@php
    $productType = request()->attributes->get('pharmacy_product_type', 'medicine');
@endphp
@extends('core::layouts.app')

@section('title', ucfirst($productType).' Damage / Wastage')

@section('content')
    @include('core::partials.heading', ['title' => 'New '.ucfirst($productType).' Damage / Wastage'])

    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST" data-pharmacy-entry data-type="damage" data-vat-enabled="0">
        @csrf
        <input id="pharmacy_product_type" name="product_type" type="hidden" value="{{ $productType }}">

        <div class="row g-3 mb-3">
            <div class="col-sm-6 col-lg-3">
                @include('pharmacy::entries.partials.date-field')
            </div>
            <div class="col-lg-6">
                <label class="form-label" for="reason">Reason <span class="text-danger" aria-hidden="true">*</span></label>
                <input class="{{ inputClass('reason') }}" id="reason" name="reason" type="text" value="{{ getValue('reason', $entry) }}" maxlength="500" required {{ inputAttrs('reason') }}>
                @error('reason')
                    <div class="invalid-feedback d-block" id="reason-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
            <h2 class="h6 mb-0">Items</h2>
            <button class="btn btn-outline-primary btn-sm" type="button" data-pharmacy-add-line>
                <i class="fa-solid fa-plus me-1" aria-hidden="true"></i>Add item
            </button>
        </div>
        @error('lines')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div data-pharmacy-lines>
            @foreach (($lineInputs ?: [['quantity' => 1]]) as $index => $values)
                @include('pharmacy::wastage.item-line', [
                    'lineIndex' => $index,
                    'lineValues' => $values,
                    'variant' => $variants->get($values['variant_id'] ?? null),
                ])
            @endforeach
        </div>
        <template data-pharmacy-line-template>
            @include('pharmacy::wastage.item-line', [
                'lineIndex' => '__INDEX__',
                'lineValues' => ['quantity' => 1],
                'variant' => null,
            ])
        </template>

        <div class="row g-3 mt-1">
            <div class="col-lg-6">
                @include('pharmacy::entries.partials.notes-field')
            </div>
        </div>
        @include('pharmacy::entries.partials.form-actions')
    </form>
@endsection
