<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

final readonly class Money
{
    private const int CENTS_PER_UNIT = 100;

    public function __construct(private float|int $amount, private Currency $currency)
    {
        $this->validate($this->amount);
    }

    public function getAmount(): int|float
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getFormattedAmount(): string
    {
        return $this->currency->format($this, $this->currency);
    }

    public function multiply(int $factor): Money
    {
        if ($factor < 0) {
            throw new \InvalidArgumentException('Factor cannot be negative');
        }

        return new self($this->amount * $factor, $this->currency);
    }

    private function validate(float $amount): void
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Amount cannot be negative');
        }
    }
}
