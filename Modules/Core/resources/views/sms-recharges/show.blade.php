@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])
    <section class="app-card p-3 p-md-4">
        <dl class="row gy-2 mb-0">
            <dt class="col-sm-3">Organization</dt><dd class="col-sm-9 mb-0">{{ $recharge->tenant->name }}</dd>
            <dt class="col-sm-3">Amount</dt><dd class="col-sm-9 fw-semibold mb-0">BDT {{ moneyDecimal($recharge->amount) }}</dd>
            <dt class="col-sm-3">Payment method</dt><dd class="col-sm-9 mb-0">{{ $recharge->payment_method }}</dd>
            <dt class="col-sm-3">Reference</dt><dd class="col-sm-9 text-break mb-0">{{ $recharge->payment_reference }}</dd>
            <dt class="col-sm-3">Status</dt><dd class="col-sm-9 mb-0">{{ $statusLabel }}</dd>
            <dt class="col-sm-3">Review note</dt><dd class="col-sm-9 text-break mb-0">{{ $recharge->review_note ?? '-' }}</dd>
        </dl>
        @if ($recharge->proof_path)
            @can('viewProof', $recharge)
                <a class="btn btn-outline-primary mt-3" href="{{ route('core.sms-recharges.proof', $recharge) }}">Download payment proof</a>
            @endcan
        @endif
        @if ($recharge->status === 'pending')
            @can('reviewRecharge', $recharge)
                <form class="border-top mt-4 pt-3" method="POST" action="{{ route('core.sms-recharges.update', $recharge) }}" data-confirm="Save this recharge decision after checking the payment?">
                    @csrf @method('PUT')
                    <label class="form-label" for="review_note">Review note</label>
                    <textarea class="{{ inputClass('review_note') }}" id="review_note" name="review_note" rows="2" maxlength="1000">{{ getValue('review_note') }}</textarea>
                    @error('review_note')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-success" name="decision" value="approve" type="submit">Approve and credit balance</button>
                        <button class="btn btn-danger" name="decision" value="reject" type="submit">Reject</button>
                    </div>
                </form>
            @endcan
        @endif
    </section>
@endsection
