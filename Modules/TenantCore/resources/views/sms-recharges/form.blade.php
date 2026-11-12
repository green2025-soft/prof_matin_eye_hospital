@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])
    @if ($account?->enabled)
        <form class="app-card p-3 p-md-4" method="POST" action="{{ route('tenantcore.sms-recharges.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="amount">Amount (BDT)</label>
                    <input class="{{ inputClass('amount') }}" id="amount" name="amount" inputmode="decimal" value="{{ getValue('amount') }}" required autofocus>
                    @error('amount')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="payment_method">Payment method</label>
                    <input class="{{ inputClass('payment_method') }}" id="payment_method" name="payment_method" value="{{ getValue('payment_method') }}" maxlength="100" placeholder="Bank / bKash / Nagad" required>
                    @error('payment_method')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="payment_reference">Transaction reference</label>
                    <input class="{{ inputClass('payment_reference') }}" id="payment_reference" name="payment_reference" value="{{ getValue('payment_reference') }}" maxlength="150" required>
                    @error('payment_reference')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="proof">Payment proof (optional)</label>
                    <input class="{{ inputClass('proof') }}" id="proof" name="proof" type="file" accept=".png,.jpg,.jpeg,.webp,.pdf">
                    <div class="form-text">Image or PDF, up to 5 MB.</div>
                    @error('proof')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="d-flex justify-content-end gap-2 border-top mt-4 pt-3">
                <a class="btn btn-warning" href="{{ $cancelUrl }}">Cancel</a>
                <button class="btn btn-primary" type="submit">Submit request</button>
            </div>
        </form>
    @else
        <div class="alert alert-secondary">SMS is not enabled. Contact the platform administrator.</div>
    @endif
@endsection
