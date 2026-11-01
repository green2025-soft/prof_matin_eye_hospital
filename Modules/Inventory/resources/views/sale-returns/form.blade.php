@extends('core::layouts.app')

@section('title', ucfirst(request()->attributes->get('inventory_product_type', 'medicine')).' Sales Return')

@section('content')
    @include('core::partials.heading', ['title' => ($document->exists ? 'Edit ' : 'New ').ucfirst(request()->attributes->get('inventory_product_type', 'medicine')).' Sales Return'.($document->exists ? ' — '.$document->number : '')])

    @unless ($document->exists)
        <form id="inventory-return-source-form" method="GET" action="{{ route($routePrefix.'.create') }}" data-inventory-return-source>
            <input id="source_product_type" name="product_type" type="hidden" value="{{ request()->attributes->get('inventory_product_type', 'medicine') }}">
        </form>
    @endunless

    <form
        class="app-card p-3 p-md-4"
        action="{{ $formAction }}"
        method="POST"
        data-inventory-document
        data-inventory-return
        data-type="sale_return"
        data-return-source-total="{{ $source?->total_minor ?? 0 }}"
        data-return-source-returned="{{ $source?->returned_minor ?? 0 }}"
        data-return-source-paid="{{ $source?->paid_minor ?? 0 }}"
        data-return-source-refunded="{{ $source?->refunded_minor ?? 0 }}"
        data-return-source-discount="{{ $source?->settlement_discount_minor ?? 0 }}"
        data-return-edit-total="{{ $document->exists ? $document->total_minor : 0 }}"
        data-return-edit-cash="{{ $document->exists ? $document->initial_paid_minor : 0 }}"
    >
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif
        <input id="inventory_product_type" name="product_type" type="hidden" value="{{ request()->attributes->get('inventory_product_type', 'medicine') }}">
        <input name="source_document_id" type="hidden" value="{{ $source?->id }}">

        <section class="app-form-section mb-3" aria-labelledby="return-invoice-heading">
            <h2 class="app-form-section-title" id="return-invoice-heading">
                <span class="app-form-section-icon"><i class="fa-solid fa-arrow-rotate-left" aria-hidden="true"></i></span>
                Return details
            </h2>
            <div class="row g-3 align-items-end">
                <div class="col-md-6 col-xl-4">
                    <label class="form-label" for="source_document">Original sale invoice</label>
                    @if ($document->exists)
                        <output class="form-control bg-body-tertiary" id="source_document">
                            {{ $source->number }}
                            <span class="small text-body-secondary ms-2">{{ $source->document_date->toDateString() }}</span>
                        </output>
                    @else
                        <select class="{{ inputClass('source_document_id', 'form-select') }}" id="source_document" name="source_document_id" form="inventory-return-source-form" data-inventory-return-source-select data-ajax-select data-url="{{ route('inventory.sources.options', ['source_type' => 'sale']) }}" data-parent-select="source_product_type" data-parent-field="product_type" data-placeholder="Search invoice number" {{ inputAttrs('source_document_id', 'source_document') }}>
                            <option value=""></option>
                            @if ($source)
                                <option value="{{ $source->id }}" selected>{{ $source->number }} / {{ $source->document_date->toDateString() }}</option>
                            @endif
                        </select>
                    @endif
                    @error('source_document_id')<div class="invalid-feedback d-block" id="source_document-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 col-xl-3">
                    <label class="form-label" for="return_party">Customer</label>
                    <output class="form-control bg-body-tertiary text-break" id="return_party">{{ $sourceCustomer['name'] ?? '—' }}</output>
                </div>
                <div class="col-sm-6 col-xl-3">
                    @include('inventory::entries.partials.date-field')
                </div>
                <div class="col-sm-6 col-xl-2">
                    @include('inventory::entries.partials.payment-type-field')
                </div>
            </div>
        </section>

        @if ($source)
            @include('inventory::entries.partials.return-items', ['cashReturnLabel' => 'Cash refunded'])
            @include('inventory::entries.partials.form-actions')
        @else
            <p class="small text-body-secondary bg-body-tertiary rounded p-3 mb-0">Select an invoice to load its returnable items.</p>
            <div class="app-form-actions border-top pt-3 mt-4">
                <a class="btn btn-warning" href="{{ route($routePrefix.'.index', $routeParameters) }}">Cancel</a>
            </div>
        @endif
    </form>
@endsection
