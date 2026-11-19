<?php

namespace Modules\Core\Helpers;

use Illuminate\Support\Number;
use InvalidArgumentException;

final class Money
{
    public static function minor(string|int $amount): int
    {
        // Accept native decimal input without converting money through floating point.
        $amount = trim((string) $amount);
        if (! preg_match('/^([+-]?)(\d*)(?:\.(\d{0,2}))?$/D', $amount, $parts)
            || ($parts[2] === '' && ($parts[3] ?? '') === '')
            || strlen(ltrim($parts[2], '0')) > 10) {
            throw new InvalidArgumentException('Use a positive amount with at most two decimal places.');
        }

        $minor = ((int) $parts[2] * 100) + (int) str_pad($parts[3] ?? '', 2, '0');
        if ($parts[1] === '-' && $minor !== 0) {
            throw new InvalidArgumentException('Use a positive amount with at most two decimal places.');
        }

        return $minor;
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

    public static function words(int|float  $amount ): string
    {
        if ($amount  < 0) {
            throw new InvalidArgumentException('Use a nonnegative amount in minor currency units.');
        }

        $taka = (int) floor($amount);
        $paisa = (int) round(($amount - $taka) * 100);
        $words = ucfirst(Number::spell($taka, locale: 'en')) . ' taka';

        if ($paisa > 0) {
            $words .= ' and ' . Number::spell($paisa, locale: 'en') . ' paisa';
    }

        return $words.' only.';
    }
}
