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
        $this->assertTrue($user->getPassword()->match(new Str('ValidPassword1!')));

        $this->assertNull($user->getId());
        $this->assertNull($user->getEmailConfirmedAt());
    }

    #[Test]
    public function itShouldAllowUserToChangeName(): void {}

    public function itShouldAllowUserToChangeEmail(): void {}

    public function itShouldAllowUserToChangePassword(): void {}
}
