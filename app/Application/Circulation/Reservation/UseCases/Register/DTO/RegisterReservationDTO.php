<?php

declare(strict_types=1);

namespace App\Application\Circulation\Reservation\UseCases\Register\DTO;

use App\Domain\Shared\ValueObject\Id;
use App\Presentation\Http\Api\V1\Circulation\Reservation\Register\Request\RegisterReservationApiRequest;

class RegisterReservationDTO
{
    public function __construct(private Id $userId, private Id $storedBookId) {}

    public function getUserId(): Id
    {
        return $this->userId;
    }

    public function getStoredBookId(): Id
    {
        return $this->storedBookId;
    }

    public static function fromRequest(RegisterReservationApiRequest $request): self
    {
        $payload = $request->validated();

        return new self(
            userId: new Id($payload['user_id']),
            storedBookId: new Id($payload['stored_book_id'])
        );
    }
}
