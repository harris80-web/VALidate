<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    public function authorize()
    {
        // Only allow authenticated admins — middleware already applied to route
        return true;
    }

    public function rules()
    {
        return [
            'category_id' => ['required', 'exists:question_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'options' => ['nullable', 'array'],
            'options.*' => ['required_with:options', 'string', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'Selected category does not exist.',
            'name.required' => 'Question name is required.',
            'options.*.required_with' => 'Each option must be a non-empty string.',
        ];
    }
}
