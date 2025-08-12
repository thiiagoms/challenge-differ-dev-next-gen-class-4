<?php

namespace Feature\Presentation\Http\Api\V1\Circulation\Reservation\Return;

use App\Models\Book as LaravelBookModel;
use App\Models\Reservation as LaravelReservationModel;
use App\Models\StoredBook as LaravelStoredBookModel;
use App\Models\User as LaravelUserModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReturnReservationTest extends TestCase
{
    use DatabaseTransactions;

    private const string RETURN_RESERVATION_ENDPOINT = '/api/reservations/return';

    /**
     * @throws \DateMalformedStringException
     */
    #[Test]
    public function itShouldAllowToReturnAReservation(): void
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

        $returnedDate = $reservedDate->format('Y-m-d H:i:s');

        $this
            ->postJson(self::RETURN_RESERVATION_ENDPOINT, [
                'reservation_id' => $reservation->id,
                'return_date' => $returnedDate,
            ])
            ->assertAccepted()
            ->assertJson(fn (AssertableJson $json): AssertableJson => $json
                ->hasAll([
                    'data',
                    'data.id',
                    'data.user_id',
                    'data.stored_book_id',
                    'data.reserved_at',
                    'data.returned_at',
                    'data.created_at',
                    'data.updated_at',
                ])
                ->whereAllType([
                    'data' => 'array',
                    'data.id' => 'integer',
                    'data.user_id' => 'integer',
                    'data.stored_book_id' => 'integer',
                    'data.reserved_at' => 'string',
                    'data.returned_at' => 'string',
                    'data.created_at' => 'string',
                    'data.updated_at' => 'string',
                ])
                ->whereAll([
                    'data.id' => $reservation->id,
                    'data.user_id' => $reservation->user_id,
                    'data.stored_book_id' => $reservation->stored_book_id,
                    'data.reserved_at' => $reservation->reserved_at,
                    'data.returned_at' => $returnedDate,
                ])
            );
    }

    #[Test]
    public function itShouldNotAllowToReturnAReservationWhenItIsNotReserved(): void
    {
        $this
            ->postJson(self::RETURN_RESERVATION_ENDPOINT, [
                'reservation_id' => 9999,
                'return_date' => '',
            ])
            ->assertNotFound()
            ->assertJson(fn (AssertableJson $json): AssertableJson => $json
                ->has('error')
                ->whereType('error', 'string')
                ->where('error', 'Reservation not found')
            );
    }

    #[Test]
    public function itShouldNotAllowToReturnAReservationTwice(): void
    {
        $user = LaravelUserModel::factory()->create();

        $book = LaravelBookModel::factory()->create();

        $storedBook = LaravelStoredBookModel::factory()->create(['book_id' => $book->id]);

        $reservedDate = new \DateTimeImmutable;

        $reservation = LaravelReservationModel::factory()->create([
            'user_id' => $user->id,
            'stored_book_id' => $storedBook->id,
            'reserved_at' => $reservedDate->modify('-1 day')->format('Y-m-d H:i:s'),
            'returned_at' => $reservedDate->modify('+1 day')->format('Y-m-d H:i:s'),
        ]);

        $this
            ->postJson(self::RETURN_RESERVATION_ENDPOINT, [
                'reservation_id' => $reservation->id,
                'return_date' => '',
            ])
            ->assertForbidden()
            ->assertJson(fn (AssertableJson $json): AssertableJson => $json
                ->has('error')
                ->whereType('error', 'string')
                ->where('error', 'Reservation already returned')
            );
    }

    #[Test]
    public function itShouldNotAllowToReturnReservationWhenReturnDateIsNotGreaterThanReservedDate(): void
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

        $returnedDate = $reservedDate->modify('-3 days')->format('Y-m-d H:i:s');

        $this
            ->postJson(self::RETURN_RESERVATION_ENDPOINT, [
                'reservation_id' => $reservation->id,
                'return_date' => $returnedDate,
            ])
            ->assertForbidden()
            ->assertJson(fn (AssertableJson $json): AssertableJson => $json
                ->has('error')
                ->whereType('error', 'string')
                ->where('error', 'Return date must be greater than reserved date')
            );
    }

    #[Test]
    public function itShouldThrowErrorWhenSaveReturnDate(): void
    {
        // TODO: Think about this test scenario
    }
}
