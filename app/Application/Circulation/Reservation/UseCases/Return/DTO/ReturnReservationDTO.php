<?php

namespace App\Application\Circulation\Reservation\UseCases\Return\DTO;

use App\Domain\Shared\ValueObject\Id;
use App\Presentation\Http\Api\V1\Circulation\Reservation\Return\Request\ReturnReservationApiRequest;
use DateTimeImmutable;

final readonly class ReturnReservationDTO
{
    public function __construct(private Id $id, private \DateTimeImmutable $returnDate) {}

    public function getId(): Id
    {
        return $this->id;
    }

    public function getReturnDate(): \DateTimeImmutable
    {
        return $this->returnDate;
    }

    public static function fromRequest(ReturnReservationApiRequest $request): self
    {
        $payload = $request->validated();

        return new self(
            id: new Id($payload['reservation_id']),
            returnDate: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $payload['return_date'])
        );
    }
}
