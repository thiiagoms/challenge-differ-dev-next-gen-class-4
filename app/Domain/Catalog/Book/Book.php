<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Book;

use App\Domain\Shared\ValueObject\Id;
use App\Domain\Shared\ValueObject\Str;
use DateTimeImmutable;

final class Book
{
    private readonly DateTimeImmutable $createdAt;

    private DateTimeImmutable $updatedAt;

    public function __construct(
        private readonly Str $title,
        private readonly Str $author,
        private readonly ?Str $isbn = null,
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

    public function getTitle(): Str
    {
        return $this->title;
    }

    public function getAuthor(): Str
    {
        return $this->author;
    }

    public function getIsbn(): ?Str
    {
        return $this->isbn;
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
