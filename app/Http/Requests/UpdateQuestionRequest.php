<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'options' => ['nullable', 'array'],
            'options.*.id' => ['nullable', 'integer', 'exists:question_options,id'],
            'options.*.description' => ['required', 'string', 'max:1000'],
            'deleted_option_ids' => ['nullable', 'array'],
            'deleted_option_ids.*' => ['integer', 'exists:question_options,id'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Question name is required.',
        ];
    }
}
