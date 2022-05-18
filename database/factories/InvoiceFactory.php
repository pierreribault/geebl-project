<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Product;
use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'quantity' => $this->faker->randomNumber(2),
            'status' => $this->faker->randomElement(array_column(InvoiceStatus::cases(), 'value')),
        ];
    }
}
