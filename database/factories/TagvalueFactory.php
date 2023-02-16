<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tagvalue>
 */
class TagvalueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //'value' => random_int(0, 200000),
            'description' => fake()->words(random_int(2,10), true),
            //'tag_id_first' => Tag::query()->where('user_id', 1)->get(['id'])->random(),
            //'tag_id_second' => Tag::query()->where('user_id', 1)->get(['id'])->random(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
