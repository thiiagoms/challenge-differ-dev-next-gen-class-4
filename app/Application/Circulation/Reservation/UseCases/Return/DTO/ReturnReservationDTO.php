<?php

namespace App\Application\Circulation\Reservation\UseCases\Return\DTO;

use App\Domain\Shared\ValueObject\Id;

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
}
