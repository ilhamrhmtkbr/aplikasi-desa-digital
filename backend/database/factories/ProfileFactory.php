<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'thumbnail' => $this->faker->imageUrl(),
            'name' => $this->faker->city,
            'about' => $this->faker->sentence,
            'headman' => $this->faker->name,
            'people' => 39,
            'agricultural_area' => 39.02,
            'total_area' => 79.02,
        ];
    }
}
