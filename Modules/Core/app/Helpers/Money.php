<?php

namespace Modules\Core\Helpers;

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
        if (! preg_match('/^\d{1,65}$/D', (string) $minor)) {
            throw new InvalidArgumentException('Use a nonnegative amount in minor currency units.');
        }

        $digits = str_pad(ltrim((string) $minor, '0'), 3, '0', STR_PAD_LEFT);

        return substr($digits, 0, -2).'.'.substr($digits, -2);
    }
}
