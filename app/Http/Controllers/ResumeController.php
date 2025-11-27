<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\ResumeRequestValidator;

class ResumeController extends Controller
{


    //The URL format should be:

    ////https://rybxfrlqueuyunigopty.supabase.co/storage/v1/object/public/ShaghalniResumes/resumes/your-file.pdf
    public function upload(ResumeRequestValidator $request)
    {
        try {
            $file = $request->file('new_resume');
            $filename = Str::uuid() . '_' . time() . '.pdf';

            $path = Storage::disk('supabase')->putFileAs(
                'resumes',
                $file,
                $filename,
                'public'
            );

            // Use the helper method
            $publicUrl = $this->getSupabasePublicUrl($path);

            return response()->json([
                'message' => 'Resume uploaded successfully',
                'path' => $path,
                'url' => $publicUrl,
                'filename' => $filename
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Upload failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function download($filename)
    {
        try {
            $path = 'resumes/' . $filename;

            if (!Storage::disk('supabase')->exists($path)) {
                return response()->json(['message' => 'File not found'], 404);
            }

            $file = Storage::disk('supabase')->get($path);

            return response($file)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Download failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($filename)
    {
        try {
            $path = 'resumes/' . $filename;

            if (!Storage::disk('supabase')->exists($path)) {
                return response()->json(['message' => 'File not found'], 404);
            }

            Storage::disk('supabase')->delete($path);

            return response()->json(['message' => 'Resume deleted successfully'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Delete failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function list()
    {
        try {
            $files = Storage::disk('supabase')->files('resumes');

            return response()->json([
                'files' => $files,
                'count' => count($files)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to list files',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUrl($filename)
    {
        try {
            $path = 'resumes/' . $filename;

            if (!Storage::disk('supabase')->exists($path)) {
                return response()->json(['message' => 'File not found'], 404);
            }

            // Use public URL format
            $url = $this->getSupabasePublicUrl($path);

            return response()->json(['url' => $url], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to get URL',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Generate Supabase public URL
     */
    private function getSupabasePublicUrl(string $path): string
    {
        $projectUrl = env('PROJECT_URL');
        $bucketName = env('SUPABASE_BUCKET');

        return "{$projectUrl}/storage/v1/object/public/{$bucketName}/{$path}";
    }
}