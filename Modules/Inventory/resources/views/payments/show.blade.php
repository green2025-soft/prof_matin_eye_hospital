@extends('core::layouts.app')
@section('title', ucfirst($payment->product_type).' '.$pageTitle)
@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
        @include('core::partials.heading', ['title' => ucfirst($payment->product_type).' '.$pageTitle])
        <div class="d-flex gap-2 align-self-start d-print-none">
            <a class="btn btn-outline-secondary" href="{{ route('inventory.payments.index', ['product_type' => $payment->product_type, 'direction' => $payment->direction]) }}">
                Back
            </a>
            <button class="btn btn-outline-primary" type="button" data-inventory-print>
                Print
            </button>
            @if($canVoid)
                <form action="{{ route('inventory.payments.void', ['payment' => $payment, 'product_type' => $payment->product_type, 'direction' => $payment->direction]) }}" method="POST" data-inventory-confirm="Void this payment and restore the invoice dues?">
                    @csrf
                    <button class="btn btn-outline-danger" type="submit">
                        Void
                    </button>
                </form>
            @endif
        </div>
    </div>
    <section class="app-card p-3 p-md-4">
        @if($payment->voided_at)
            <div class="alert alert-secondary">
                Voided on {{ $payment->voided_at->format('Y-m-d H:i') }}
            </div>
        @endif
        <dl class="row">
            <div class="col-sm-4">
                <dt class="small text-body-secondary">Date</dt>
                <dd>
                    {{ $payment->payment_date->format('Y-m-d') }}
                </dd>
            </div>
            <div class="col-sm-4">
                <dt class="small text-body-secondary">Payment</dt>
                <dd>
                    {{ $payment->direction === 'customer' ? 'Customer collection' : 'Supplier payment' }} / {{ ucfirst($payment->product_type) }}
                </dd>
            </div>
            <div class="col-sm-4">
                <dt class="small text-body-secondary">Party</dt>
                <dd>
                    {{ $customer['name'] ?? $supplier?->name }}
                </dd>
            </div>
        </dl>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Invoice</th>
                        <th scope="col" class="text-end">Cash</th>
                        <th scope="col" class="text-end">Discount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payment->allocations as $allocation)
                        <tr>
                            <td>
                                {{ $allocation->document?->number }}
                            </td>
                            <td class="text-end">
                                {{ \Modules\Core\Helpers\Money::decimal($allocation->amount_minor) }}
                            </td>
                            <td class="text-end">
                                {{ \Modules\Core\Helpers\Money::decimal($allocation->discount_minor) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-end">
                            {{ \Modules\Core\Helpers\Money::decimal($payment->amount_minor) }}
                        </th>
                        <th class="text-end">
                            {{ \Modules\Core\Helpers\Money::decimal($payment->discount_minor) }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
        @if($payment->notes)
            <p class="mb-0 text-break">
                {{ $payment->notes }}
            </p>
        @endif
    </section>
@endsection
