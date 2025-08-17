<?php

namespace Tests\Unit\Domain\Shared\ValueObject;

use App\Domain\Shared\ValueObject\Id;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class IdTest extends TestCase
{
    #[Test]
    public function itShouldCreateAnId(): void
    {
        $id = new Id(1000);

        $this->assertEquals(1000, $id->getValue());
    }

    public static function invalidIdCases(): array
    {
        return [
            'should throw exception when provided id is zero' => [
                0,
            ],
            'should throw exception when provided id is less than zero' => [
                -1,
            ],
        ];
    }

    #[Test]
    #[DataProvider('invalidIdCases')]
    public function itShouldThrowExceptionWhenIdIsInvalid(int $id): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid id provided: '{$id}'");

        new Id($id);
    }
}
