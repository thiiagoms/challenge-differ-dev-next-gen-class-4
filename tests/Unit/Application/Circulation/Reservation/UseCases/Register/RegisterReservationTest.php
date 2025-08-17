<?php

namespace Tests\Unit\Application\Circulation\Reservation\UseCases\Register;

use App\Application\Circulation\Reservation\UseCases\Register\DTO\RegisterReservationDTO;
use App\Application\Circulation\Reservation\UseCases\Register\RegisterReservation;
use App\Application\Identity\User\Exception\UserNotFoundException;
use App\Application\Identity\User\UseCases\Guard\GuardUserExists;
use App\Application\Inventory\StoredBook\Exception\StoredBookNotFoundException;
use App\Application\Inventory\StoredBook\UseCases\Guard\GuardBookIsAvailableInStore;
use App\Domain\Circulation\Reservation\Factory\ReservationFactoryInterface;
use App\Domain\Circulation\Reservation\Repository\Register\RegisterReservationRepositoryInterface;
use App\Domain\Circulation\Reservation\Reservation;
use App\Domain\Circulation\Reservation\Status\Implementation\Pending;
use App\Domain\Circulation\Reservation\Status\Status;
use App\Domain\Shared\ValueObject\Id;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RegisterReservationTest extends TestCase
{
    private RegisterReservationDTO $dto;

    private GuardUserExists|MockObject $guardAgainUserExists;

    private GuardBookIsAvailableInStore|MockObject $guardBookIsAvailableInStore;

    private RegisterReservationRepositoryInterface $repository;

    private ReservationFactoryInterface $factory;

    private RegisterReservation $registerReservation;

    protected function setUp(): void
    {
        $this->dto = new RegisterReservationDTO(
            userId: new Id(rand(1, 100)),
            storedBookId: new Id(rand(101, 200))
        );

        $this->guardAgainUserExists = $this->createMock(GuardUserExists::class);

        $this->guardBookIsAvailableInStore = $this->createMock(GuardBookIsAvailableInStore::class);

        $this->repository = $this->createMock(RegisterReservationRepositoryInterface::class);

        $this->factory = $this->createMock(ReservationFactoryInterface::class);

        $this->registerReservation = new RegisterReservation(
            guardAgainstUserExists: $this->guardAgainUserExists,
            guardAgainBookIsAvailableInStored: $this->guardBookIsAvailableInStore,
            factory: $this->factory,
            repository: $this->repository
        );
    }

    /**
     * @throws UserNotFoundException
     * @throws StoredBookNotFoundException
     */
    #[Test]
    public function itShouldCreateNewReservation(): void
    {
        $this->guardAgainUserExists
            ->expects($this->once())
            ->method('verify')
            ->with($this->dto->getUserId());

        $this->guardBookIsAvailableInStore
            ->expects($this->once())
            ->method('verify')
            ->with($this->dto->getStoredBookId());

        $reservation = new Reservation(
            userId: $this->dto->getUserId(),
            storedBookId: $this->dto->getStoredBookId(),
            status: new Pending,
            reservedAt: new \DateTimeImmutable
        );

        $this->factory
            ->expects($this->once())
            ->method('build')
            ->with($this->dto->getUserId(), $this->dto->getStoredBookId())
            ->willReturn($reservation);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($reservation)
            ->willReturn(
                new Reservation(
                    userId: $this->dto->getUserId(),
                    storedBookId: $this->dto->getStoredBookId(),
                    status: new Pending,
                    reservedAt: $reservation->getReservedAt(),
                    id: new Id(100),
                    createdAt: $reservation->getCreatedAt(),
                    updatedAt: $reservation->getUpdatedAt()
                )
            );

        $result = $this->registerReservation->reserve($this->dto);

        $this->assertEquals($this->dto->getUserId()->getValue(), $result->getUserId()->getValue());
        $this->assertEquals($this->dto->getStoredBookId()->getValue(), $result->getStoredBookId()->getValue());
        $this->assertEquals(Status::PENDING, $result->getStatus());

        $this->assertNotNull($result->getId());
        $this->assertNull($result->getReturnedAt());
    }

    /**
     * @throws StoredBookNotFoundException
     */
    #[Test]
    public function itShouldThrowExceptionWhenUserNotFound(): void
    {
        $this->guardAgainUserExists
            ->expects($this->once())
            ->method('verify')
            ->with($this->dto->getUserId())
            ->willThrowException(UserNotFoundException::create());

        $this->guardBookIsAvailableInStore
            ->expects($this->never())
            ->method('verify');

        $this->factory
            ->expects($this->never())
            ->method('build');

        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User not found');

        $this->registerReservation->reserve($this->dto);
    }

    /**
     * @throws UserNotFoundException
     */
    #[Test]
    public function itShouldThrowExceptionWhenStoredBookNotFound(): void
    {
        $this->guardAgainUserExists
            ->expects($this->once())
            ->method('verify')
            ->with($this->dto->getUserId());

        $this->guardBookIsAvailableInStore
            ->expects($this->once())
            ->method('verify')
            ->with($this->dto->getStoredBookId())
            ->willThrowException(StoredBookNotFoundException::create());

        $this->factory
            ->expects($this->never())
            ->method('build');

        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(StoredBookNotFoundException::class);
        $this->expectExceptionMessage('Stored book not found');

        $this->registerReservation->reserve($this->dto);
    }
}
