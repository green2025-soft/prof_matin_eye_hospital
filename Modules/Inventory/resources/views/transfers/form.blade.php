@extends('core::layouts.app')

@section('title', ucfirst(request()->attributes->get('inventory_product_type', 'medicine')).' Branch Transfer')

@section('content')
    @include('core::partials.heading', ['title' => 'New '.ucfirst(request()->attributes->get('inventory_product_type', 'medicine')).' Branch Transfer'])

    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST" data-inventory-entry data-type="transfer" data-vat-enabled="0">
        @csrf
        <input id="inventory_product_type" name="product_type" type="hidden" value="{{ request()->attributes->get('inventory_product_type', 'medicine') }}">

        <div class="row g-3 mb-3">
            <div class="col-sm-6 col-lg-3">
                @include('inventory::entries.partials.date-field')
            </div>
            <div class="col-lg-6">
                <label class="form-label" for="destination_branch_id">Destination branch <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="{{ inputClass('destination_branch_id', 'form-select') }}" id="destination_branch_id" name="destination_branch_id" required {{ inputAttrs('destination_branch_id') }}>
                    <option value="">Select destination</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" @selected((string) getValue('destination_branch_id') === (string) $branch->id)>{{ $branch->name }}</option>
                    @endforeach
                </select>
                @error('destination_branch_id')
                    <div class="invalid-feedback d-block" id="destination_branch_id-error">{{ $message }}</div>
                @enderror
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
            @foreach (($lineInputs ?: [['quantity' => 1]]) as $index => $values)
                @include('inventory::transfers.item-line', [
                    'lineIndex' => $index,
                    'lineValues' => $values,
                    'variant' => $variants->get($values['variant_id'] ?? null),
                ])
            @endforeach
        </div>
        <template data-inventory-line-template>
            @include('inventory::transfers.item-line', [
                'lineIndex' => '__INDEX__',
                'lineValues' => ['quantity' => 1],
                'variant' => null,
            ])
        </template>

        <div class="row g-3 mt-1">
            <div class="col-lg-6">
                <label class="form-label" for="reason">Reason <span class="text-danger" aria-hidden="true">*</span></label>
                <input class="{{ inputClass('reason') }}" id="reason" name="reason" type="text" value="{{ getValue('reason', $entry) }}" maxlength="500" required {{ inputAttrs('reason') }}>
                @error('reason')
                    <div class="invalid-feedback d-block" id="reason-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6">
                @include('inventory::entries.partials.notes-field')
            </div>
        </div>
        @include('inventory::entries.partials.form-actions')
    </form>
@endsection
