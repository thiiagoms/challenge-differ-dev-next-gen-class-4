<?php

namespace Tests\Feature\Infrastructure\Persistence\Repository\identity\User\Find;

use App\Domain\Identity\User\User as UserEntity;
use App\Domain\Shared\ValueObject\Id;
use App\Infrastructure\Persistence\Models\User as LaravelUserModel;
use App\Infrastructure\Persistence\Repository\identity\User\Find\EloquentFindUserByIdRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EloquentFindUserByIdRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function itShouldReturnUserWhenIdExistsInDatabase(): void
    {
        $id = new Id(id: rand(900, 1001));

        LaravelUserModel::factory()->create(['id' => $id->getValue()]);

        $repository = new EloquentFindUserByIdRepository;

        /** @var UserEntity $user */
        $user = $repository->find($id);

        $this->assertEquals($id->getValue(), $user->getId()->getValue());
    }

    #[Test]
    public function itShouldReturnNullWhenIdDoesNotExistInDatabase(): void
    {
        $id = new Id(id: rand(900, 1001));

        $repository = new EloquentFindUserByIdRepository;

        /** @var UserEntity $user */
        $user = $repository->find($id);

        $this->assertNull($user);
    }
}
