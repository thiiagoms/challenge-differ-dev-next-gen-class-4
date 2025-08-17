<?php

namespace App\Presentation\Http\Api\V1\Reservation\Shared\Resource;

use App\Domain\Circulation\Reservation\Reservation;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array|\JsonSerializable|Arrayable
    {
        /** @var Reservation $reservation */
        $reservation = $this->resource;

        $data = [
            'id' => $reservation->getId()->getValue(),
            'user_id' => $reservation->getUserId()->getValue(),
            'stored_book_id' => $reservation->getStoredBookId()->getValue(),
            'reserved_at' => $reservation->getReservedAt()->format('Y-m-d H:i:s'),
            'created_at' => $reservation->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $reservation->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];

        if (! empty($reservation->getReturnedAt())) {
            $data['returned_at'] = $reservation->getReturnedAt()->format('Y-m-d H:i:s');
        }

        return $data;
    }
}
