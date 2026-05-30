<?php

namespace Database\Factories;

use App\Enums\QuestionCategoryType;
use App\Models\Question;
use App\Models\QuestionCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionCategory>
 */
class QuestionCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(QuestionCategoryType::asArray()),
            'name' => ucfirst($this->faker->word()),
            'description' => $this->faker->sentence(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (QuestionCategory $category) {
            Question::factory()->count(3)->create([
                'category_id' => $category->id
            ]);
        });
    }
}
