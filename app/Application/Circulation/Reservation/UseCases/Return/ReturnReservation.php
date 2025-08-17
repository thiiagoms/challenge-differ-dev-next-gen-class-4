<?php

namespace App\Application\Circulation\Reservation\UseCases\Return;

use App\Application\Circulation\Reservation\Exception\ReservationNotFoundException;
use App\Application\Circulation\Reservation\UseCases\Return\DTO\ReturnReservationDTO;
use App\Application\Circulation\Reservation\UseCases\Shared\Service\Find\FindOrFailReservationByIdService;
use App\Domain\Circulation\Reservation\Exception\ReturnReservationDateIsInvalidException;
use App\Domain\Circulation\Reservation\Repository\Update\UpdateReservationRepositoryInterface;
use App\Domain\Circulation\Reservation\Reservation;
use App\Domain\Circulation\Reservation\Status\Exception\InvalidReservationStatusTransitionException;
use Exception;

final readonly class ReturnReservation
{
    public function __construct(
        private FindOrFailReservationByIdService $findOrFailReservationByIdService,
        private UpdateReservationRepositoryInterface $repository
    ) {}

    /**
     * @throws ReturnReservationDateIsInvalidException
     * @throws ReservationNotFoundException
     * @throws InvalidReservationStatusTransitionException
     * @throws Exception
     */
    public function complete(ReturnReservationDTO $dto): Reservation
    {
        $reservation = $this->findOrFailReservationByIdService->findOrFail($dto->getId());

        $reservation->return($dto->getReturnDate());

        $this->repository->update($reservation);

        return $reservation;
    }
}
