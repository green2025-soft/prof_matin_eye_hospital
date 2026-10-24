<?php

namespace Modules\Core\Gateways;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\CheckoutAttempt;

final class EpsGateway extends BaseGateway
{
    public function start(CheckoutAttempt $attempt, #[\SensitiveParameter] array $settings, string $callbackUrl): GatewayCheckout
    {
        $this->assertCurrency($attempt);
        $customer = $attempt->invoice->customer_snapshot ?? [];
        $response = $this->request($this->authorized($settings, $attempt->reference), 'POST', $this->baseUrl($settings).'/v1/EPSEngine/InitializeEPS', [
            'CustomerOrderId' => $attempt->reference,
            'merchantId' => $settings['merchant_id'],
            'storeId' => $settings['store_id'],
            'merchantTransactionId' => $attempt->reference,
            'deviceTypeId' => 4,
            'transactionTypeId' => 1,
            'financialEntityId' => 0,
            'totalAmount' => Money::decimal($attempt->amount_minor),
            'version' => '1',
            'transactionDate' => now('Asia/Dhaka')->toIso8601String(),
            'transitionStatusId' => 0,
            'successUrl' => $callbackUrl,
            'failUrl' => $callbackUrl,
            'cancelUrl' => $callbackUrl,
            'ipAddress' => request()->ip(),
            'customerName' => $customer['name'] ?? '',
            'customerEmail' => $customer['email'] ?? '',
            'customerPhone' => $customer['phone'] ?? '',
            'customerAddress' => $customer['address'] ?? '',
            'customerCity' => $customer['city'] ?? '',
            'customerState' => $customer['state'] ?? '',
            'customerPostcode' => $customer['postcode'] ?? '',
            'customerCountry' => 'BD',
            'productName' => 'Invoice '.$attempt->invoice->number,
            'productProfile' => 'Invoice payment',
            'productCategory' => 'Services',
            'noOfItem' => '1',
        ]);

        if (! is_string($response['RedirectURL'] ?? null) || $response['RedirectURL'] === '') {
            throw ValidationException::withMessages(['gateway' => 'EPS could not initialize this payment. Check the merchant configuration and customer billing details.']);
        }

        // EPS verifies the merchant order ID; its final EpsTransactionId differs from the initialization UUID.
        return new GatewayCheckout($attempt->reference, $this->redirectUrl($response['RedirectURL'], ['eps.com.bd'], (bool) $settings['sandbox']));
    }

    public function verify(CheckoutAttempt $attempt, #[\SensitiveParameter] array $settings, array $callback): GatewayVerification
    {
        $this->assertCurrency($attempt);
        $response = $this->request($this->authorized($settings, $attempt->reference), 'GET', $this->baseUrl($settings).'/v1/EPSEngine/CheckMerchantTransactionStatus', [
            'merchantTransactionId' => $attempt->reference,
        ]);
        $status = is_string($response['Status'] ?? null) ? strtolower($response['Status']) : '';
        $merchantReference = $response['MerchantTransactionId'] ?? null;
        $matchesIdentity = is_string($merchantReference) && hash_equals($attempt->reference, $merchantReference);

        if ($status !== 'success') {
            $failed = $matchesIdentity && in_array($status, ['failed', 'cancelled', 'canceled'], true);

            return new GatewayVerification($failed ? 'failed' : 'pending');
        }

        $reference = $response['EpsTransactionId'] ?? null;
        $amount = $response['TotalAmount'] ?? null;

        if (! $matchesIdentity
            || ! is_string($reference) || $reference === '' || ! is_scalar($amount)) {
            throw ValidationException::withMessages(['gateway' => 'EPS returned incomplete or mismatched payment verification.']);
        }

        try {
            $amountMinor = Money::minor((string) $amount);
        } catch (InvalidArgumentException) {
            throw ValidationException::withMessages(['gateway' => 'EPS returned an invalid payment amount.']);
        }

        if ($amountMinor !== $attempt->amount_minor) {
            throw ValidationException::withMessages(['gateway' => 'The verified EPS amount does not match this checkout.']);
        }

        // This authenticated merchant endpoint settles BDT only and does not return a currency field.
        return new GatewayVerification('paid', $reference, $amountMinor, 'BDT', $merchantReference);
    }

    private function authorized(#[\SensitiveParameter] array $settings, string $reference): PendingRequest
    {
        $response = $this->request($this->http()->withHeaders(['x-hash' => $this->hash($settings['username'], $settings['hash_key'])]), 'POST', $this->baseUrl($settings).'/v1/Auth/GetToken', [
            'userName' => $settings['username'],
            'password' => $settings['password'],
        ]);
        $token = $response['token'] ?? null;

        if (! is_string($token) || $token === '') {
            throw ValidationException::withMessages(['gateway' => 'EPS merchant authentication failed. Check the configured credentials.']);
        }

        return $this->http()->withToken($token)->withHeaders(['x-hash' => $this->hash($reference, $settings['hash_key'])]);
    }

    private function hash(string $value, #[\SensitiveParameter] string $key): string
    {
        return base64_encode(hash_hmac('sha512', $value, $key, true));
    }

    private function baseUrl(#[\SensitiveParameter] array $settings): string
    {
        return $settings['sandbox'] ? 'https://sandboxpgapi.eps.com.bd' : 'https://pgapi.eps.com.bd';
    }

    private function assertCurrency(CheckoutAttempt $attempt): void
    {
        if ($attempt->currency !== 'BDT') {
            throw ValidationException::withMessages(['gateway' => 'EPS supports BDT payments only.']);
        }
    }
}
