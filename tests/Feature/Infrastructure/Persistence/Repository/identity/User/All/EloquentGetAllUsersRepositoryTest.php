<?php

namespace Feature\Infrastructure\Persistence\Repository\identity\User\All;

use App\Infrastructure\Persistence\Models\User as LaravelUserModel;
use App\Infrastructure\Persistence\Repository\identity\User\All\EloquentGetAllUsersRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EloquentGetAllUsersRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private EloquentGetAllUsersRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new EloquentGetAllUsersRepository;
    }

    #[Test]
    public function itShouldReturnEmptyWhenThereAreNoUsers(): void
    {
        $users = $this->repository->all();

        $this->assertEmpty($users);
    }

    #[Test]
    public function itShouldReturnAllUsers(): void
    {
        LaravelUserModel::factory(10)->create();

        $users = $this->repository->all();

        $this->assertNotEmpty($users);
    }
}
