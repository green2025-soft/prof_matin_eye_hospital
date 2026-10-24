<?php

namespace Modules\Core\Gateways;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\CheckoutAttempt;
use OpenSSLAsymmetricKey;

final class NagadGateway extends BaseGateway
{
    public function start(CheckoutAttempt $attempt, #[\SensitiveParameter] array $settings, string $callbackUrl): GatewayCheckout
    {
        $this->assertCurrency($attempt);
        $dateTime = now('Asia/Dhaka')->format('YmdHis');
        $response = $this->request($this->headers(), 'POST', $this->baseUrl($settings).'/check-out/initialize/'.rawurlencode($settings['merchant_id']).'/'.rawurlencode($attempt->reference).'?locale=EN', [
            'dateTime' => $dateTime,
            ...$this->signedPayload([
                'merchantId' => $settings['merchant_id'],
                'datetime' => $dateTime,
                'orderId' => $attempt->reference,
                'challenge' => Str::random(40),
            ], $settings),
        ]);
        $initialized = $this->verifiedPayload($response, $settings);
        $reference = $initialized['paymentReferenceId'] ?? null;
        $challenge = $initialized['challenge'] ?? null;

        if (! is_string($reference) || $reference === '' || ! is_string($challenge) || $challenge === '') {
            throw ValidationException::withMessages(['gateway' => 'Nagad returned incomplete payment initialization.']);
        }

        $response = $this->request($this->headers(), 'POST', $this->baseUrl($settings).'/check-out/complete/'.rawurlencode($reference), [
            ...$this->signedPayload([
                'merchantId' => $settings['merchant_id'],
                'orderId' => $attempt->reference,
                'currencyCode' => '050',
                'amount' => Money::decimal($attempt->amount_minor),
                'challenge' => $challenge,
            ], $settings),
            'merchantCallbackURL' => $callbackUrl,
            'additionalMerchantInfo' => ['order_no' => $attempt->reference],
        ]);

        if (($response['status'] ?? null) !== 'Success' || ! is_string($response['callBackUrl'] ?? null)) {
            throw ValidationException::withMessages(['gateway' => 'Nagad could not initialize this payment. Check the merchant configuration.']);
        }

        return new GatewayCheckout($reference, $this->redirectUrl($response['callBackUrl'], ['mynagad.com', 'nagad.com.bd'], (bool) $settings['sandbox']));
    }

    public function verify(CheckoutAttempt $attempt, #[\SensitiveParameter] array $settings, array $callback): GatewayVerification
    {
        $this->assertCurrency($attempt);

        if (! is_string($attempt->provider_reference) || $attempt->provider_reference === '') {
            throw ValidationException::withMessages(['gateway' => 'This checkout has no Nagad payment reference.']);
        }

        $response = $this->request($this->headers(), 'GET', $this->baseUrl($settings).'/verify/payment/'.rawurlencode($attempt->provider_reference));
        $status = $response['status'] ?? null;
        $reference = $response['paymentRefId'] ?? null;
        $merchantReference = $response['orderId'] ?? null;
        $merchantId = $response['merchantId'] ?? null;
        $matchesIdentity = is_string($reference) && hash_equals($attempt->provider_reference, $reference)
            && is_string($merchantReference) && hash_equals($attempt->reference, $merchantReference)
            && is_scalar($merchantId) && (string) $merchantId === (string) $settings['merchant_id'];

        if ($status !== 'Success') {
            $failed = $matchesIdentity && in_array($status, ['Cancelled', 'InvalidRequest', 'Fraud', 'Aborted', 'UnknownFailed'], true);

            return new GatewayVerification($failed ? 'failed' : 'pending');
        }

        $amount = $response['amount'] ?? null;

        if (($response['statusCode'] ?? null) !== '000'
            || ! $matchesIdentity
            || (string) ($response['currencyCode'] ?? '') !== '050' || ! is_scalar($amount)) {
            throw ValidationException::withMessages(['gateway' => 'Nagad returned incomplete or mismatched payment verification.']);
        }

        try {
            $amountMinor = Money::minor((string) $amount);
        } catch (InvalidArgumentException) {
            throw ValidationException::withMessages(['gateway' => 'Nagad returned an invalid payment amount.']);
        }

        if ($amountMinor !== $attempt->amount_minor) {
            throw ValidationException::withMessages(['gateway' => 'The verified Nagad amount does not match this checkout.']);
        }

        return new GatewayVerification('paid', $reference, $amountMinor, 'BDT', $merchantReference);
    }

    private function signedPayload(array $values, #[\SensitiveParameter] array $settings): array
    {
        $plain = json_encode($values, JSON_THROW_ON_ERROR);
        $encrypted = '';
        $signature = '';

        if (! @openssl_public_encrypt($plain, $encrypted, $this->key($settings['public_key'], false), OPENSSL_PKCS1_PADDING)
            || ! @openssl_sign($plain, $signature, $this->key($settings['private_key'], true), OPENSSL_ALGO_SHA256)) {
            throw ValidationException::withMessages(['gateway' => 'Nagad could not sign the payment request. Check the configured keys.']);
        }

        return ['sensitiveData' => base64_encode($encrypted), 'signature' => base64_encode($signature)];
    }

    private function verifiedPayload(array $response, #[\SensitiveParameter] array $settings): array
    {
        $encrypted = is_string($response['sensitiveData'] ?? null) ? base64_decode($response['sensitiveData'], true) : false;
        $signature = is_string($response['signature'] ?? null) ? base64_decode($response['signature'], true) : false;
        $plain = '';

        if ($encrypted === false || $signature === false
            || ! @openssl_private_decrypt($encrypted, $plain, $this->key($settings['private_key'], true), OPENSSL_PKCS1_PADDING)
            || @openssl_verify($plain, $signature, $this->key($settings['public_key'], false), OPENSSL_ALGO_SHA256) !== 1) {
            throw ValidationException::withMessages(['gateway' => 'Nagad payment initialization could not be authenticated.']);
        }

        $values = json_decode($plain, true);

        if (! is_array($values)) {
            throw ValidationException::withMessages(['gateway' => 'Nagad returned invalid payment initialization.']);
        }

        return $values;
    }

    private function key(#[\SensitiveParameter] string $value, bool $private): OpenSSLAsymmetricKey
    {
        if (! str_contains($value, '-----BEGIN ')) {
            $type = $private ? 'RSA PRIVATE KEY' : 'PUBLIC KEY';
            $value = "-----BEGIN {$type}-----\n".chunk_split(preg_replace('/\s+/', '', $value), 64, "\n")."-----END {$type}-----";
        }

        $key = $private ? @openssl_pkey_get_private($value) : @openssl_pkey_get_public($value);

        if ($key === false) {
            throw ValidationException::withMessages(['gateway' => 'The configured Nagad key is invalid.']);
        }

        return $key;
    }

    private function headers(): PendingRequest
    {
        return $this->http()->withHeaders([
            'X-KM-Api-Version' => 'v-0.2.0',
            'X-KM-IP-V4' => request()->ip(),
            'X-KM-Client-Type' => 'PC_WEB',
        ]);
    }

    private function baseUrl(#[\SensitiveParameter] array $settings): string
    {
        // Nagad's published sandbox endpoint uses HTTP; production always uses HTTPS.
        return $settings['sandbox'] ? 'http://sandbox.mynagad.com:10080/remote-payment-gateway-1.0/api/dfs' : 'https://api.mynagad.com/api/dfs';
    }

    private function assertCurrency(CheckoutAttempt $attempt): void
    {
        if ($attempt->currency !== 'BDT') {
            throw ValidationException::withMessages(['gateway' => 'Nagad supports BDT payments only.']);
        }
    }
}
