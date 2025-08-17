<?php

namespace Tests\Unit\Domain\Identity\User\ValueObject;

use App\Domain\Identity\User\ValueObject\Email;
use App\Domain\Shared\ValueObject\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    #[Test]
    public function itShouldCreateAnEmail(): void
    {
        $email = new Email(new Str('ilovephp@gmail.com'));

        $this->assertEquals('ilovephp@gmail.com', $email->getValue());
    }

    public static function invalidEmailCases(): array
    {
        return [
            'should throw exception when provided email is not a valid email address' => [
                new Str('invalid-email'),
            ],
        ];
    }

    #[Test]
    #[DataProvider('invalidEmailCases')]
    public function itShouldThrowExceptionWhenEmailIsInvalid(Str $invalidEmail): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid e-mail address given: '{$invalidEmail->getValue()}'");

        new Email($invalidEmail);
    }
}
