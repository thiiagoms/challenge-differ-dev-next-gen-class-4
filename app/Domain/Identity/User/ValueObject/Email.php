<?php

declare(strict_types=1);

namespace App\Domain\Identity\User\ValueObject;

use App\Domain\Shared\ValueObject\Str;

final readonly class Email
{
    private string $email;

    public function __construct(Str $email)
    {
        $this->validate($email->getValue());

        $this->email = $email->getValue();
    }

    public function getValue(): string
    {
        return $this->email;
    }

    private function validate(string $email): void
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new \InvalidArgumentException("Invalid e-mail address given: '{$email}'");
        }
    }
}
