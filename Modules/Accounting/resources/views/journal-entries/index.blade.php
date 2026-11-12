@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => 'Vouchers',
        'create' => [
            'model' => resourceModel($routePrefix.'.create'),
            'url' => route($routePrefix.'.create'),
            'label' => 'New voucher',
        ],
    ])

    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label class="form-label" for="search">Voucher number / Reference</label>
                    <input class="{{ inputClass('search') }}" id="search" name="search" type="search" maxlength="80"
                        value="{{ getValue('search', null, $filters['search'] ?? '') }}" {{ inputAttrs('search') }}>
                    @error('search')<div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">@include('core::partials.filter-actions')</div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Voucher no.</th>
                        <th scope="col">Date</th>
                        <th scope="col">Type / Reference</th>
                        <th scope="col">Status</th>
                        <th class="text-end" scope="col">Amount (BDT)</th>
                        <th class="text-end" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $entry)
                        <tr>
                            <th scope="row"><a href="{{ route($routePrefix.'.show', $entry) }}">{{ $entry->number }}</a></th>
                            <td class="text-nowrap">{{ $entry->entry_date->format('d M Y') }}</td>
                            <td>{{ $entry->voucherType->name }}<div class="small text-body-secondary">{{ $entry->reference }}</div></td>
                            <td>
                                @if ($entry->voided_at)
                                    <span class="badge text-bg-secondary">Voided</span>
                                @elseif ($entry->isAutomatic())
                                    <span class="badge text-bg-info">Automatic</span>
                                @else
                                    <span class="badge text-bg-success">Manual</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">{{ moneyDecimal($entry->total) }}</td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <a class="btn btn-outline-primary btn-sm app-action-button"
                                        href="{{ route($routePrefix.'.show', $entry) }}" aria-label="View {{ $entry->number }}" title="View voucher">
                                        <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                    </a>
                                    @include('core::partials.row-actions', ['model' => $entry])
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-body-secondary py-4" colspan="6">No vouchers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('core::partials.pagination', ['paginator' => $records])
    </section>
@endsection
