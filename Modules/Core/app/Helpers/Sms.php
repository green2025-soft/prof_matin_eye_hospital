<?php

namespace Modules\Core\Helpers;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use LogicException;
use Modules\Core\Models\Setting;

class Sms
{
    public function send(string $to, string $message): Response
    {
        $values = Setting::values('sms', 'central');

        if (! $values['enabled']) {
            throw new LogicException('SMS delivery is disabled in platform settings.');
        }

        $replacements = [
            '{to}' => $to, '{message}' => $message, '{sender_id}' => $values['sender_id'],
            '{api_key}' => $values['api_key'], '{username}' => $values['username'], '{password}' => $values['password'],
        ];
        $headers = $this->parameters($values['headers'] ?: '{}', $replacements);
        $parameters = $this->parameters($values['parameters'], $replacements);
        $request = Http::connectTimeout(5)->timeout(20)->withoutRedirecting()->withHeaders($headers);

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
