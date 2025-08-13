<?php

declare(strict_types=1);

namespace App\Domain\Circulation\Reservation;

use App\Domain\Circulation\Reservation\Status\Interface\StatusInterface;
use App\Domain\Circulation\Reservation\Status\Status;
use App\Domain\Shared\ValueObject\Id;
use DateTimeImmutable;

final class Reservation
{
    private readonly DateTimeImmutable $createdAt;

    private DateTimeImmutable $updatedAt;

    public function __construct(
        private readonly Id $userId,
        private readonly Id $storedBookId,
        private StatusInterface $status,
        private readonly DateTimeImmutable $reservedAt,
        private ?DateTimeImmutable $returnedAt = null,
        private readonly ?Id $id = null,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
    ) {
        $now = new DateTimeImmutable;

        $this->createdAt = $createdAt ?? $now;
        $this->updatedAt = $updatedAt ?? $now;
    }

    public function getId(): ?Id
    {
        return $this->id;
    }

    public function getUserId(): Id
    {
        return $this->userId;
    }

    public function getStoredBookId(): Id
    {
        return $this->storedBookId;
    }

    public function getStatus(): Status
    {
        return $this->status->getStatus();
    }

    public function getReservedAt(): DateTimeImmutable
    {
        return $this->reservedAt;
    }

    public function getReturnedAt(): ?DateTimeImmutable
    {
        return $this->returnedAt;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function return(): void
    {
        $this->status->returned($this);
        $this->returnedAt = new DateTimeImmutable;
        $this->touch();
    }

    /**
     * @internal
     */
    public function changeStatusTo(StatusInterface $status): void
    {
        $this->status = $status;
        $this->touch();
    }

    private function touch(): void
    {
        $this->updatedAt = new DateTimeImmutable;
    }
}
