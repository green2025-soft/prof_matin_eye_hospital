<?php

namespace Modules\Core\Helpers;

use Illuminate\Support\Number;
use InvalidArgumentException;

final class Money
{
    public static function minor(string|int $amount): int
    {
        if (! preg_match('/^(\d{1,10})(?:\.(\d{1,2}))?$/D', (string) $amount, $parts)) {
            throw new InvalidArgumentException('Use a positive amount with at most two decimal places.');
        }

        return ((int) $parts[1] * 100) + (int) str_pad($parts[2] ?? '', 2, '0');
    }

    public static function decimal(int|string $minor): string
    {
        if (! preg_match('/^-?\d{1,65}$/D', (string) $minor)) {
            throw new InvalidArgumentException('Use a whole amount in minor currency units.');
        }

        $digits = ltrim((string) $minor, '-0');
        $sign = str_starts_with((string) $minor, '-') && $digits !== '' ? '-' : '';
        $digits = str_pad($digits, 3, '0', STR_PAD_LEFT);

        return $sign.substr($digits, 0, -2).'.'.substr($digits, -2);
    }

    public static function words(int $minor): string
    {
        if ($minor < 0) {
            throw new InvalidArgumentException('Use a nonnegative amount in minor currency units.');
        }

        $words = ucfirst(Number::spell(intdiv($minor, 100), locale: 'en')).' taka';
        if ($minor % 100 > 0) {
            $words .= ' and '.Number::spell($minor % 100, locale: 'en').' paisa';
        }

        return $words.' only.';
    }
}
