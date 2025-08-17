<?php

namespace App\Domain\Shared\ValueObject;

enum Currency: string
{
    case BRL = 'BRL';

    public function format(Money $value, Currency $currency): string
    {
        return match ($currency) {
            Currency::BRL => $this->formatPtBr($value),
            default => throw new \InvalidArgumentException("Unsupported currency: '{$currency->value}'"),
        };
    }

    private function formatPtBr(Money $value): string
    {
        $formattedValue = number_format(
            num: $value->getAmount(),
            decimals: 2,
            decimal_separator: ',',
            thousands_separator: '.'
        );

        return "R$ {$formattedValue}";
    }
}
