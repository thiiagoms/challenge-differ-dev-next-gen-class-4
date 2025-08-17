<?php

namespace Tests\Unit\Domain\Identity\User\ValueObject;

use App\Domain\Identity\User\ValueObject\Password;
use App\Domain\Shared\ValueObject\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    #[Test]
    public function itShouldCreatePasswordObject(): void
    {
        $password = new Password(password: new Str('ValidPassword1!'));

        $this->assertIsString($password->getValue());
        $this->assertNotEquals('ValidPassword1!', $password->getValue());
    }

    #[Test]
    public function itShouldCreatePasswordWithRawText(): void
    {
        $password = new Password(password: new Str('ValidPassword1!'), hashed: false);

        $this->assertSame('ValidPassword1!', $password->getValue());
        $this->assertFalse($password->match(new Str('ValidPassword1!')));
    }

    #[Test]
    public function itShouldReturnFalseWhenProvidedPasswordDoesNotMatch(): void
    {
        $password = new Password(password: new Str('ValidPassword1!'));

        $this->assertFalse($password->match(new Str('InvalidPassword1!')));
    }
}
