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
use App\Jobs\AnalyzeJobApplication;
use App\Jobs\ExtractResumeText;

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


    public function processApplication(
        ApplyJobRequest $request,
        JobVacancy $jobVacancy
    ) {
        $startTime = microtime(true);
        try {
            $resumeId = null;
            $resumeOption = $request->input('resume_option');
            $extractedResumeInfo = null; // Initialize the variable

            // Handle based on selected option
            // ** create a seperate function to handle these conditions**
            if ($resumeOption === 'existing_resume') {
                // ** create a seperate function to handle this condition**
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

                // Store file locally for background processing
                $path = $resumeFile->storeAs('temp', $filename);
                $absolutePath = Storage::path($path);

                // Create new resume entry with placeholders
                $resume = Resume::create([
                    'fileName' => $originalFileName,
                    'fileUri' => 'processing',
                    'publicUrl' => 'processing',
                    'userId' => auth()->id(),
                    'contactDetails' => json_encode([
                        'name' => auth()->user()->name,
                        'email' => auth()->user()->email,
                    ]),
                    'summary' => json_encode([]),
                    'skills' => json_encode([]),
                    'experience' => json_encode([]),
                    'education' => json_encode([]),
                ]);

                $resumeId = $resume->id;

                // Create job application
                $jobApplication = JobApplication::create([
                    'status' => 'Pending',
                    'jobVacancyId' => $jobVacancy->id,
                    'resumeId' => $resumeId,
                    'userId' => auth()->id(),
                    'aiGeneratedScore' => null,
                    'aiGeneratedFeedback' => null,
                ]);

                // Dispatch extraction job (which will trigger analysis and upload)
                ExtractResumeText::dispatch($absolutePath, $resume, $jobApplication);

            } else {
                // This should never happen due to validation, but just in case
                return back()
                    ->withInput()
                    ->withErrors(['resume_option' => 'Invalid resume option selected, it is neither existing resume nor new resume.']);
            }

            // For existing resumes, we need to create the application and dispatch analysis here
            if ($resumeOption === 'existing_resume') {
                 // Create job application
                $jobApplication = JobApplication::create([
                    'status' => 'Pending',
                    'jobVacancyId' => $jobVacancy->id,
                    'resumeId' => $resumeId,
                    'userId' => auth()->id(),
                    'aiGeneratedScore' => null,
                    'aiGeneratedFeedback' => null,
                ]);

                // Dispatch AI analysis directly
                AnalyzeJobApplication::dispatch($jobApplication, $extractedResumeInfo);
            }

            $duration = round(microtime(true) - $startTime, 2);
            \Log::info("Job application processed in {$duration}s for user ID: " . auth()->id());

            return redirect()
                ->route('job-applications.index')
                ->with('success', 'Application received! AI analysis is processing in the background.');

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

        // top 10 modles for out task

        // gemini-2.5-pro
        // gemini-3-pro-preview
        // gemini-2.5-flash
        // gemini-flash-latest
        // gemini-3-flash-preview
        // gemini-2.5-flash-lite
        // gemini-pro-latest
        // gemma-3-27b-it
        // gemma-3-12b-it
        // gemini-2.0-flash
        $response = Gemini::models()->list();

        return $response->models;
    }
}
