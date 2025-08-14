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
        $usersQuantity = LaravelUserModel::all()->count();

        $this
            ->getJson(self::RETRIEVE_ALL_USERS_ENDPOINT)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json): AssertableJson => $json
                ->count($usersQuantity)
                ->each(fn (AssertableJson $userJson): AssertableJson => $userJson
                    ->whereType('id', 'integer')
                    ->whereType('name', 'string')
                    ->whereType('email', 'string')
                    ->whereType('email_verified_at', 'string')
                    ->whereType('created_at', 'string')
                    ->whereType('updated_at', 'string')
                )
            );
    }
}
