<?php

namespace Tests\Unit\Domain\Identity\User;

use App\Domain\Identity\User\Factory\UserFactory;
use App\Domain\Identity\User\ValueObject\Email;
use App\Domain\Identity\User\ValueObject\Password;
use App\Domain\Shared\ValueObject\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    #[Test]
    public function itShouldCreateUser(): void
    {
        $user = UserFactory::build(
            name: new Str('John Doe'),
            email: new Email(new Str('ilovelaravel@gmail.com')),
            password: new Password(password: new Str('ValidPassword1!@'))
        );

        $this->assertEquals('John Doe', $user->getName()->getValue());
        $this->assertEquals('ilovelaravel@gmail.com', $user->getEmail()->getValue());
        $this->assertTrue($user->getPassword()->match(new Str('ValidPassword1!@')));

        $this->assertNull($user->getId());
        $this->assertNull($user->getEmailConfirmedAt());
    }

    #[Test]
    public function itShouldAllowUserToChangeName(): void
    {
        $user = UserFactory::build(
            name: new Str('John Doe'),
            email: new Email(new Str('ilovelaravel@gmail.com')),
            password: new Password(password: new Str('ValidPassword1!@'))
        );

        $user->changeNameTo(new Str('Doe John'));

        $this->assertNotEquals('John Doe', $user->getName()->getValue());
        $this->assertEquals('Doe John', $user->getName()->getValue());
    }

    #[Test]
    public function itShouldAllowUserToChangeEmail(): void
    {
        $user = UserFactory::build(
            name: new Str('John Doe'),
            email: new Email(new Str('ilovelaravel@gmail.com')),
            password: new Password(password: new Str('ValidPassword1!@'))
        );

        $user->changeEmailTo(
            new Email(
                new Str('ilovephp@gmail.com')
            )
        );

        $this->assertNotEquals('ilovelaravel@gmail.com', $user->getEmail()->getValue());
        $this->assertEquals('ilovephp@gmail.com', $user->getEmail()->getValue());
    }

    #[Test]
    public function itShouldAllowUserToChangePassword(): void
    {
        $user = UserFactory::build(
            name: new Str('John Doe'),
            email: new Email(new Str('ilovelaravel@gmail.com')),
            password: new Password(password: new Str('ValidPassword1!@'))
        );

        $user->changePasswordTo(
            new Password(
                password: new Str('CHzEe4scoy@ASD_')
            )
        );

        $this->assertFalse($user->getPassword()->match(new Str('ValidPassword1!@')));
        $this->assertTrue($user->getPassword()->match(new Str('CHzEe4scoy@ASD_')));
    }
}
