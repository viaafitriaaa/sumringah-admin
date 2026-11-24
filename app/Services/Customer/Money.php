<?php

namespace App\Services\Customer;

final class Money
{
    public static function cents(int|float|string $decimal): int
    {
        $text = (string) $decimal;
        [$whole, $fraction] = array_pad(explode('.', ltrim($text, '-'), 2), 2, '');
        $cents = (int) $whole * 100 + (int) substr(str_pad($fraction, 2, '0'), 0, 2);

        return str_starts_with($text, '-') ? -$cents : $cents;
    }

    public static function format(int $cents): string
    {
        return number_format($cents / 100, $cents % 100 === 0 ? 0 : 2, ',', '.');
    }

    public static function taxBps(): int
    {
        return max(0, min(10000, (int) config('customer.tax_bps')));
    }
}
