<?php

namespace Feature\Presentation\Http\Api\V1\Circulation\Reservation\Register;

use App\Models\Book as LaravelBookModel;
use App\Models\StoredBook as LaravelStoredBookModel;
use App\Models\User as LaravelUserModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterReservationTest extends TestCase
{
    use DatabaseTransactions;

    private const string REGISTER_RESERVATION_ENDPOINT = '/api/reservations';

    #[Test]
    public function itShouldCreateANewReservation(): void
    {
        $user = LaravelUserModel::factory()->createOne();

        $book = LaravelBookModel::factory()->createOne();

        $storedBookId = LaravelStoredBookModel::factory()->createOne(['book_id' => $book->id]);

        $this
            ->postJson(
                uri: self::REGISTER_RESERVATION_ENDPOINT,
                data: ['user_id' => $user->id, 'stored_book_id' => $storedBookId->id]
            )
            ->assertCreated()
            ->assertJson(fn (AssertableJson $json): AssertableJson => $json
                ->hasAll([
                    'data',
                    'data.id',
                    'data.user_id',
                    'data.stored_book_id',
                    'data.reserved_at',
                    'data.created_at',
                    'data.updated_at',
                ])
                ->whereAllType([
                    'data' => 'array',
                    'data.id' => 'integer',
                    'data.user_id' => 'integer',
                    'data.stored_book_id' => 'integer',
                    'data.reserved_at' => 'string',
                    'data.created_at' => 'string',
                    'data.updated_at' => 'string',
                ])
                ->whereAll([
                    'data.user_id' => $user->id,
                    'data.stored_book_id' => $storedBookId->id,
                ])
            );
    }

    #[Test]
    public function itShouldReturnUserNotFoundWhenUserDoesNotExists(): void
    {
        $this
            ->postJson(
                uri: self::REGISTER_RESERVATION_ENDPOINT,
                data: ['user_id' => 12, 'stored_book_id' => 12]
            )
            ->assertNotFound()
            ->assertJson(fn (AssertableJson $json): AssertableJson => $json
                ->has('error')
                ->whereType(key: 'error', expected: 'string')
                ->where(key: 'error', expected: 'User not found')
            );
    }

    #[Test]
    public function itShouldReturnStoredBookNotFoundWhenBookDoesNotExists(): void
    {
        $user = LaravelUserModel::factory()->createOne();

        $this
            ->postJson(
                uri: self::REGISTER_RESERVATION_ENDPOINT,
                data: ['user_id' => $user->id, 'stored_book_id' => rand(191, 1099)]
            )
            ->assertNotFound()
            ->assertJson(fn (AssertableJson $json): AssertableJson => $json
                ->has('error')
                ->whereType(key: 'error', expected: 'string')
                ->where(key: 'error', expected: 'Stored book not found')
            );
    }
}
