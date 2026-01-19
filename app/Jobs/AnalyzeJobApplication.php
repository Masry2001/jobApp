<?php

namespace App\Jobs;

use App\Models\JobApplication;
use App\Services\ResumeAnalysisService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class AnalyzeJobApplication implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public JobApplication $jobApplication,
        public array $extractedResumeInfo
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ResumeAnalysisService $resumeAnalysisService): void
    {
        Log::info("Starting AI analysis for Job Application ID: {$this->jobApplication->id}");

        try {
            // Perform the analysis
            $evaluation = $resumeAnalysisService->analyzeResume(
                $this->jobApplication->jobVacancy,
                $this->extractedResumeInfo
            );

            // Update the application with the results
            $this->jobApplication->update([
                'aiGeneratedScore' => $evaluation['aiGeneratedScore'],
                'aiGeneratedFeedback' => $evaluation['aiGeneratedFeedback'],
                'status' => 'Pending', // Keep as pending or change to 'Reviewed' if you prefer
            ]);

            Log::info("Completed AI analysis for Job Application ID: {$this->jobApplication->id}. Score: {$evaluation['aiGeneratedScore']}");

        } catch (\Exception $e) {
            Log::error("Failed to analyze Job Application ID: {$this->jobApplication->id}. Error: " . $e->getMessage());

            // Optionally mark as failed analysis in the database if you have a field for it
            // $this->jobApplication->update(['ai_analysis_status' => 'failed']);
        }
    }
}
