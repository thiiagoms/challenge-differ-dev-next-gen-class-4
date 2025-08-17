<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

final readonly class Id
{
    public function __construct(private int $id)
    {
        $this->validate($id);
    }

    public function getValue(): int
    {
        return $this->id;
    }

    private function validate(int $id): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException("Invalid id provided: '{$id}'");
        }
    }
}
