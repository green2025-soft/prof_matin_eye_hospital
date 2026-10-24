<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\Core\Helpers\Money;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Http\Requests\PaymentRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Invoice;
use Modules\Core\Models\Payment;
use Modules\Core\Services\PaymentService;

class PaymentController extends BaseController
{
    protected string $model = Payment::class;

    protected string $title = 'Payment';

    protected string $service = PaymentService::class;

    protected string $views = 'core::payments';

    protected string $parameter = 'payment';

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(PaymentRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Payment $payment): View
    {
        Gate::authorize('view', $payment);
        $payment->load('invoice');

        return view('core::payments.show', [
            'payment' => $payment,
            'pageTitle' => 'Payment #'.$payment->getKey(),
            'amount' => Money::decimal($payment->amount_minor),
            'extraCharge' => Money::decimal($payment->extra_charge_minor),
            'received' => Money::decimal($payment->amount_minor + $payment->extra_charge_minor),
            'paidDate' => $payment->paid_at->format('d M Y H:i'),
            'customer' => $payment->invoice->customer_snapshot ?? [],
            'merchant' => $payment->invoice->merchant_snapshot ?? [],
            'invoiceUrl' => Gate::allows('view', $payment->invoice)
                ? route('core.invoices.show', $payment->invoice)
                : null,
        ]);
    }

    protected function formData(Account $actor, Model $record): array
    {
        return [
            'idempotencyKey' => (string) Str::uuid(),
            'invoices' => Invoice::query()
                ->whereIn('status', [Invoice::STATUS_ISSUED, Invoice::STATUS_PARTIALLY_PAID])
                ->whereNotNull('issued_at')
                ->orderByDesc('id')->limit(100)
                ->get(['id', 'number', 'currency', 'total_minor', 'paid_minor']),
        ];
    }

    protected function redirectAfterWrite(?Model $record = null): RedirectResponse
    {
        return $record && Gate::allows('view', $record)
            ? redirect()->route('core.payments.show', $record)
            : redirect()->route('home');
    }
}
