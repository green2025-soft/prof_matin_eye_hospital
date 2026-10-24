@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @php($formItems = array_slice(getValue('items', null, $items), 0, 100, true) ?: [['description' => '', 'quantity' => 1, 'unit_amount' => '0.00']])

    @include('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Choose Draft to keep editing or Issued to finalize this invoice. Issued invoices cannot be edited.',
    ])

    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST" data-invoice-editor>
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif

        <section class="app-form-section" aria-labelledby="invoice-details-heading">
            <h2 class="app-form-section-title" id="invoice-details-heading">Invoice details</h2>
            <div class="row g-3">
                <div class="col-md-6 col-xl-5">
                    <label class="form-label" for="tenant-id">Workspace</label>
                    @if ($invoice->exists)
                        <input class="form-control" id="tenant-id" type="text" value="{{ $invoice->tenant->name }}" readonly>
                    @else
                        <input
                            class="{{ inputClass('tenant_id') }}"
                            id="tenant-id"
                            name="tenant_id"
                            type="text"
                            inputmode="numeric"
                            list="invoice-tenants"
                            value="{{ getValue('tenant_id') }}"
                            required
                            {{ inputAttrs('tenant_id', 'tenant-id', 'tenant-id-help') }}
                        >
                        <datalist id="invoice-tenants">
                            @foreach ($tenantSuggestions as $tenant)
                                <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                            @endforeach
                        </datalist>
                        <div class="form-text" id="tenant-id-help">
                            Choose a suggestion or enter a workspace ID from tenant management.
                        </div>
                        @error('tenant_id')
                            <div class="invalid-feedback d-block" id="tenant-id-error">{{ $message }}</div>
                        @enderror
                    @endif
                </div>
                <div class="col-md-3 col-xl-2">
                    <label class="form-label" for="invoice-currency">Currency</label>
                    <input class="form-control" id="invoice-currency" type="text" value="{{ $currency }}" readonly>
                </div>
                <div class="col-md-3 col-xl-3">
                    <label class="form-label" for="due-at">Due date</label>
                    <input
                        class="{{ inputClass('due_at') }}"
                        id="due-at"
                        name="due_at"
                        type="date"
                        value="{{ getValue('due_at', null, $invoice->due_at?->toDateString()) }}"
                        {{ inputAttrs('due_at', 'due-at') }}
                    >
                    @error('due_at')
                        <div class="invalid-feedback d-block" id="due-at-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 col-xl-2">
                    <label class="form-label" for="invoice-status">Status</label>
                    <select class="{{ inputClass('status', 'form-select') }}" id="invoice-status" name="status" required {{ inputAttrs('status', 'invoice-status') }}>
                        <option value="draft" {{ getSelected('draft', 'status', $invoice, 'draft') }}>Draft</option>
                        @can('issue', $invoice)
                            <option value="issued" {{ getSelected('issued', 'status', $invoice, 'draft') }}>Issued</option>
                        @endcan
                    </select>
                    @error('status')
                        <div class="invalid-feedback d-block" id="invoice-status-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </section>

        <section class="app-form-section" aria-labelledby="invoice-items-heading">
            <h2 class="app-form-section-title" id="invoice-items-heading">Line items</h2>
            <p class="app-form-section-note">
                Amounts are in {{ $currency }}. Quantities are whole numbers; totals are calculated when you save.
            </p>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Description</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Unit amount</th>
                            <th scope="col"><span class="visually-hidden">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody data-invoice-items>
                        @foreach ($formItems as $index => $item)
                            @include('core::invoices.item-row', ['index' => $index, 'item' => is_array($item) ? $item : []])
                        @endforeach
                    </tbody>
                </table>
            </div>
            @error('items')
                <div class="invalid-feedback d-block mb-3">{{ $message }}</div>
            @enderror
            <button class="btn btn-outline-secondary btn-sm" type="button" data-invoice-add>
                <i class="fa-solid fa-plus me-2" aria-hidden="true"></i>
                Add item
            </button>
            <template data-invoice-template>
                @include('core::invoices.item-row', ['index' => '__INDEX__', 'item' => []])
            </template>
        </section>

        <section class="app-form-section">
            <label class="form-label" for="notes">Notes</label>
            <textarea class="{{ inputClass('notes') }}" id="notes" name="notes" rows="3" {{ inputAttrs('notes') }}>{{ getValue('notes', $invoice) }}</textarea>
            @error('notes')
                <div class="invalid-feedback d-block" id="notes-error">{{ $message }}</div>
            @enderror
        </section>

        @include('core::partials.form-actions', [
            'model' => $invoice,
            'routePrefix' => 'core.invoices',
            'submitLabel' => 'Save invoice',
        ])
    </form>
@endsection
