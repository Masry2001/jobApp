<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResumeRequestValidator extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'new_resume' => ['required', 'file', 'mimetypes:application/pdf', 'max:5120'],
        ];
    }

    public function messages()
    {
        return [
            'new_resume.required' => 'The resume field is required.',
            'new_resume.file' => 'The resume must be a file.',
            'new_resume.mimetypes' => 'The resume must be a PDF file.',
            'new_resume.max' => 'The resume may not be greater than 5MB.',
        ];
    }
}
