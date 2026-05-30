<?php

namespace Database\Factories;

use App\Enums\QuestionType;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(QuestionType::asArray()),
            'name' => $this->faker->sentence(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Question $question) {
            QuestionOption::factory()->count(4)->create([
                'question_id' => $question->id,
            ]);
        });
    }
}
