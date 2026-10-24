<?php

namespace Modules\Core\Gateways;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\CheckoutAttempt;

final class BkashGateway extends BaseGateway
{
    public function start(CheckoutAttempt $attempt, #[\SensitiveParameter] array $settings, string $callbackUrl): GatewayCheckout
    {
        $this->assertCurrency($attempt);
        $response = $this->request($this->authorized($settings), 'POST', $this->baseUrl($settings).'/create', [
            'mode' => '0011',
            'payerReference' => (string) $attempt->invoice->tenant_id,
            'callbackURL' => $callbackUrl,
            'amount' => Money::decimal($attempt->amount_minor),
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => $attempt->reference,
        ]);
        $reference = $response['paymentID'] ?? null;
        $url = $response['bkashURL'] ?? null;

        if (($response['statusCode'] ?? null) !== '0000' || ! is_string($reference) || $reference === '' || ! is_string($url) || $url === '') {
            throw ValidationException::withMessages(['gateway' => 'bKash could not initialize this payment. Check the merchant configuration.']);
        }

        return new GatewayCheckout($reference, $this->redirectUrl($url, ['bka.sh', 'bkash.com'], (bool) $settings['sandbox']));
    }

    public function verify(CheckoutAttempt $attempt, #[\SensitiveParameter] array $settings, array $callback): GatewayVerification
    {
        $this->assertCurrency($attempt);

        if (! is_string($attempt->provider_reference) || $attempt->provider_reference === '') {
            throw ValidationException::withMessages(['gateway' => 'This checkout has no bKash payment reference.']);
        }

        $http = $this->authorized($settings);
        $queryUrl = $this->baseUrl($settings).'/payment/status';
        $values = ['paymentID' => $attempt->provider_reference];
        $response = $this->request($http, 'POST', $queryUrl, $values);

        if (in_array($response['transactionStatus'] ?? null, ['Initiated', 'Pending'], true)
            && ($callback['status'] ?? null) === 'success'
            && ($callback['paymentID'] ?? null) === $attempt->provider_reference) {
            try {
                $this->request($http, 'POST', $this->baseUrl($settings).'/execute', $values);
            } catch (ValidationException) {
                // An execute timeout can follow a successful debit. Query before deciding its result.
            }

            $response = $this->request($http, 'POST', $queryUrl, $values);
        }

        $status = $response['transactionStatus'] ?? null;

        if ($status !== 'Completed') {
            $failed = ($response['paymentID'] ?? null) === $attempt->provider_reference
                && in_array($status, ['Failed', 'Cancelled', 'Canceled', 'Expired'], true);

            return new GatewayVerification($failed ? 'failed' : 'pending');
        }

        $reference = $response['trxID'] ?? null;
        $merchantReference = $response['merchantInvoiceNumber'] ?? null;
        $amount = $response['amount'] ?? null;

        if (($response['statusCode'] ?? null) !== '0000'
            || ($response['paymentID'] ?? null) !== $attempt->provider_reference
            || ($response['currency'] ?? null) !== 'BDT'
            || ! is_string($reference) || $reference === '' || ! is_scalar($amount)
            || ! is_string($merchantReference) || ! hash_equals($attempt->reference, $merchantReference)) {
            throw ValidationException::withMessages(['gateway' => 'bKash returned incomplete or mismatched payment verification.']);
        }

        try {
            $amountMinor = Money::minor((string) $amount);
        } catch (InvalidArgumentException) {
            throw ValidationException::withMessages(['gateway' => 'bKash returned an invalid payment amount.']);
        }

        if ($amountMinor !== $attempt->amount_minor) {
            throw ValidationException::withMessages(['gateway' => 'The verified bKash amount does not match this checkout.']);
        }

        return new GatewayVerification('paid', $reference, $amountMinor, 'BDT', $merchantReference);
    }

    private function authorized(#[\SensitiveParameter] array $settings): PendingRequest
    {
        $key = 'gateway:bkash:'.hash('sha256', json_encode(Arr::only($settings, ['sandbox', 'app_key', 'app_secret', 'username', 'password']), JSON_THROW_ON_ERROR));
        $encryptedToken = Cache::get($key);
        $token = null;

        if (is_string($encryptedToken)) {
            try {
                $token = Crypt::decryptString($encryptedToken);
            } catch (DecryptException) {
                Cache::forget($key);
            }
        }

        if (! is_string($token) || $token === '') {
            $response = $this->request($this->http()->withHeaders(['username' => $settings['username'], 'password' => $settings['password']]), 'POST', $this->baseUrl($settings).'/token/grant', [
                'app_key' => $settings['app_key'],
                'app_secret' => $settings['app_secret'],
            ]);
            $token = $response['id_token'] ?? null;

            if (($response['statusCode'] ?? null) !== '0000' || ! is_string($token) || $token === '') {
                throw ValidationException::withMessages(['gateway' => 'bKash merchant authentication failed. Check the configured credentials.']);
            }

            $expiresIn = filter_var($response['expires_in'] ?? 3600, FILTER_VALIDATE_INT);
            $ttl = max(1, min(3600, $expiresIn === false ? 3600 : $expiresIn) - 60);
            Cache::put($key, Crypt::encryptString($token), $ttl);
        }

        return $this->http()->withHeaders(['Authorization' => $token, 'X-APP-Key' => $settings['app_key']]);
    }

    private function baseUrl(#[\SensitiveParameter] array $settings): string
    {
        return ($settings['sandbox'] ? 'https://tokenized.sandbox.bka.sh' : 'https://tokenized.pay.bka.sh').'/v1.2.0-beta/tokenized/checkout';
    }

    private function assertCurrency(CheckoutAttempt $attempt): void
    {
        if ($attempt->currency !== 'BDT') {
            throw ValidationException::withMessages(['gateway' => 'bKash supports BDT payments only.']);
        }
    }
}
