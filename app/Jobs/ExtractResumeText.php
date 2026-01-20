<?php

namespace App\Jobs;

use App\Models\JobApplication;
use App\Models\Resume;
use App\Services\ResumeAnalysisService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ExtractResumeText implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $tempFilePath,
        public Resume $resume,
        public JobApplication $jobApplication
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ResumeAnalysisService $resumeAnalysisService): void
    {
        Log::info("Starting resume text extraction for Resume ID: {$this->resume->id}");

        try {
            // 1. Extract info from local temp file
            $extractedResumeInfo = $resumeAnalysisService->extractResumeInformationFromPath($this->tempFilePath);

            // 2. Update Resume record with extracted info
            $this->resume->update([
                'summary' => json_encode($extractedResumeInfo['summary']),
                'skills' => json_encode($extractedResumeInfo['skills']),
                'experience' => json_encode($extractedResumeInfo['experience']),
                'education' => json_encode($extractedResumeInfo['education']),
            ]);

            Log::info("Resume text extracted for Resume ID: {$this->resume->id}");

            // 3. Dispatch Analysis Job
            AnalyzeJobApplication::dispatch($this->jobApplication, $extractedResumeInfo);
            Log::info("Dispatched AnalyzeJobApplication for Job Application ID: {$this->jobApplication->id}");

            // 4. Dispatch Upload Job (Pass temp path to upload it next)
            UploadResumeToStorage::dispatch($this->tempFilePath, $this->resume);
            Log::info("Dispatched UploadResumeToStorage for Resume ID: {$this->resume->id}");

        } catch (\Exception $e) {
            Log::error("Failed to extract text for Resume ID: {$this->resume->id}. Error: " . $e->getMessage());
            // Optionally handle failure (e.g., mark resume as failed)
        }
    }
}
