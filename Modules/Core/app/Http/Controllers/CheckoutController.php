<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Modules\Core\Http\Requests\CheckoutRequest;
use Modules\Core\Http\Requests\PaymentReviewRequest;
use Modules\Core\Models\CheckoutAttempt;
use Modules\Core\Models\Invoice;
use Modules\Core\Services\CheckoutService;

class CheckoutController extends BaseController
{
    protected string $model = CheckoutAttempt::class;

    protected string $title = 'Payment request';

    protected string $service = CheckoutService::class;

    protected string $parameter = 'checkout';

    // Bind the invoice before CheckoutRequest authorizes and prepares its payload.
    public function store(CheckoutRequest $request, Invoice $invoice): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function approve(PaymentReviewRequest $request, CheckoutAttempt $checkout, CheckoutService $payments): RedirectResponse
    {
        $payments->approve($request->user('web'), $checkout);

        return redirect()->route('core.invoices.index')->with('status', 'Manual payment approved.');
    }

    public function reject(PaymentReviewRequest $request, CheckoutAttempt $checkout, CheckoutService $payments): RedirectResponse
    {
        $payments->reject($request->user('web'), $checkout, $request->validated('reason'));

        return redirect()->route('core.invoices.index')->with('status', 'Manual payment rejected.');
    }

    protected function redirectAfterWrite(?Model $record = null): RedirectResponse
    {
        return filled($record->redirect_url) ? redirect()->away($record->redirect_url) : redirect()->to($record->return_url);
    }
}
