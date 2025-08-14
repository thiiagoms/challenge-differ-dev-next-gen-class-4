<?php

namespace App\Application\Circulation\Reservation\UseCases\Return;

use App\Application\Circulation\Reservation\Exception\ReservationAlreadyBeenReturned;
use App\Application\Circulation\Reservation\Exception\ReservationNotFoundException;
use App\Application\Circulation\Reservation\UseCases\Return\DTO\ReturnReservationDTO;
use App\Application\Circulation\Reservation\UseCases\Shared\Service\Find\FindOrFailReservationByIdService;
use App\Domain\Circulation\Reservation\Exception\ReturnReservationDateIsInvalidException;
use App\Domain\Circulation\Reservation\Reservation;

final readonly class ReturnReservation
{
    public function __construct(private FindOrFailReservationByIdService $findOrFailReservationByIdService) {}

    /**
     * @throws ReturnReservationDateIsInvalidException
     * @throws ReservationNotFoundException
     */
    public function complete(ReturnReservationDTO $dto): void
    {
        $reservation = $this->findOrFailReservationByIdService->findOrFail($dto->getId());

        $reservation->return($dto->getReturnDate());


    }
}
