@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => 'Payment type ledgers'])
    <div class="row g-3 align-items-start">
        @if ($form !== null)
            <aside class="{{ $records !== null ? 'col-lg-4' : 'col-12' }}">@include('accounting::payment-accounts.form', $form)</aside>
        @endif
        @if ($records !== null)
            <div class="{{ $form !== null ? 'col-lg-8' : 'col-12' }}">
                <section class="app-card overflow-hidden">
                    <div class="table-responsive">
                        <table class="table app-table align-middle mb-0">
                            <thead><tr><th>Payment type</th><th>Ledger</th><th class="text-end">Actions</th></tr></thead>
                            <tbody>
                                @forelse ($records as $mapping)
                                    <tr><th scope="row">{{ $mapping->paymentType->name }}</th><td>{{ $mapping->accountHead->code }} · {{ $mapping->accountHead->name }}</td><td>@include('core::partials.row-actions', ['model' => $mapping, 'routePrefix' => $routePrefix])</td></tr>
                                @empty
                                    <tr><td class="text-center text-body-secondary py-4" colspan="3">No payment ledgers configured.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
                </section>
            </div>
        @endif
    </div>
@endsection
