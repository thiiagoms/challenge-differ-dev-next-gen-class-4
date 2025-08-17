<?php

declare(strict_types=1);

namespace App\Domain\Identity\User\ValueObject;

use App\Domain\Shared\ValueObject\Str;

final readonly class Password
{
    private string $password;

    public function __construct(Str $password, bool $hashed = true)
    {
        $hashed === true
            ? $this->password = $this->hash($password->getValue())
            : $this->password = $password->getValue();
    }

    public function getValue(): string
    {
        return $this->password;
    }

    public function match(Str $passwordAsPlain): bool
    {
        return password_verify($passwordAsPlain->getValue(), $this->password);
    }

    private function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
