<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'used' => $this->faker->boolean(),
        ];
    }

    /**
     * Indicate that the ticket should be unused.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unused()
    {
        return $this->state(function (array $attributes) {
            return [
                'used' => false,
            ];
        });
    }
}
