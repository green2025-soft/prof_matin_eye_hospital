@extends('core::layouts.app')

@section('title', ucfirst(request()->attributes->get('pharmacy_product_type', 'medicine')).' Sales Return')

@section('content')
    @include('core::partials.heading', ['title' => ($entry->exists ? 'Edit ' : 'New ').ucfirst(request()->attributes->get('pharmacy_product_type', 'medicine')).' Sales Return'.($entry->exists ? ' — '.$entry->number : '')])

    @unless ($entry->exists)
        <form id="pharmacy-return-source-form" method="GET" action="{{ route($routePrefix.'.create') }}" data-pharmacy-return-source>
            <input id="source_product_type" name="product_type" type="hidden" value="{{ request()->attributes->get('pharmacy_product_type', 'medicine') }}">
        </form>
    @endunless

    <form
        class="app-card p-3 p-md-4"
        action="{{ $formAction }}"
        method="POST"
        data-pharmacy-entry
        data-pharmacy-return
        data-type="sale_return"
        data-return-source-total="{{ $source?->total ?? 0 }}"
        data-return-source-returned="{{ $source?->returned ?? 0 }}"
        data-return-source-paid="{{ $source?->paid ?? 0 }}"
        data-return-source-refunded="{{ $source?->refunded ?? 0 }}"
        data-return-source-discount="{{ $source?->settlement_discount ?? 0 }}"
        data-return-edit-total="{{ $entry->exists ? $entry->total : 0 }}"
        data-return-edit-cash="{{ $entry->exists ? $entry->initial_paid : 0 }}"
    >
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif
        <input id="pharmacy_product_type" name="product_type" type="hidden" value="{{ request()->attributes->get('pharmacy_product_type', 'medicine') }}">
        <input name="sale_id" type="hidden" value="{{ $source?->id }}">

        <section class="app-form-section mb-3" aria-labelledby="return-invoice-heading">
            <h2 class="app-form-section-title" id="return-invoice-heading">
                <span class="app-form-section-icon"><i class="fa-solid fa-arrow-rotate-left" aria-hidden="true"></i></span>
                Return details
            </h2>
            <div class="row g-3 align-items-end">
                <div class="col-md-6 col-xl-4">
                    <label class="form-label" for="sale">Original sale invoice</label>
                    @if ($entry->exists)
                        <output class="form-control bg-body-tertiary" id="sale">
                            {{ $source->number }}
                            <span class="small text-body-secondary ms-2">{{ $source->entry_date->toDateString() }}</span>
                        </output>
                    @else
                        <select class="{{ inputClass('sale_id', 'form-select') }}" id="sale" name="sale_id" form="pharmacy-return-source-form" data-ajax-select data-url="{{ route('pharmacy.sale-sources.options') }}" data-parent-select="source_product_type" data-parent-field="product_type" data-placeholder="Search invoice number" {{ inputAttrs('sale_id', 'sale') }}>
                            <option value=""></option>
                            @if ($source)
                                <option value="{{ $source->id }}" selected>{{ $source->number }} / {{ $source->entry_date->toDateString() }}</option>
                            @endif
                        </select>
                    @endif
                    @error('sale_id')<div class="invalid-feedback d-block" id="sale-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 col-xl-3">
                    <label class="form-label" for="return_party">Customer</label>
                    <output class="form-control bg-body-tertiary text-break" id="return_party">{{ $sourceCustomer['name'] ?? '—' }}</output>
                </div>
                <div class="col-sm-6 col-xl-3">
                    @include('pharmacy::entries.partials.date-field')
                </div>
                <div class="col-sm-6 col-xl-2">
                    @include('pharmacy::entries.partials.payment-type-field')
                </div>
            </div>
        </section>

        @if ($source)
            @include('pharmacy::sale-returns.items')
            @include('pharmacy::entries.partials.form-actions')
        @else
            <p class="small text-body-secondary bg-body-tertiary rounded p-3 mb-0">Select an invoice to load its returnable items.</p>
            <div class="app-form-actions border-top pt-3 mt-4">
                <a class="btn btn-warning" href="{{ route($routePrefix.'.index', $routeParameters) }}">Cancel</a>
            </div>
        @endif
    </form>
@endsection
