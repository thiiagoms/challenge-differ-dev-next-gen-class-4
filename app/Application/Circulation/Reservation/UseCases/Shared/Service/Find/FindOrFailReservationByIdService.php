<?php

namespace App\Application\Circulation\Reservation\UseCases\Shared\Service\Find;

use App\Application\Circulation\Reservation\Exception\ReservationNotFoundException;
use App\Domain\Circulation\Reservation\Repository\Find\FindReservationByIdRepositoryInterface;
use App\Domain\Circulation\Reservation\Reservation;
use App\Domain\Shared\ValueObject\Id;

class FindOrFailReservationByIdService
{
    public function __construct(private readonly FindReservationByIdRepositoryInterface $repository) {}

    /**
     * @throws ReservationNotFoundException
     */
    public function findOrFail(Id $id): Reservation
    {
        $reservation = $this->repository->find($id);

        if (! empty($reservation)) {
            return $reservation;
        }

        throw ReservationNotFoundException::create();
    }
}
