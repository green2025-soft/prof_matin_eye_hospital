@extends('core::layouts.app')

@section('title', $invoice->number ? $pageTitle : 'Invoice #'.$invoice->id)

@section('content')
    <div class="app-print-hidden">
        @include('core::partials.heading', [
            'title' => $invoice->number ? $pageTitle : 'Invoice #'.$invoice->id,
            'description' => 'Invoice details and payment history.',
        ])
        <div class="d-flex flex-wrap gap-2 mb-4">
            <button class="btn btn-outline-secondary" type="button" data-print>
                <i class="fa-solid fa-print me-2" aria-hidden="true"></i>
                Print
            </button>
            @can('update', $invoice)
                <a class="btn btn-outline-secondary" href="{{ route('core.invoices.edit', $invoice) }}">Edit draft</a>
            @endcan
            @can('issue', $invoice)
                <form
                    action="{{ route('core.invoices.issue', $invoice) }}"
                    method="POST"
                    data-confirm="Issue this invoice? Its details will become final."
                >
                    @csrf
                    <button class="btn btn-primary" type="submit">Issue</button>
                </form>
            @endcan
            @if ($canRecordPayment)
                <a class="btn btn-primary" href="{{ route('core.payments.create') }}">Record payment</a>
            @endif
        </div>
    </div>

    <article class="app-card p-3 p-md-4 app-invoice-document" aria-labelledby="invoice-title">
        <div class="d-flex flex-wrap justify-content-between gap-4 border-bottom pb-4 mb-4">
            <div>
                <h2 class="h5" id="invoice-title">{{ $merchant['name'] }}</h2>
                @if ($merchant['address'] ?? '')
                    <p class="small mb-1 text-break">{{ $merchant['address'] }}</p>
                @endif
                <p class="small mb-1">
                    {{ $merchant['email'] ?? '' }}
                    @if ($merchant['phone'] ?? '')
                        <span class="ms-2">{{ $merchant['phone'] }}</span>
                    @endif
                </p>
                @if ($merchant['tax_id'] ?? '')
                    <p class="small mb-0">Tax ID: {{ $merchant['tax_id'] }}</p>
                @endif
            </div>
            <div class="text-md-end">
                <h2 class="h4">INVOICE</h2>
                <p class="fw-semibold mb-1">{{ $invoice->number ?: 'Invoice #'.$invoice->id }}</p>
                <span class="app-tag">{{ $statusLabel }}</span>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-sm-6">
                <h3 class="small text-body-secondary text-uppercase">Bill to</h3>
                <p class="fw-semibold mb-1">{{ $customer['name'] }}</p>
                <p class="small mb-0">{{ $customer['email'] ?? '' }}</p>
            </div>
            <div class="col-sm-6 text-sm-end">
                <p class="small mb-1">Issued: {{ $issuedDate ?? 'Not issued' }}</p>
                <p class="small mb-1">Due: {{ $dueDate ?? 'Not specified' }}</p>
                @if ($periodLabel)
                    <p class="small mb-0">Period: {{ $periodLabel }}</p>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table class="table app-table align-middle">
                <thead>
                    <tr>
                        <th scope="col">Description</th>
                        <th class="text-end" scope="col">Quantity</th>
                        <th class="text-end" scope="col">Unit amount</th>
                        <th class="text-end" scope="col">Amount ({{ $invoice->currency }})</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td class="text-break">{{ $item['description'] }}</td>
                            <td class="text-end">{{ $item['quantity'] }}</td>
                            <td class="text-end">{{ $item['unit'] }}</td>
                            <td class="text-end">{{ $item['total'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end">
            <div class="col-sm-6 col-lg-4">
                <dl class="mb-0">
                    <div class="d-flex justify-content-between py-2">
                        <dt>Subtotal</dt>
                        <dd class="mb-0">{{ $subtotal }}</dd>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-top">
                        <dt>Total</dt>
                        <dd class="fw-semibold mb-0">{{ $invoice->currency }} {{ $total }}</dd>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <dt>Paid</dt>
                        <dd class="mb-0">{{ $paid }}</dd>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-top">
                        <dt>Balance</dt>
                        <dd class="fw-semibold mb-0">{{ $invoice->currency }} {{ $balance }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        @if ($invoice->notes)
            <div class="border-top mt-4 pt-3">
                <h3 class="h6">Notes</h3>
                <p class="small text-break mb-0">{{ $invoice->notes }}</p>
            </div>
        @endif
        @if ($invoice->payment_instructions)
            <div class="mt-3">
                <h3 class="h6">Payment instructions</h3>
                <p class="small text-break mb-0">{{ $invoice->payment_instructions }}</p>
            </div>
        @endif
        @if ($invoice->void_reason)
            <div class="alert alert-secondary mt-4 mb-0">
                <strong>Voided:</strong>
                {{ $invoice->void_reason }}
            </div>
        @endif
    </article>

    @if ($checkout['canPay'])
        <section class="app-card p-3 p-md-4 mt-4 app-print-hidden" aria-labelledby="payment-title">
            <h2 class="h5" id="payment-title">Pay this invoice</h2>
            <p class="text-secondary small">Amount due: <strong>{{ $invoice->currency }} {{ $balance }}</strong></p>

            @if ($checkout['activeAttempt'])
                <p class="mb-2">{{ $checkout['activeAttempt']['message'] }}</p>
                <p class="small">Extra charge: {{ $invoice->currency }} {{ $checkout['activeAttempt']['extraCharge'] }} · Total payment: <strong>{{ $invoice->currency }} {{ $checkout['activeAttempt']['total'] }}</strong></p>
                @if ($checkout['activeAttempt']['reference'])
                    <p class="small mb-0">Reference: {{ $checkout['activeAttempt']['reference'] }}</p>
                @endif
                @if ($checkout['activeAttempt']['url'])
                    <a class="btn btn-primary mt-2" href="{{ $checkout['activeAttempt']['url'] }}">Resume checkout</a>
                @endif
            @elseif ($checkout['providers'] !== [])
                <form action="{{ $checkout['checkoutAction'] }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="provider">Payment method</label>
                            <select class="{{ inputClass('provider', 'form-select') }}" id="provider" name="provider" required data-payment-provider {{ inputAttrs('provider') }}>
                                @foreach ($checkout['providers'] as $value => $method)
                                    <option value="{{ $value }}" data-extra-charge="{{ $method['extraCharge'] }}" data-total="{{ $method['total'] }}" {{ getSelected($value, 'provider', null, $checkout['defaultProvider']) }}>{{ $method['label'] }} — extra {{ $invoice->currency }} {{ $method['extraCharge'] }}</option>
                                @endforeach
                            </select>
                            @error('provider')
                                <div class="invalid-feedback" id="provider-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12" data-manual-payment-fields>
                            @if ($checkout['manualInstructions'])
                                <p class="text-break">{{ $checkout['manualInstructions'] }}</p>
                            @endif
                            <label class="form-label" for="submitted_reference">Transaction reference</label>
                            <input class="{{ inputClass('submitted_reference') }}" id="submitted_reference" name="submitted_reference" value="{{ getValue('submitted_reference') }}" maxlength="190" required {{ inputAttrs('submitted_reference', 'submitted_reference', 'manual-payment-help') }}>
                            @error('submitted_reference')
                                <div class="invalid-feedback" id="submitted_reference-error">{{ $message }}</div>
                            @enderror
                            <p class="form-text mb-0" id="manual-payment-help">Enter the reference from your completed transfer. A platform operator will review it.</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="payment-notes">Payment note <span class="text-secondary">(optional)</span></label>
                            <textarea class="{{ inputClass('notes') }}" id="payment-notes" name="notes" rows="2" maxlength="500" {{ inputAttrs('notes', 'payment-notes') }}>{{ getValue('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback" id="payment-notes-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <p class="small mb-1">Extra charge: {{ $invoice->currency }} <span data-payment-extra-charge>{{ $checkout['providers'][$checkout['defaultProvider']]['extraCharge'] }}</span></p>
                            <p class="fw-semibold">Total payment: {{ $invoice->currency }} <span data-payment-total>{{ $checkout['providers'][$checkout['defaultProvider']]['total'] }}</span></p>
                            <button class="btn btn-primary" type="submit">Pay</button>
                        </div>
                    </div>
                </form>
            @else
                <p class="mb-0 text-secondary">No payment methods are enabled. Contact the platform administrator.</p>
            @endif
        </section>
    @endif

    @if ($payments !== [])
        <section class="app-card mt-4 overflow-hidden">
            <div class="app-card-heading px-3 py-3 border-bottom">
                <h2 class="h6 mb-0">Recent payments (latest 20)</h2>
            </div>
            <div class="table-responsive">
                <table class="table app-table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Provider</th>
                            <th scope="col">Reference</th>
                            <th class="text-end" scope="col">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ $payment['date'] }}</td>
                                <td>{{ $payment['provider'] }}</td>
                                <td class="text-break">{{ $payment['reference'] ?? '-' }}</td>
                                <td class="text-end">
                                    {{ $invoice->currency }} {{ $payment['received'] }}
                                    <div class="small text-body-secondary">Invoice {{ $payment['amount'] }} + extra {{ $payment['extraCharge'] }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    @can('void', $invoice)
        <form
            class="app-card p-3 p-md-4 mt-4 app-print-hidden"
            action="{{ route('core.invoices.void', $invoice) }}"
            method="POST"
            data-confirm="Void this unpaid invoice?"
        >
            @csrf
            <label class="form-label" for="reason">Void reason</label>
            <textarea class="{{ inputClass('reason') }}" id="reason" name="reason" rows="2" required {{ inputAttrs('reason') }}>{{ getValue('reason') }}</textarea>
            @error('reason')
                <div class="invalid-feedback d-block" id="reason-error">{{ $message }}</div>
            @enderror
            <button class="btn btn-outline-danger mt-3" type="submit">Void</button>
        </form>
    @endcan
@endsection
