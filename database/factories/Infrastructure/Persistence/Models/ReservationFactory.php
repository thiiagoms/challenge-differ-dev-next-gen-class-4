<?php

namespace Database\Factories\Infrastructure\Persistence\Models;

use App\Infrastructure\Persistence\Models\Reservation as LaravelReservationModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LaravelReservationModel>
 */
class ReservationFactory extends Factory
{
    protected $model = LaravelReservationModel::class;

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
