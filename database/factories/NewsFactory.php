<?php

namespace Database\Factories;

use App\Enums\NewsStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name(),
            'description' => $this->faker->sentence(),
            'date' => $this->faker->date(),
            'slug' => $this->faker->slug(),
            'status' => $this->faker->randomElement(array_column(NewsStatus::cases(), 'value'))
        ];
    }
}
