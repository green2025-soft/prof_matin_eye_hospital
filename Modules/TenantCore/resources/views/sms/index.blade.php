@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <h1 class="h4 mb-0">{{ $pageTitle }}</h1>
        @if ($account?->enabled)
            @can('requestRecharge', $rechargeModel)
                <a class="btn btn-primary" href="{{ route('tenantcore.sms-recharges.create') }}">Request recharge</a>
            @endcan
        @endif
    </header>
    <div class="row g-3 mb-3">
        <div class="col-md-4"><section class="app-card bg-primary-subtle p-3"><p class="small mb-1">Available balance</p><p class="h3 mb-0">BDT {{ moneyDecimal($account?->balance ?? 0) }}</p></section></div>
        <div class="col-md-4"><section class="app-card bg-success-subtle p-3"><p class="small mb-1">Rate / SMS part</p><p class="h3 mb-0">BDT {{ moneyDecimal($account?->rate ?? 0) }}</p></section></div>
        <div class="col-md-4"><section class="app-card bg-info-subtle p-3"><p class="small mb-1">Monthly part limit</p><p class="h3 mb-0">{{ $account?->monthly_limit ?? 'Unlimited' }}</p></section></div>
    </div>
    @unless ($account?->enabled)<div class="alert alert-secondary">SMS is not enabled. Contact the platform administrator.</div>@endunless
    <section class="app-card mb-3 overflow-hidden">
        <header class="d-flex justify-content-between align-items-center gap-2 p-3 border-bottom"><h2 class="h6 mb-0">Recent recharges</h2><a href="{{ route('tenantcore.sms-recharges.index') }}">View all</a></header>
        <div class="table-responsive overflow-y-hidden">
            <table class="table app-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Status</th>
                        <th class="text-end">Amount (BDT)</th>
                        <th>Requested</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recharges as $recharge)
                        <tr><td>{{ $recharge->payment_reference }}</td><td>{{ $rechargeStatuses[$recharge->status] }}</td><td class="text-end">{{ moneyDecimal($recharge->amount) }}</td><td class="small">{{ $recharge->created_at->format('d M Y H:i') }}</td></tr>
                    @empty
                        <tr><td class="text-center text-body-secondary py-3" colspan="4">No recharge requests.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <section class="app-card overflow-hidden">
        <header class="p-3 border-bottom"><h2 class="h6 mb-0">SMS history</h2></header>
        <div class="table-responsive overflow-y-hidden">
            <table class="table app-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Feature / Recipient</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th class="text-end">Parts / Attempts</th>
                        <th class="text-end">Charge (BDT)</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $message)
                        <tr>
                            <td>{{ $features[$message->feature] ?? $message->feature }}<div class="small text-body-secondary">{{ $message->recipient }}</div></td>
                            <td class="small text-break">
                                <details>
                                    <summary class="text-nowrap">{{ str($message->message)->limit(60) }}</summary>
                                    <p class="mt-2 mb-0">{{ $message->message }}</p>
                                </details>
                            </td>
                            <td>
                                {{ $statuses[$message->status] }}
                                @if ($message->error)<div class="small text-danger text-break mt-1">{{ $message->error }}</div>@endif
                            </td>
                            <td class="text-end">{{ $message->segments }} / {{ $message->attempts }}</td>
                            <td class="text-end">{{ moneyDecimal($message->charge) }}</td>
                            <td class="small text-nowrap">{{ $message->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-body-secondary py-4" colspan="6">No SMS history.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('core::partials.pagination', ['paginator' => $records])
    </section>
@endsection
