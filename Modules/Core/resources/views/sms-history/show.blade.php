@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])
    <section class="app-card p-3 p-md-4">
        <dl class="row gy-2 mb-3">
            <dt class="col-sm-3">Organization</dt><dd class="col-sm-9 mb-0">{{ $message->tenant->name }}</dd>
            <dt class="col-sm-3">Feature</dt><dd class="col-sm-9 mb-0">{{ $featureLabel }}</dd>
            <dt class="col-sm-3">Recipient</dt><dd class="col-sm-9 mb-0">{{ $message->recipient }}</dd>
            <dt class="col-sm-3">Status</dt><dd class="col-sm-9 mb-0">{{ $statusLabel }}</dd>
            <dt class="col-sm-3">Parts / Attempts</dt><dd class="col-sm-9 mb-0">{{ $message->segments }} / {{ $message->attempts }}</dd>
            <dt class="col-sm-3">Charge</dt><dd class="col-sm-9 mb-0">BDT {{ moneyDecimal($message->charge) }}</dd>
        </dl>
        <p class="text-break border rounded p-3 mb-2">{{ $message->message }}</p>
        @if ($message->error)<p class="small text-danger text-break mb-2">{{ $message->error }}</p>@endif
        <p class="small text-body-secondary mb-0">Submitted means accepted by the gateway, not confirmed delivery.</p>
        @if ($message->status === 'uncertain')
            @can('resolveMessage', $message)
                <form class="d-flex flex-wrap gap-2 border-top mt-3 pt-3" method="POST" action="{{ route('core.sms-history.resolve', $message) }}" data-confirm="Have you checked this result with the SMS provider?">
                    @csrf
                    <button class="btn btn-success" type="submit" name="decision" value="submitted">Provider accepted</button>
                    <button class="btn btn-danger" type="submit" name="decision" value="rejected">Provider rejected — refund</button>
                </form>
            @endcan
        @endif
    </section>
@endsection
