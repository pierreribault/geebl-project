<?php

namespace Database\Factories;

use App\Enums\TicketStatus;
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
            'status' => TicketStatus::NonUsed->value,
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
                'status' => TicketStatus::NonUsed->value,
            ];
        });
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TicketStatus::Pending->value,
            ];
        });
    }
}
