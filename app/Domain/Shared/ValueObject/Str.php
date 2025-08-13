<?php

namespace App\Domain\Shared\ValueObject;

use App\Infrastructure\Support\Sanitizer;

final readonly class Str
{
    private string $value;

    public function __construct(string $value)
    {
        $vaue = Sanitizer::clean($value);

        $this->validate($value);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    private function validate(string $value): void
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Provided value cannot be empty.');
        }
    }
}
