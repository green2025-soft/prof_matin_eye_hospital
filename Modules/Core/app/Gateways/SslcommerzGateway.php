<?php

namespace Modules\Core\Gateways;

use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\CheckoutAttempt;

final class SslcommerzGateway extends BaseGateway
{
    public function start(CheckoutAttempt $attempt, #[\SensitiveParameter] array $settings, string $callbackUrl): GatewayCheckout
    {
        $customer = $attempt->invoice->customer_snapshot ?? [];
        $response = $this->request($this->http()->asForm(), 'POST', $this->baseUrl($settings).'/gwprocess/v4/api.php', [
            'store_id' => $settings['store_id'],
            'store_passwd' => $settings['store_password'],
            'tran_id' => $attempt->reference,
            'total_amount' => Money::decimal($attempt->amount_minor),
            'currency' => $attempt->currency,
            'success_url' => $callbackUrl,
            'fail_url' => $callbackUrl,
            'cancel_url' => $callbackUrl,
            'ipn_url' => $settings['notify_url'] ?? $callbackUrl,
            'cus_name' => $customer['name'] ?? '',
            'cus_email' => $customer['email'] ?? '',
            'cus_phone' => $customer['phone'] ?? '',
            'cus_add1' => $customer['address'] ?? '',
            'cus_city' => $customer['city'] ?? '',
            'cus_state' => $customer['state'] ?? '',
            'cus_postcode' => $customer['postcode'] ?? '',
            'cus_country' => 'Bangladesh',
            'shipping_method' => 'NO',
            'num_of_item' => 1,
            'product_name' => 'Invoice '.$attempt->invoice->number,
            'product_category' => 'Service',
            'product_profile' => 'general',
            'emi_option' => 0,
        ]);
        $reference = $response['sessionkey'] ?? null;
        $url = $response['GatewayPageURL'] ?? null;

        if (($response['status'] ?? null) !== 'SUCCESS' || ! is_string($reference) || $reference === '' || ! is_string($url) || $url === '') {
            throw ValidationException::withMessages(['gateway' => 'SSLCommerz could not initialize this payment. Check the merchant configuration and customer billing details.']);
        }

        return new GatewayCheckout($reference, $this->redirectUrl($url, ['sslcommerz.com'], (bool) $settings['sandbox']));
    }

    public function verify(CheckoutAttempt $attempt, #[\SensitiveParameter] array $settings, array $callback): GatewayVerification
    {
        $validationId = $callback['val_id'] ?? null;

        if (is_string($validationId) && $validationId !== '' && strlen($validationId) <= 255) {
            return $this->validatePayment($attempt, $settings, $validationId);
        }

        $response = $this->request($this->http(), 'GET', $this->baseUrl($settings).'/validator/api/merchantTransIDvalidationAPI.php', [
            'tran_id' => $attempt->reference,
            'store_id' => $settings['store_id'],
            'store_passwd' => $settings['store_password'],
            'v' => 1,
            'format' => 'json',
        ]);

        if (($response['APIConnect'] ?? null) !== 'DONE' || ! is_array($response['element'] ?? null)) {
            return new GatewayVerification('pending');
        }

        $matched = false;
        $allFailed = true;

        foreach ($response['element'] as $transaction) {
            if (! is_array($transaction) || ($transaction['tran_id'] ?? null) !== $attempt->reference) {
                continue;
            }

            $matched = true;

            if (! $this->matchesPayment($attempt, $transaction)) {
                $allFailed = false;

                continue;
            }

            $status = $transaction['status'] ?? null;
            $validationId = $transaction['val_id'] ?? null;

            if (in_array($status, ['VALID', 'VALIDATED'], true) && is_string($validationId) && $validationId !== '') {
                $result = $this->validatePayment($attempt, $settings, $validationId);

                if ($result->status === 'paid') {
                    return $result;
                }

                $allFailed = false;
            } elseif (! in_array($status, ['FAILED', 'CANCELLED', 'CANCEL'], true)) {
                $allFailed = false;
            }
        }

        return new GatewayVerification($matched && $allFailed ? 'failed' : 'pending');
    }

    private function validatePayment(CheckoutAttempt $attempt, #[\SensitiveParameter] array $settings, string $validationId): GatewayVerification
    {
        $response = $this->request($this->http(), 'GET', $this->baseUrl($settings).'/validator/api/validationserverAPI.php', [
            'val_id' => $validationId,
            'store_id' => $settings['store_id'],
            'store_passwd' => $settings['store_password'],
            'format' => 'json',
        ]);
        $status = $response['status'] ?? null;

        if (! in_array($status, ['VALID', 'VALIDATED'], true)) {
            $failed = in_array($status, ['FAILED', 'CANCELLED', 'CANCEL'], true) && $this->matchesPayment($attempt, $response);

            return new GatewayVerification($failed ? 'failed' : 'pending');
        }

        $reference = $response['bank_tran_id'] ?? null;
        $currency = $response['currency_type'] ?? $response['currency'] ?? null;

        if (! $this->matchesPayment($attempt, $response) || ! is_string($reference) || $reference === ''
            || ! in_array($response['risk_level'] ?? null, ['0', 0], true)
            || ($response['val_id'] ?? null) !== $validationId) {
            throw ValidationException::withMessages(['gateway' => 'SSLCommerz returned a risky, incomplete or mismatched payment verification.']);
        }

        return new GatewayVerification('paid', $reference, $attempt->amount_minor, $currency, $response['tran_id']);
    }

    private function matchesPayment(CheckoutAttempt $attempt, array $transaction): bool
    {
        $amount = $transaction['currency_amount'] ?? $transaction['amount'] ?? null;
        $currency = $transaction['currency_type'] ?? $transaction['currency'] ?? null;

        if (($transaction['tran_id'] ?? null) !== $attempt->reference || $currency !== $attempt->currency || ! is_scalar($amount)) {
            return false;
        }

        try {
            return Money::minor((string) $amount) === $attempt->amount_minor;
        } catch (InvalidArgumentException) {
            return false;
        }
    }

    private function baseUrl(#[\SensitiveParameter] array $settings): string
    {
        return $settings['sandbox'] ? 'https://sandbox.sslcommerz.com' : 'https://securepay.sslcommerz.com';
    }
}
