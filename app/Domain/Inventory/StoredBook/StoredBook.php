<?php

declare(strict_types=1);

namespace App\Domain\Inventory\StoredBook;

use App\Domain\Shared\ValueObject\Id;
use DateTimeImmutable;

final class StoredBook
{
    private readonly DateTimeImmutable $createdAt;

    private DateTimeImmutable $updatedAt;

    public function __construct(
        private readonly Id $bookId,
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

    public function getBookId(): Id
    {
        return $this->bookId;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
