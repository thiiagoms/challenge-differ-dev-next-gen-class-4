<?php

namespace App\Presentation\Http\Api\V1\Reservation\Cost\Resource;

use App\Application\Circulation\Reservation\UseCases\Cost\DTO\CostReservationDTO;
use App\Presentation\Http\Api\V1\Reservation\Shared\Resource\ReservationResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationCostResource extends JsonResource
{
    public function toArray(Request $request): array|\JsonSerializable|Arrayable
    {
        /** @var CostReservationDTO $cost */
        $cost = $this->resource;

        return [
            'reservation_cost' => $cost->getReservationCost()->getFormattedAmount(),
            'cost_per_day' => $cost->getCostPerDays()->getFormattedAmount(),
            'reservedDays' => $cost->getReservedDays(),
            'reservation' => ReservationResource::make($cost->getReservation()),
        ];
    }
}
