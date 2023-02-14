<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    //protected $model = \App\Models\Tag::class;

    public function suspended()
    {
        return $this->state(function (array $attributes) {
            return [
                'remember_token' => 'chichini!',
            ];
        });
    }

    // $user = User::factory()->has(Tag::factory()->count(3), 'tags')->create();
    // $user = User::factory()->has(Tag::factory()->count(3))->create();
    // $user = User::factory()->hasTags(3)->create();

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => fake()->name('male'),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            //'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            //'password' => '$2y$10$ks.Goir1Ij/usF9ALB36DO7AbHxRzB1Ccfz3kBdUzfF5wwk8XCITS', // 12345678aA@
            'password' => Hash::make('12345678aA@'), // 12345678aA@
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
