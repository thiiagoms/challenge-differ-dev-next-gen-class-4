<?php

declare(strict_types=1);

namespace App\Application\Circulation\Reservation\UseCases\Cost;

use App\Application\Circulation\Reservation\Exception\ReservationNotFoundException;
use App\Application\Circulation\Reservation\UseCases\Cost\DTO\CostReservationDTO;
use App\Application\Circulation\Reservation\UseCases\Shared\Const\Rate;
use App\Application\Circulation\Reservation\UseCases\Shared\Service\Find\FindOrFailReservationByIdService;
use App\Domain\Shared\ValueObject\Currency;
use App\Domain\Shared\ValueObject\Id;
use App\Domain\Shared\ValueObject\Money;

final readonly class GetReservationCost
{
    public function __construct(private FindOrFailReservationByIdService $findOrFailReservationByIdService) {}

    /**
     * @throws ReservationNotFoundException
     */
    public function cost(Id $reservationId): CostReservationDTO
    {
        $reservation = $this->findOrFailReservationByIdService->findOrFail($reservationId);

        $tax = new Money(amount: Rate::DAILY_RATE_TAX, currency: Currency::BRL);

        $reservedDays = $reservation->getReservedDays();

        $dailyRate = $reservation->calculateDailyRate(tax: $tax);

        return new CostReservationDTO(
            reservationCost: $dailyRate,
            costPerDays: $tax,
            reservedDays: $reservedDays,
            reservation: $reservation
        );
    }
}
