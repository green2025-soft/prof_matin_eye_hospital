@extends('core::layouts.app')
@section('title', ucfirst($payment->product_type).' '.$pageTitle)
@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
        @include('core::partials.heading', ['title' => ucfirst($payment->product_type).' '.$pageTitle])
        <div class="d-flex gap-2 align-self-start d-print-none">
            <a class="btn btn-outline-secondary" href="{{ route('pharmacy.payments.index', ['product_type' => $payment->product_type, 'direction' => $payment->direction]) }}">
                Back
            </a>
            <button class="btn btn-outline-primary" type="button" data-print>
                Print
            </button>
            @if($canVoid)
                <form action="{{ route('pharmacy.payments.void', ['payment' => $payment, 'product_type' => $payment->product_type, 'direction' => $payment->direction]) }}" method="POST" data-confirm="Void this payment and restore the invoice dues?">
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
                    @foreach($allocations as $allocation)
                        <tr>
                            <td>
                                {{ $payment->direction === 'customer' ? $allocation->sale?->number : $allocation->purchase?->number }}
                            </td>
                            <td class="text-end">
                                {{ moneyDecimal($allocation->amount) }}
                            </td>
                            <td class="text-end">
                                {{ moneyDecimal($allocation->discount) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-end">
                            {{ moneyDecimal($payment->amount) }}
                        </th>
                        <th class="text-end">
                            {{ moneyDecimal($payment->discount) }}
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
