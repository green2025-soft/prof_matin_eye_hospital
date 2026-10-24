<?php

namespace Modules\Core\Gateways;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Modules\Core\Contracts\PaymentGateway;

abstract class BaseGateway implements PaymentGateway
{
    protected function http(): PendingRequest
    {
        return Http::acceptJson()->connectTimeout(10)->timeout(30)->withoutRedirecting();
    }

    protected function request(#[\SensitiveParameter] PendingRequest $http, string $method, string $url, #[\SensitiveParameter] array $values = []): array
    {
        try {
            $response = $method === 'GET' ? $http->get($url, $values) : $http->post($url, $values);
        } catch (ConnectionException) {
            throw ValidationException::withMessages(['gateway' => 'The payment provider is unavailable. Please try again.']);
        }

        return $this->responseData($response);
    }

    protected function responseData(#[\SensitiveParameter] Response $response): array
    {
        $data = $response->json();

        if (! $response->successful() || ! is_array($data)) {
            throw ValidationException::withMessages(['gateway' => 'The payment provider could not process the request. Please try again.']);
        }

        return $data;
    }

    protected function redirectUrl(string $url, array $domains, bool $sandbox = false): string
    {
        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');
        $validHost = false;

        foreach ($domains as $domain) {
            if ($host === $domain || str_ends_with($host, '.'.$domain)) {
                $validHost = true;
                break;
            }
        }

        if (! $validHost || isset($parts['user']) || isset($parts['pass'])
            || ! in_array($parts['scheme'] ?? '', $sandbox ? ['https', 'http'] : ['https'], true)) {
            throw ValidationException::withMessages(['gateway' => 'The payment provider returned an invalid checkout address.']);
        }

        return $url;
    }
}
