<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\Log;
use Gemini\Laravel\Facades\Gemini;
use Gemini\Data\Content;
use Gemini\Data\GenerationConfig;
use Gemini\Data\Schema;
use Gemini\Enums\DataType;
use Gemini\Enums\ResponseMimeType;



class ResumeAnalysisService
{
  public function extractResumeInformation(string $publicUrl)
  {
    // read the pdf file and get the raw text, we will use spatie pdf to text
    $rawText = $this->extractTextFromUrl($publicUrl);

    Log::debug('Successfully extracted resume information with ' . strlen($rawText) . ' characters');

    // use Gemini api to get the resume information as json
    return $this->extractResumeInformationUsingGemini($rawText);
  }

  public function extractResumeInformationFromPath(string $filePath)
  {
    $rawText = $this->extractTextFromPath($filePath);

    Log::debug('Successfully extracted resume information from path with ' . strlen($rawText) . ' characters');

    return $this->extractResumeInformationUsingGemini($rawText);
  }

  // Gemini Call#2
  public function analyzeResume($jobVacancy, $extractedResumeInfo)
  {
    try {
      // Encode job vacancy data to json to send to gemini
      $jobVacancyJson = json_encode([
        'jobTitle' => $jobVacancy->title,
        'description' => $jobVacancy->description,
        'location' => $jobVacancy->location,
        'type' => $jobVacancy->type,
        'salary' => $jobVacancy->salary,
      ]);

      // Encode resume data to json to send to gemini
      $resumeDataJson = json_encode($extractedResumeInfo);

      // Send to gemini

      $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
        ->withSystemInstruction(
          Content::parse('You are an expert HR professional and job recruiter. You are given a job vacancy and a resume. Your task is to analyze and determine if the candidate is a good fit for the job. Provide a score from 0 to 100 for the candidate suitability for the job and detailed feedback. Calculate the score based on the following weights: Skills (20%), Education (20%), Experience (50%), Summary (10%).')
        )
        ->withGenerationConfig(
          generationConfig: new GenerationConfig(
            responseMimeType: ResponseMimeType::APPLICATION_JSON,
            responseSchema: new Schema(
              type: DataType::OBJECT,
              properties: [
                'aiGeneratedScore' => new Schema(type: DataType::INTEGER),
                'aiGeneratedFeedback' => new Schema(type: DataType::STRING)
              ],
              required: ['aiGeneratedScore', 'aiGeneratedFeedback']
            ),
            temperature: 0.3
          )
        )
        ->generateContent(
          'Please evaluate this job application. Job details: ' . $jobVacancyJson . ' Resume details: ' . $resumeDataJson
        );

      Log::debug('Gemini Evaluation response: ' . $result->text());

      $parsedResult = json_decode($result->text(), true);

      if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Failed to parse Gemini response: ' . json_last_error_msg());
      }

      if (!isset($parsedResult['aiGeneratedScore']) || !isset($parsedResult['aiGeneratedFeedback'])) {
        throw new Exception('Missing required fields in Gemini response');
      }

      return $parsedResult;

    } catch (Exception $e) {
      Log::error('Failed to analyze resume: ' . $e->getMessage());

      return [
        'aiGeneratedScore' => 0,
        'aiGeneratedFeedback' => 'Failed to analyze resume. Please try again later.'
      ];
    }
  }

//for remote files
  private function extractTextFromUrl(string $publicUrl)
  {
    $tempFile = tempnam(sys_get_temp_dir(), 'resume');
    $filePath = parse_url($publicUrl, PHP_URL_PATH);

    if (!$filePath) {
      throw new Exception('Invalid file Url');
    }
    $fileName = basename($filePath);
    $storagePath = "resumes/$fileName";

    if (!Storage::disk('supabase')->exists($storagePath)) {
      throw new Exception('File not found');
    }

    $pdfContent = Storage::disk('supabase')->get($storagePath);
    if (!$pdfContent) {
      throw new Exception('Failed to get pdf content');
    }

    file_put_contents($tempFile, $pdfContent);

    try {
        return $this->extractTextFromPath($tempFile);
    } finally {
        unlink($tempFile);
    }
  }

    //for local files
  private function extractTextFromPath(string $filePath)
  {
    $this->checkPdfToTextInstalled();

    return (new Pdf())
      ->setPdf($filePath)
      ->text();
  }

  private function checkPdfToTextInstalled()
  {
    $pdfToText = ['/usr/bin/pdftotext', '/opt/homebrew/bin/pdftotext', '/usr/local/bin/pdftotext'];

    foreach ($pdfToText as $path) {
      if (file_exists($path)) {
        return true;
      }
    }

    throw new Exception('pdftotext is not installed, you can install it from this link: https://github.com/spatie/pdf-to-text');
  }


    // Gemini Call#1
  private function extractResumeInformationUsingGemini(string $text)
  {
    try {
      $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
        ->withSystemInstruction(
          Content::parse('You are a precision resume analyzer. Your job is to analyze a resume and extract information exactly as it appears in the resume without adding any additional information.')
        )
        ->withGenerationConfig(
          generationConfig: new GenerationConfig(
            responseMimeType: ResponseMimeType::APPLICATION_JSON,
            responseSchema: new Schema(
              type: DataType::OBJECT,
              properties: [
                'skills' => new Schema(type: DataType::STRING),
                'education' => new Schema(type: DataType::STRING),
                'experience' => new Schema(type: DataType::STRING),
                'summary' => new Schema(type: DataType::STRING)
              ],
              required: ['skills', 'education', 'experience', 'summary']
            ),
            temperature: 0.1
          )
        )
        ->generateContent(
          'Parse the following resume into a JSON object. Extract the following information: skills, education, experience, summary. Return an empty string for keys that are not present in the resume. Resume Content: ' . $text
        );

      Log::debug('Successfully extracted resume information: ' . $result->text());

      $parsedResult = json_decode($result->text(), true);

      $this->validateResumeKeys($parsedResult);

      return [
        'skills' => $parsedResult['skills'],
        'education' => $parsedResult['education'],
        'experience' => $parsedResult['experience'],
        'summary' => $parsedResult['summary']
      ];

    } catch (Exception $e) {
      Log::error('Failed to extract resume information: ' . $e->getMessage());
      return [
        'skills' => '',
        'education' => '',
        'experience' => '',
        'summary' => ''
      ];
    }
  }

  private function validateResumeKeys(array $parsedResult)
  {
      $requiredKeys = ['skills', 'education', 'experience', 'summary'];
      $missingKeys = array_diff($requiredKeys, array_keys($parsedResult ?? []));

      if (count($missingKeys) > 0) {
        Log::error('Missing keys: ' . implode(', ', $missingKeys));
        throw new Exception('Missing keys: ' . implode(', ', $missingKeys));
      }
  }

}
