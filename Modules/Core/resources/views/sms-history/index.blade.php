@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])
    <section class="app-card overflow-hidden">
        <form class="row g-2 align-items-end p-3 border-bottom" method="GET" action="{{ route('core.sms-history.index') }}">
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
                        <th>Organization / Feature</th>
                        <th>Recipient</th>
                        <th>Status</th>
                        <th class="text-end">Parts</th>
                        <th class="text-end">Charge (BDT)</th>
                        <th>Date</th>
                        <th class="text-end">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $message)
                        <tr>
                            <th scope="row">{{ $message->tenant->name }}<div class="small fw-normal text-body-secondary">{{ $features[$message->feature] ?? $message->feature }}</div></th>
                            <td>{{ $message->recipient }}</td>
                            <td>{{ $statuses[$message->status] }}</td>
                            <td class="text-end">{{ $message->segments }}</td>
                            <td class="text-end">{{ moneyDecimal($message->charge) }}</td>
                            <td class="small text-nowrap">{{ $message->created_at->format('d M Y H:i') }}</td>
                            <td class="text-end"><a class="btn btn-outline-primary btn-sm" href="{{ route('core.sms-history.show', $message) }}">Details</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-body-secondary py-4">No SMS history.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('core::partials.pagination', ['paginator' => $records])
    </section>
@endsection
