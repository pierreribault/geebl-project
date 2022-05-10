<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seller>
 */
class SellerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'is_owner' => false,
            'is_redactor' => false,
            'is_reviewer' => false,
            'is_consumer' => false,
        ];
    }

    public function owner()
    {
        return $this->state(fn (array $attributes) => ['is_owner' => true]);
    }

    public function redactor()
    {
        return $this->state(fn (array $attributes) => ['is_redactor' => true]);
    }

    public function reviewer()
    {
        return $this->state(fn (array $attributes) => ['is_reviewer' => true]);
    }

    public function consumer()
    {
        return $this->state(fn (array $attributes) => ['is_consumer' => true]);
    }
}
