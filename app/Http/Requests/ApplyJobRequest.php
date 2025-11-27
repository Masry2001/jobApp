<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'resume_option' => ['required', 'in:existing_resume,new_resume'],
            'new_resume' => ['nullable', 'required_if:resume_option,new_resume', 'file', 'mimetypes:application/pdf', 'max:5120'],
            'existing_resume' => ['nullable', 'required_if:resume_option,existing_resume', 'exists:resumes,id'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $resumeOption = $this->input('resume_option');

            // Validate based on selected option
            if ($resumeOption === 'new_resume') {
                if (!$this->hasFile('new_resume')) {
                    $validator->errors()->add('new_resume', 'Please upload a PDF resume.');
                }
            } elseif ($resumeOption === 'existing_resume') {
                if (!$this->input('existing_resume')) {
                    $validator->errors()->add('existing_resume', 'Please select an existing resume.');
                }
            } else {
                $validator->errors()->add('resume_option', 'Please select either an existing resume or upload a new one.');
            }
        });
    }

    public function messages()
    {
        return [
            'resume_option.required' => 'Please select a resume option.',
            'resume_option.in' => 'Invalid resume option selected.',
            'new_resume.required_if' => 'Please upload a resume.',
            'new_resume.file' => 'The resume must be a valid file.',
            'new_resume.mimetypes' => 'The resume must be a PDF file.',
            'new_resume.max' => 'The resume file size must not exceed 5MB.',
            'existing_resume.required_if' => 'Please select an existing resume.',
            'existing_resume.exists' => 'The selected resume does not exist.',
        ];
    }
}