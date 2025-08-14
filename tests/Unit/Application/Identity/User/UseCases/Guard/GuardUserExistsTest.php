<?php

namespace Tests\Unit\Application\Identity\User\UseCases\Guard;

use App\Application\Identity\User\Exception\UserNotFoundException;
use App\Application\Identity\User\UseCases\Guard\GuardUserExists;
use App\Domain\Identity\User\Repository\Find\FindUserByIdRepositoryInterface;
use App\Domain\Identity\User\User;
use App\Domain\Identity\User\ValueObject\Email;
use App\Domain\Identity\User\ValueObject\Password;
use App\Domain\Shared\ValueObject\Id;
use App\Domain\Shared\ValueObject\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GuardUserExistsTest extends TestCase
{
    private Id $id;

    private FindUserByIdRepositoryInterface|MockObject $repository;

    private GuardUserExists $guardUserExists;

    protected function setUp(): void
    {
        $this->id = new Id(rand(1, 100));

        $this->repository = $this->createMock(FindUserByIdRepositoryInterface::class);

        $this->guardUserExists = new GuardUserExists(repository: $this->repository);
    }

    /**
     * @throws UserNotFoundException
     */
    #[Test]
    public function itShouldNotThrowExceptionWhenUserExistsInDatabase(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($this->id)
            ->willReturn(
                new User(
                    name: new Str('Test User'),
                    email: new Email(new Str('ilovelaravel@gmail.com')),
                    password: new Password(new Str('password123#ASD_')),
                    id: $this->id
                )
            );

        $this->guardUserExists->verify($this->id);
    }

    #[Test]
    public function itShouldThrowExceptionWhenUserDoesNotExist(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($this->id)
            ->willReturn(null);

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User not found');

        $this->guardUserExists->verify($this->id);
    }
}
