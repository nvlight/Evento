<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user_id = 333;
        return [
            'user_id' => $user_id,
            'name' => fake()->word,
            'description' => fake()->words(random_int(3,5), true),
            'text_color' => fake()->hexColor, // '#ffffff',
            'bg_color' => fake()->hexColor, //'#5CB85C',
        ];
    }
}
