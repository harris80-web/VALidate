<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Enums\RespondentType;
use Illuminate\Foundation\Http\FormRequest;

class SurveyStartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow all users for this survey
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:' . implode(',', RespondentType::asArray())],
            'submitted_date' => ['required', 'date', 'after_or_equal:today'],
            'age'  => 'required|integer|min:1|max:120',
            'gender' => ['required', 'in:' . implode(',', Gender::asArray())],
            'region' => 'required|exists:regions,id',
            'service'  => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Please select a client type.',
            'service.required' => 'Please describe the service availed.',
        ];
    }
}
