<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyJobRequest;
use App\Models\JobVacancy;
use App\Services\ResumeAnalysisService;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Gemini\Laravel\Facades\Gemini;
use App\Models\Resume;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobVacancyController extends Controller
{

    protected $resumeAnalysisService;
    public function __construct(ResumeAnalysisService $resumeAnalysisService)
    {
        $this->resumeAnalysisService = $resumeAnalysisService;
    }

    public function show(JobVacancy $jobVacancy)
    {
        $sessionKey = 'job_viewed_' . $jobVacancy->id;

        if (!session()->has($sessionKey)) {
            $jobVacancy->increment('viewCount');
            session()->put($sessionKey, true);
        }
        return view('job-vacancies.show', compact('jobVacancy'));
    }

    public function apply(JobVacancy $jobVacancy)
    {

        // Check if the user has already applied for this job
        if (
            JobApplication::where('jobVacancyId', $jobVacancy->id)
                ->where('userId', auth()->id())
                ->exists()
        ) {
            return redirect()->route('job-vacancies.show', $jobVacancy)
                ->with('error', 'You have already applied for this job.');
        }

        // Get user's existing resumes
        $resumes = Resume::where('userId', auth()->id())
            ->latest()
            ->get();

        return view('job-vacancies.apply', compact('jobVacancy', 'resumes'));
    }

    /*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Process a job application submission.
     *
     * @param ApplyJobRequest $request
     * @param JobVacancy $jobVacancy
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    /*******  e2402925-4648-415c-baeb-a98d8eb452cb  *******/
    public function processApplication(
        ApplyJobRequest $request,
        JobVacancy $jobVacancy
    ) {
        try {
            $resumeId = null;
            $resumeOption = $request->input('resume_option');
            $extractedResumeInfo = null; // Initialize the variable

            // Handle based on selected option
            if ($resumeOption === 'existing_resume') {
                // User selected an existing resume
                $resumeId = $request->input('existing_resume');

                // Verify the resume belongs to the authenticated user
                $resume = Resume::where('id', $resumeId)
                    ->where('userId', auth()->id())
                    ->firstOrFail();

                // Get extracted info from existing resume
                $extractedResumeInfo = [
                    'summary' => $resume->summary,
                    'skills' => $resume->skills,
                    'experience' => $resume->experience,
                    'education' => $resume->education,
                ];

            } elseif ($resumeOption === 'new_resume') {
                // User uploaded a new resume
                $resumeFile = $request->file('new_resume');
                $originalFileName = $resumeFile->getClientOriginalName();
                $filename = Str::uuid() . '_' . time() . '.pdf';

                // Upload to Supabase
                $path = Storage::disk('supabase')->putFileAs(
                    'resumes',
                    $resumeFile,
                    $filename,
                    'public'
                );

                $publicUrl = $this->getSupabasePublicUrl($path);

                // Extract resume info
                $extractedResumeInfo = $this->resumeAnalysisService->extractResumeInformation($publicUrl);

                // Create new resume entry
                $resume = Resume::create([
                    'fileName' => $originalFileName,
                    'fileUri' => $path,
                    'publicUrl' => $publicUrl,
                    'userId' => auth()->id(),
                    'contactDetails' => json_encode([
                        'name' => auth()->user()->name,
                        'email' => auth()->user()->email,
                    ]),
                    'summary' => $extractedResumeInfo['summary'],
                    'skills' => $extractedResumeInfo['skills'],
                    'experience' => $extractedResumeInfo['experience'],
                    'education' => $extractedResumeInfo['education'],
                ]);

                $resumeId = $resume->id;
            } else {
                // This should never happen due to validation, but just in case
                return back()
                    ->withInput()
                    ->withErrors(['resume_option' => 'Invalid resume option selected.']);
            }

            // AI (Gemini API) will evaluate job application using extracted resume info
            $evaluation = $this->resumeAnalysisService->analyzeResume($jobVacancy, $extractedResumeInfo);

            // Create job application
            JobApplication::create([
                'status' => 'pending',
                'jobVacancyId' => $jobVacancy->id,
                'resumeId' => $resumeId,
                'userId' => auth()->id(),
                'aiGeneratedScore' => $evaluation['aiGeneratedScore'],
                'aiGeneratedFeedback' => $evaluation['aiGeneratedFeedback'],
            ]);

            return redirect()
                ->route('job-applications.index')
                ->with('success', 'Application submitted successfully!');

        } catch (\Exception $e) {
            \Log::error('Application submission failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['resume_option' => 'Failed to process application. Please try again.']);
        }
    }
    /**
     * Generate Supabase public URL from path
     */
    private function getSupabasePublicUrl(string $path): string
    {
        $projectUrl = env('PROJECT_URL');
        $bucketName = env('SUPABASE_BUCKET');

        return "{$projectUrl}/storage/v1/object/public/{$bucketName}/{$path}";
    }


    public function testOpenAI()
    {
        $response = OpenAI::responses()->create([
            'model' => 'gpt-5',
            'input' => 'Hello!',
        ]);

        echo $response->outputText; // Hello! How can I assist you today?
    }

    public function testGemini()
    {

        $result = Gemini::generativeModel(
            model: 'gemini-2.0-flash-001'
        )->generateContent('hello, how can you help me today?');

        echo $result->text(); // Hello! How can I assist you today?
    }

    public function listModels()
    {
        $response = Gemini::models()->list();

        return $response->models;
    }
}
