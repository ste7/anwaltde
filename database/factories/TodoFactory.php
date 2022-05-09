<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'userId' => $this->faker->numberBetween(1, User::count()),
            'dueOn' => $this->faker->dateTime()->format('Y-m-d'),
            'title' => $this->faker->sentence(),
            'status' => ['pending', 'completed'][rand(0, 1)],
        ];
    }
}
