@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <div class="app-print-hidden">
        @include('core::partials.heading', ['title' => $pageTitle])
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route($routePrefix.'.index') }}">
                <i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Back
            </a>
            <button class="btn btn-primary btn-sm" type="button" data-print>
                <i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print voucher
            </button>
            @can('update', $journal_entry)
                <a class="btn btn-outline-primary btn-sm" href="{{ route($routePrefix.'.edit', $journal_entry) }}">
                    <i class="fa-solid fa-pen-to-square me-2" aria-hidden="true"></i>Edit
                </a>
            @endcan
        </div>
    </div>

    <article class="app-card app-invoice-document app-clinical-invoice d-flex flex-column">
        <header>
            <div class="app-clinical-invoice-brand d-flex align-items-center justify-content-center text-center">
                @if ($logoUrl)<img class="app-clinical-invoice-logo" src="{{ $logoUrl }}" width="76" height="76" alt="">@endif
                <div>
                    <h1 class="app-clinical-invoice-company">{{ $company['name'] }}</h1>
                    @if ($company['address'])<p class="app-clinical-invoice-address">{{ $company['address'] }}</p>@endif
                    <div class="app-clinical-invoice-contact d-flex flex-wrap justify-content-center gap-3">
                        @if ($company['phone'])<span>Phone: {{ $company['phone'] }}</span>@endif
                        @if ($company['email'])<span>{{ $company['email'] }}</span>@endif
                    </div>
                </div>
            </div>
            <div class="app-clinical-invoice-title-row d-flex align-items-center justify-content-between gap-3">
                <div>
                    <h2 class="h5 mb-1">{{ $journal_entry->voucherType->name }}</h2>
                    <p class="fw-semibold mb-0">Voucher no.: {{ $journal_entry->number }}</p>
                </div>
                <div class="text-end">
                    <p class="mb-1">Date: {{ $journal_entry->entry_date->format('d-m-Y') }}</p>
                    @if ($journal_entry->voided_at)
                        <strong class="text-danger">VOIDED</strong>
                    @elseif ($journal_entry->isAutomatic())
                        <span class="small">Automatic voucher</span>
                    @else
                        <span class="small">Manual voucher</span>
                    @endif
                </div>
            </div>
        </header>

        @if ($journal_entry->reference)<p class="small mb-3"><strong>Reference:</strong> {{ $journal_entry->reference }}</p>@endif
        <div class="table-responsive">
            <table class="table app-clinical-invoice-table align-middle">
                <thead>
                    <tr>
                        <th scope="col">Code</th>
                        <th scope="col">Account head / Remarks</th>
                        <th class="text-end" scope="col">Debit (BDT)</th>
                        <th class="text-end" scope="col">Credit (BDT)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($journal_entry->lines as $line)
                        <tr>
                            <td class="text-nowrap">{{ $line->accountHead->code }}</td>
                            <td>{{ $line->accountHead->name }}<div class="small text-body-secondary">{{ $line->remarks }}</div></td>
                            <td class="text-end text-nowrap">{{ $line->debit > 0 ? moneyDecimal($line->debit) : '—' }}</td>
                            <td class="text-end text-nowrap">{{ $line->credit > 0 ? moneyDecimal($line->credit) : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-semibold">
                        <th colspan="2" scope="row">Total</th>
                        <td class="text-end text-nowrap">{{ moneyDecimal($journal_entry->total) }}</td>
                        <td class="text-end text-nowrap">{{ moneyDecimal($journal_entry->total) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <p class="small mb-3"><strong>In words:</strong> {{ $amountWords }}</p>
        @if ($journal_entry->narration)<p class="text-break small"><strong>Narration:</strong> {{ $journal_entry->narration }}</p>@endif

        <footer class="mt-auto pt-5">
            <div class="row g-4 text-center pt-4 mb-4">
                <div class="col-4"><div class="border-top pt-2">Prepared by</div><div class="small">{{ $journal_entry->created_by_name }}</div></div>
                <div class="col-4"><div class="border-top pt-2">Checked by</div></div>
                <div class="col-4"><div class="border-top pt-2">Authorized by</div></div>
            </div>
            <p class="small text-body-secondary border-top pt-2 mb-0">Printed by: {{ $printedBy }} &middot; {{ $printedAt }}</p>
        </footer>
    </article>
@endsection
