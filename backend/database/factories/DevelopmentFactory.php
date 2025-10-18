<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Development>
 */
class DevelopmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'thumbnail'=>$this->faker->imageUrl(),
            'name'=>$this->faker->company,
            'description'=>$this->faker->sentence,
            'person_in_charge'=>$this->faker->name,
            'start_date'=>$this->faker->dateTimeBetween('-1years', 'now'),
            'end_date' => $this->faker->dateTime('+1 years'),
            'amount'=>$this->faker->numberBetween(1000,9000),
            'status'=>$this->faker->randomElement(['ongoing', 'completed'])
        ];
    }
}
