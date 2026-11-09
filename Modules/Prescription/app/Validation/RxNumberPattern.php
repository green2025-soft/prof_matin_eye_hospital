<?php

namespace Modules\Prescription\Validation;

use Illuminate\Validation\Validator;

class RxNumberPattern
{
    public function validate(string $attribute, mixed $value, array $parameters, Validator $validator): bool
    {
        if (! is_string($value)) {
            return false;
        }

        $sequencePattern = '/\{SEQ(?::(?:[1-9]|1[0-2]))?\}/';

        if (preg_match_all($sequencePattern, $value) !== 1) {
            return false;
        }

        $withoutSequence = preg_replace($sequencePattern, '', $value);
        $withoutTokens = preg_replace('/\{(?:YYYY|YY|MM|DD|BRANCH)\}/', '', $withoutSequence);

        if (str_contains($withoutTokens, '{') || str_contains($withoutTokens, '}')) {
            return false;
        }

        $expanded = preg_replace($sequencePattern, str_repeat('9', 20), $value);
        $expanded = strtr($expanded, [
            '{YYYY}' => '9999',
            '{YY}' => '99',
            '{MM}' => '99',
            '{DD}' => '99',
            '{BRANCH}' => str_repeat('9', 20),
        ]);

        return mb_strlen($expanded) <= 80;
    }
}
