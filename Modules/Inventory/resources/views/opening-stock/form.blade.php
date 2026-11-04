@extends('core::layouts.app')

@section('title', ucfirst(request()->attributes->get('inventory_product_type', 'medicine')).' Opening Stock')

@section('content')
    @include('core::partials.heading', ['title' => 'New '.ucfirst(request()->attributes->get('inventory_product_type', 'medicine')).' Opening Stock'])

    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST" data-inventory-entry data-type="opening" data-vat-enabled="0">
        @csrf
        <input id="inventory_product_type" name="product_type" type="hidden" value="{{ request()->attributes->get('inventory_product_type', 'medicine') }}">

        <div class="row g-3 mb-3">
            <div class="col-sm-6 col-lg-3">
                @include('inventory::entries.partials.date-field')
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
            <h2 class="h6 mb-0">Items</h2>
            <button class="btn btn-outline-primary btn-sm" type="button" data-inventory-add-line>
                <i class="fa-solid fa-plus me-1" aria-hidden="true"></i>Add item
            </button>
        </div>
        @error('lines')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div data-inventory-lines>
            @foreach (($lineInputs ?: [['quantity' => 1, 'unit_price' => '0.00']]) as $index => $values)
                @include('inventory::opening-stock.item-line', [
                    'lineIndex' => $index,
                    'lineValues' => $values,
                    'variant' => $variants->get($values['variant_id'] ?? null),
                ])
            @endforeach
        </div>
        <template data-inventory-line-template>
            @include('inventory::opening-stock.item-line', [
                'lineIndex' => '__INDEX__',
                'lineValues' => ['quantity' => 1, 'unit_price' => '0.00'],
                'variant' => null,
            ])
        </template>

        <div class="row g-3 mt-1">
            <div class="col-lg-6">
                @include('inventory::entries.partials.notes-field')
            </div>
        </div>
        @include('inventory::entries.partials.form-actions')
    </form>
@endsection
