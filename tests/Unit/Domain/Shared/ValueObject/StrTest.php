<?php

namespace Tests\Unit\Domain\Common\ValueObject;

use App\Domain\Shared\ValueObject\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class StrTest extends TestCase
{
    #[Test]
    public function itShouldCreateStrTypeValueObject(): void
    {
        $value = 'Hello World';
        $str = new Str($value);

        $this->assertEquals($value, $str->getValue());
    }

    #[Test]
    public function itShouldThrowExceptionWhenValueIsEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Provided value cannot be empty.');

        new Str('');
    }
}
