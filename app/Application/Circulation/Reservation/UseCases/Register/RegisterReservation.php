<?php

declare(strict_types=1);

namespace App\Application\Circulation\Reservation\UseCases\Register;

use App\Application\Circulation\Reservation\UseCases\Register\DTO\RegisterReservationDTO;
use App\Application\Identity\User\Exception\UserNotFoundException;
use App\Application\Identity\User\UseCases\Guard\GuardUserExists;
use App\Application\Inventory\StoredBook\Exception\StoredBookNotFoundException;
use App\Application\Inventory\StoredBook\UseCases\Guard\GuardBookIsAvailableInStore;
use App\Domain\Circulation\Reservation\Factory\ReservationFactoryInterface;
use App\Domain\Circulation\Reservation\Repository\Register\RegisterReservationRepositoryInterface;
use App\Domain\Circulation\Reservation\Reservation;

readonly class RegisterReservation
{
    public function __construct(
        private GuardUserExists $guardAgainstUserExists,
        private GuardBookIsAvailableInStore $guardAgainBookIsAvailableInStored,
        private ReservationFactoryInterface $factory,
        private RegisterReservationRepositoryInterface $repository
    ) {}

    /**
     * @throws UserNotFoundException
     * @throws StoredBookNotFoundException
     */
    public function reserve(RegisterReservationDTO $dto): Reservation
    {
        $this->guardAgainstUserExists->verify($dto->getUserId());
        $this->guardAgainBookIsAvailableInStored->verify($dto->getStoredBookId());

        $reservation = $this->factory->build(userId: $dto->getUserId(), storedBookId: $dto->getStoredBookId());

        return $this->repository->save($reservation);
    }
}
