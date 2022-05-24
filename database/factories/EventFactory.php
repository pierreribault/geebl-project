<?php

namespace Database\Factories;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'slug' => $this->faker->slug(),
            'location' => $this->faker->address(),
            'start_at' => $this->faker->date(),
            'end_at' => $this->faker->date(),
            'description' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(EventStatus::getValues()),
        ];
    }
}
