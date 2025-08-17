<?php

namespace Tests\Unit\Application\Identity\User\UseCases\All;

use App\Application\Identity\User\UseCases\All\ReturnAllUsers;
use App\Domain\Identity\User\Repository\All\ReturnAllUsersRepositoryInterface;
use App\Domain\Identity\User\User;
use App\Domain\Identity\User\ValueObject\Email;
use App\Domain\Identity\User\ValueObject\Password;
use App\Domain\Shared\ValueObject\Id;
use App\Domain\Shared\ValueObject\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ReturnAllUsersTest extends TestCase
{
    private ReturnAllUsersRepositoryInterface|MockObject $repository;

    private ReturnAllUsers $action;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ReturnAllUsersRepositoryInterface::class);

        $this->action = new ReturnAllUsers($this->repository);
    }

    #[Test]
    public function itShouldReturnAllUsers(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('all')
            ->willReturn([
                [
                    new User(
                        name: new Str(fake()->name()),
                        email: new Email(new Str(fake()->freeEmail())),
                        password: new Password(password: new Str(fake()->password())),
                        id: new Id(rand(1, 10000))
                    ),
                ],
                [
                    new User(
                        name: new Str(fake()->name()),
                        email: new Email(new Str(fake()->freeEmail())),
                        password: new Password(password: new Str(fake()->password())),
                        id: new Id(rand(1, 10000))
                    ),
                ],
                [
                    new User(
                        name: new Str(fake()->name()),
                        email: new Email(new Str(fake()->freeEmail())),
                        password: new Password(password: new Str(fake()->password())),
                        id: new Id(rand(1, 10000))
                    ),
                ],
            ]);

        $users = $this->action->all();

        $this->assertNotEmpty($users);
    }

    #[Test]
    public function itShouldReturnEmptyWhereThereIsNoUser(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('all')
            ->willReturn([]);

        $users = $this->action->all();

        $this->assertEmpty($users);
    }
}
