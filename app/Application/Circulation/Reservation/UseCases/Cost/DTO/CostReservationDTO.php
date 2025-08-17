<?php

namespace App\Application\Circulation\Reservation\UseCases\Cost\DTO;

use App\Domain\Circulation\Reservation\Reservation;
use App\Domain\Shared\ValueObject\Money;

final readonly class CostReservationDTO
{
    public function __construct(
        private Money $reservationCost,
        private Money $costPerDays,
        private int $reservedDays,
        private Reservation $reservation,
    ) {}

    public function getReservationCost(): Money
    {
        return $this->reservationCost;
    }

    public function getCostPerDays(): Money
    {
        return $this->costPerDays;
    }

    public function getReservedDays(): int
    {
        return $this->reservedDays;
    }

    public function getReservation(): Reservation
    {
        return $this->reservation;
    }
}
