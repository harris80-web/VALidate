<?php

namespace App\Http\Requests;

use App\Enums\QuestionCategoryType;
use App\Models\Question;
use Illuminate\Foundation\Http\FormRequest;

class ServiceQualityDimensionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [];

        // Only fetch Citizen's Charter questions
        $questions = Question::getAllQuestionsByCategoryType(QuestionCategoryType::SERVICE_QUALITY_DIMENSION);

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
        $questions = Question::getAllQuestionsByCategoryType(QuestionCategoryType::SERVICE_QUALITY_DIMENSION);

        foreach ($questions as $question) {
            $messages['question_' . $question->id . '.required'] = 'Please answer "' . $question->name . '".';
            $messages['question_' . $question->id . '.in'] = 'Invalid selection for "' . $question->name . '".';
        }

        return $messages;
    }
}
