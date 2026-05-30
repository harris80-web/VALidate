<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Question;
use App\Enums\QuestionCategoryType;

class CitizenCharterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // allow all users
    }

    public function rules(): array
    {
        $rules = [];

        // Only fetch Citizen's Charter questions
        $questions = Question::getAllQuestionsByCategoryType(QuestionCategoryType::CITIZEN_CHARTER);

        foreach ($questions as $question) {
            $rules['question_' . $question->id] = [
                'required',
                'in:' . $question->options->pluck('id')->join(',')
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [];
        $questions = Question::getAllQuestionsByCategoryType(QuestionCategoryType::CITIZEN_CHARTER);

        foreach ($questions as $question) {
            $messages['question_' . $question->id . '.required'] = 'Please answer "' . $question->name . '".';
            $messages['question_' . $question->id . '.in'] = 'Invalid selection for "' . $question->name . '".';
        }

        return $messages;
    }
}
