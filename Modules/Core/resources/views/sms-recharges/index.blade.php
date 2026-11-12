@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])
    <section class="app-card overflow-hidden">
        <form class="row g-2 align-items-end p-3 border-bottom" method="GET" action="{{ route('core.sms-recharges.index') }}">
            <div class="col-md-8">
                <label class="form-label" for="status">Status</label>
                <select class="form-select" id="status" name="status"><option value="">All statuses</option>@foreach ($statuses as $value => $label)<option value="{{ $value }}" {{ getSelected($value, 'status', null, $filters['status'] ?? '') }}>{{ $label }}</option>@endforeach</select>
            </div>
            <div class="col-md-4">@include('core::partials.filter-actions')</div>
        </form>
        <div class="table-responsive overflow-y-hidden">
            <table class="table app-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Organization</th>
                        <th>Payment reference</th>
                        <th>Status</th>
                        <th class="text-end">Amount (BDT)</th>
                        <th>Requested</th>
                        <th class="text-end">Review</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $recharge)
                        <tr>
                            <th scope="row">{{ $recharge->tenant->name }}</th>
                            <td>{{ $recharge->payment_reference }}<div class="small text-body-secondary">{{ $recharge->payment_method }}</div></td>
                            <td>{{ $statuses[$recharge->status] }}</td>
                            <td class="text-end">{{ moneyDecimal($recharge->amount) }}</td>
                            <td class="small text-nowrap">{{ $recharge->created_at->format('d M Y H:i') }}</td>
                            <td class="text-end"><a class="btn btn-outline-primary btn-sm" href="{{ route('core.sms-recharges.show', $recharge) }}">Details</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-body-secondary py-4">No recharge requests.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('core::partials.pagination', ['paginator' => $records])
    </section>
@endsection
