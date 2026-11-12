<?php

namespace Modules\Core\Helpers;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use LogicException;
use Modules\Core\Models\Setting;

class Sms
{
    public function send(string $to, string $message, ?array $configuration = null): Response
    {
        $values = $configuration ?? Setting::values('sms', 'central');

        if (! $values['enabled']) {
            throw new LogicException('SMS delivery is disabled in platform settings.');
        }

        $replacements = [
            '{to}' => $to,
            '{message}' => $message,
            '{sender_id}' => $values['sender_id'],
            '{api_key}' => $values['api_key'],
            '{username}' => $values['username'],
            '{password}' => $values['password'],
        ];
        $headers = $this->parameters($values['headers'] ?: '{}', $replacements);
        $parameters = $this->parameters($values['parameters'], $replacements);
        $request = Http::connectTimeout(3)
            ->timeout(8)
            ->withoutRedirecting()
            ->withHeaders($headers);

        // Never retry a send automatically: the provider may already have accepted it.
        if ($values['method'] === 'GET') {
            return $request->get($values['api_url'], $parameters)->throw();
        }

        if ($values['body_format'] === 'form') {
            $request->asForm();
        }

        // A 2xx response is transport acceptance; callers must interpret the provider's result.
        return $request->post($values['api_url'], $parameters)->throw();
    }

    // GSM extension characters use two septets; Unicode uses UTF-16 code units.
    public function segments(string $message): int
    {
        $basic = <<<'GSM'
@£$¥èéùìòÇØøÅåΔ_ΦΓΛΩΠΨΣΘΞÆæßÉ !"#¤%&'()*+,-./0123456789:;<=>?¡ABCDEFGHIJKLMNOPQRSTUVWXYZÄÖÑÜ§¿abcdefghijklmnopqrstuvwxyzäöñüà
GSM;
        $basic .= "\n\r";
        $extensions = "\f^{}\\[~]|€";
        $gsmWidths = [];
        $unicodeWidths = [];

        foreach (mb_str_split($message) as $character) {
            $unicodeWidths[] = mb_ord($character) > 0xFFFF ? 2 : 1;
            if (str_contains($basic, $character)) {
                $gsmWidths[] = 1;
            } elseif (str_contains($extensions, $character)) {
                $gsmWidths[] = 2;
            } else {
                $gsmWidths[] = null;
            }
        }

        $unicode = in_array(null, $gsmWidths, true);
        $widths = $unicode ? $unicodeWidths : $gsmWidths;
        $singleLimit = $unicode ? 70 : 160;
        $partLimit = $unicode ? 67 : 153;
        if (array_sum($widths) <= $singleLimit) {
            return 1;
        }

        $parts = 1;
        $used = 0;
        // Keep escape sequences and surrogate pairs together at each part boundary.
        foreach ($widths as $width) {
            if ($used + $width > $partLimit) {
                $parts++;
                $used = 0;
            }
            $used += $width;
        }

        return $parts;
    }

    private function parameters(string $template, array $replacements): array
    {
        $parameters = json_decode($template, true, flags: JSON_THROW_ON_ERROR);
        array_walk_recursive($parameters, function (&$value) use ($replacements): void {
            if (is_string($value)) {
                $value = strtr($value, $replacements);
            }
        });

        return $parameters;
    }
}
