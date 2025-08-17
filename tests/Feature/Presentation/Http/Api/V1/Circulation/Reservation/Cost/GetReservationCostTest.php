<?php

namespace Tests\Feature\Presentation\Http\Api\V1\Circulation\Reservation\Cost;

use App\Infrastructure\Persistence\Models\Book as LaravelBookModel;
use App\Infrastructure\Persistence\Models\Reservation as LaravelReservationModel;
use App\Infrastructure\Persistence\Models\StoredBook as LaravelStoredBookModel;
use App\Infrastructure\Persistence\Models\User as LaravelUserModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GetReservationCostTest extends TestCase
{
    use DatabaseTransactions;

    private const string GET_RESERVATION_COST = '/api/reservations/cost';

    /**
     * @throws \DateMalformedStringException
     */
    #[Test]
    public function itShouldReturnReservationCost(): void
    {
        $user = LaravelUserModel::factory()->create();

        $book = LaravelBookModel::factory()->create();

        $storedBook = LaravelStoredBookModel::factory()->create(['book_id' => $book->id]);

        $reservedDate = new \DateTimeImmutable;

        $reservation = LaravelReservationModel::factory()->create([
            'user_id' => $user->id,
            'stored_book_id' => $storedBook->id,
            'reserved_at' => $reservedDate->modify('-1 day')->format('Y-m-d H:i:s'),
            'returned_at' => null,
        ]);

        $this
            ->getJson(self::GET_RESERVATION_COST."?reservation_id={$reservation->id}")
            ->assertOk()
            ->assertJson(fn (AssertableJson $json): AssertableJson => $json
                ->hasAll([
                    'data',
                    'data.reservation_cost',
                    'data.cost_per_day',
                    'data.reservedDays',
                    'data.reservation',
                    'data.reservation.id',
                    'data.reservation.user_id',
                    'data.reservation.reserved_at',
                    'data.reservation.stored_book_id',
                    'data.reservation.created_at',
                    'data.reservation.updated_at',
                ])
                ->whereAllType([
                    'data' => 'array',
                    'data.reservation_cost' => 'string',
                    'data.cost_per_day' => 'string',
                    'data.reservedDays' => 'integer',
                    'data.reservation' => 'array',
                    'data.reservation.id' => 'integer',
                    'data.reservation.user_id' => 'integer',
                    'data.reservation.reserved_at' => 'string',
                    'data.reservation.stored_book_id' => 'integer',
                    'data.reservation.created_at' => 'string',
                    'data.reservation.updated_at' => 'string',
                ])
                ->whereAll([
                    'data.reservation_cost' => 'R$ 4,50',
                    'data.cost_per_day' => 'R$ 4,50',
                    'data.reservedDays' => 1,
                    'data.reservation.id' => $reservation->id,
                    'data.reservation.user_id' => $reservation->user_id,
                ])
            );
    }

    #[Test]
    public function itShouldReturnReservationNotFoundWhenReservationDoesNotExists(): void
    {
        $this
            ->getJson(self::GET_RESERVATION_COST.'?reservation_id=9999')
            ->assertNotFound()
            ->assertJson(fn (AssertableJson $json): AssertableJson => $json
                ->has('error')
                ->whereType('error', 'string')
                ->where('error', 'Reservation not found')
            );
    }
}
