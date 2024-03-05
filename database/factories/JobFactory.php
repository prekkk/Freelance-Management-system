<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()-> name,
            'user_id' => 3,
            'job_type_id' => rand(1,5),
            'category_id' => rand(1,8),
            'vacancy' => rand(1,5),
            'location' => fake()-> city,
            'description' => fake()-> text,
        ];
    }
}
