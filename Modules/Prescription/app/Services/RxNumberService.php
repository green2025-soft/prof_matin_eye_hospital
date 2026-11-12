<?php

namespace Modules\Prescription\Services;

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Validation\ValidationException;
use Modules\Prescription\Models\GlassPrescription;
use Modules\Prescription\Models\Prescription;

class RxNumberService
{
    public function assign(Prescription|GlassPrescription $record, string $pattern): void
    {
        if (! $record->wasRecentlyCreated || $record->rx_number !== null) {
            return;
        }

        $date = $record->prescription_date;
        $number = strtr($pattern, [
            '{YYYY}'    => $date->format('Y'),
            '{YY}'      => $date->format('y'),
            '{MM}'      => $date->format('m'),
            '{DD}'      => $date->format('d'),
            '{BRANCH}'  => (string) $record->branch_id,
        ]);
        $number = preg_replace_callback('/\{SEQ(?::([1-9]|1[0-2]))?\}/',
            fn (array $token): string => str_pad((string) $record->getKey(), (int) ($token[1] ?? 6), '0', STR_PAD_LEFT),
            $number,
        );

        if (mb_strlen($number) > 80) {
            throw ValidationException::withMessages([
                'record' => 'The generated Rx number is too long. Shorten its pattern in Prescription Settings.',
            ]);
        }

        try {
            $record->rx_number = $number;
            $record->saveQuietly();
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages([
                'record' => 'This Rx number already exists. Change its pattern in Prescription Settings and try again.',
            ]);
        }
    }
}
