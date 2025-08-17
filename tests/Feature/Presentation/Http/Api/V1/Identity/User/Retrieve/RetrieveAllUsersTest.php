<?php

namespace Feature\Presentation\Http\Api\V1\Identity\User\Retrieve;

use App\Infrastructure\Persistence\Models\User as LaravelUserModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RetrieveAllUsersTest extends TestCase
{
    use DatabaseTransactions;

    private const string RETRIEVE_ALL_USERS_ENDPOINT = '/api/users';

    #[Test]
    public function itShouldReturnAllUsersInDatabase(): void
    {
        LaravelUserModel::factory(20)->create();

        $usersQuantity = LaravelUserModel::all()->count();

        $this
            ->getJson(self::RETRIEVE_ALL_USERS_ENDPOINT)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json): AssertableJson => $json->has('data', $usersQuantity));
    }

    #[Test]
    public function itShouldReturnEmptyArrayWhenNoUsersInDatabase(): void
    {
        $this
            ->getJson(self::RETRIEVE_ALL_USERS_ENDPOINT)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json): AssertableJson => $json
                ->has('data', 0)
                ->whereType('data', 'array')
            );
    }
}
