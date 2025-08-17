<?php

namespace Database\Factories\Infrastructure\Persistence\Models;

use App\Infrastructure\Persistence\Models\Reservation as LaravelReservationModel;
use App\Infrastructure\Persistence\Models\StoredBook as LaravelStoredBookModel;
use App\Infrastructure\Persistence\Models\User as LaravelUserModel;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LaravelReservationModel>
 */
class ReservationFactory extends Factory
{
    protected $model = LaravelReservationModel::class;

    /**
     * @throws \DateMalformedStringException
     */
    public function definition(): array
    {
        return [
            'stored_book_id' => LaravelStoredBookModel::factory()->createOne(),
            'user_id' => LaravelUserModel::factory()->createOne(),
            'reserved_at' => (new DateTimeImmutable(sprintf('-%d days', rand(30, 60))))
                ->format('Y-m-d H:i:s'),
            'returned_at' => (new DateTimeImmutable(sprintf('-%d days', rand(10, 30))))
                ->format('Y-m-d H:i:s'),
        ];
    }
}
