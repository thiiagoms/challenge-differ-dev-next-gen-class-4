<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'stored_book_id' => rand(1, 10),
            'user_id' => rand(1, 10),
            'reserved_at' => $this->faker->dateTimeBetween('-60 days', '-30 days'),
            'returned_at' => $this->faker->dateTimeBetween('-30 days', '-10 days'),
        ];
    }
}
