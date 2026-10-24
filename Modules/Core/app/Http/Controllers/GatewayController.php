<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Core\Models\CheckoutAttempt;
use Modules\Core\Services\CheckoutService;

class GatewayController extends Controller
{
    public function callback(Request $request, string $gateway, string $token, CheckoutService $payments): RedirectResponse
    {
        $attempt = $payments->verify($gateway, $token, $this->callbackValues($request));
        $message = match ($attempt->status) {
            CheckoutAttempt::STATUS_PAID => 'Payment received. Thank you.',
            CheckoutAttempt::STATUS_FAILED => 'The payment did not complete. You can try again.',
            default => 'Payment confirmation is pending. Please check Billing shortly.',
        };

        return redirect()->to($attempt->return_url)->with('status', $message);
    }

    public function notify(Request $request, string $gateway, string $token, CheckoutService $payments): JsonResponse
    {
        $attempt = $payments->verify($gateway, $token, $this->callbackValues($request));

        return response()->json(['received' => true, 'status' => $attempt->status]);
    }

    private function callbackValues(Request $request): array
    {
        return $request->validate([
            'val_id' => ['nullable', 'string', 'max:190'],
            'paymentID' => ['nullable', 'string', 'max:190'],
            'payment_ref_id' => ['nullable', 'string', 'max:190'],
            'status' => ['nullable', 'string', 'max:50'],
        ]);
    }
}
