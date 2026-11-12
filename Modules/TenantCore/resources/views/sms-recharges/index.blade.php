@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])
    <a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('tenantcore.sms.index') }}">SMS wallet</a>
    <section class="app-card overflow-hidden">
        <div class="table-responsive overflow-y-hidden">
            <table class="table app-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Reference / Method</th>
                        <th>Status</th>
                        <th class="text-end">Amount (BDT)</th>
                        <th>Review note</th>
                        <th>Date</th>
                        <th>Proof</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $recharge)
                        <tr>
                            <th scope="row">{{ $recharge->payment_reference }}<div class="small text-body-secondary fw-normal">{{ $recharge->payment_method }}</div></th>
                            <td>{{ $statuses[$recharge->status] }}</td><td class="text-end">{{ moneyDecimal($recharge->amount) }}</td>
                            <td class="small text-break">{{ $recharge->review_note ?? '-' }}</td><td class="small text-nowrap">{{ $recharge->created_at->format('d M Y H:i') }}</td>
                            <td>@if ($recharge->proof_path)<a href="{{ route('tenantcore.sms-recharges.proof', $recharge) }}">Download</a>@endif</td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-body-secondary py-4" colspan="6">No recharge requests.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('core::partials.pagination', ['paginator' => $records])
    </section>
@endsection
