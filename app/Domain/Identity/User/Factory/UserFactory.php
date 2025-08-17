<?php

declare(strict_types=1);

namespace App\Domain\Identity\User\Factory;

use App\Domain\Identity\User\User;
use App\Domain\Identity\User\ValueObject\Email;
use App\Domain\Identity\User\ValueObject\Password;
use App\Domain\Shared\ValueObject\Str;

abstract class UserFactory
{
    public static function build(Str $name, Email $email, Password $password): User
    {
        return new User(
            name: $name,
            email: $email,
            password: $password
        );
    }
}
