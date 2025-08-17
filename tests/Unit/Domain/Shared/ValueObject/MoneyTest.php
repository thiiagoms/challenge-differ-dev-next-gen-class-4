<?php

namespace Tests\Unit\Domain\Shared\ValueObject;

use App\Domain\Shared\ValueObject\Currency;
use App\Domain\Shared\ValueObject\Money;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    #[Test]
    public function itShouldCreatesMoneyFromDecimalAndKeepsCurrency(): void
    {
        $money = new Money(amount: 4.50, currency: Currency::from('BRL'));

        $this->assertSame(4.50, $money->getAmount());
        $this->assertSame('BRL', $money->getCurrency()->value);
    }

    #[Test]
    public function itShouldMultiplyByIntegerFactor(): void
    {
        $money = new Money(amount: 4.50, currency: Currency::from('BRL'));

        $total = $money->multiply(3);

        $this->assertSame(13.5, $total->getAmount());
        $this->assertSame('BRL', $total->getCurrency()->value);
    }
}
