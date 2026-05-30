<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuggestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'nullable|email|max:255',
            'suggestion' => 'nullable|string|min:5|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot exceed 255 characters.',
            'suggestion.string' => 'Suggestion must be text.',
            'suggestion.max' => 'Suggestion cannot exceed 2000 characters.',
        ];
    }
}