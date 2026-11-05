<section class="app-form-section mt-3" aria-labelledby="pharmacy-products-heading">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
        <h2 class="h6 mb-0" id="pharmacy-products-heading">Products</h2>
        <span class="small text-body-secondary"><span data-pharmacy-line-count>{{ $hasOldLines ? count($lineInputs) : min(100, $savedLines->count()) }}</span> items</span>
    </div>
    <details class="app-pharmacy-shortcuts mb-2">
        <summary>Keyboard shortcuts</summary>
        <ul class="row g-2 list-unstyled small mb-0 mt-1">
            <li class="col-sm-6"><kbd>F2</kbd> Add product</li>
            <li class="col-sm-6"><kbd>F4</kbd> Unit: use arrows or type its name</li>
            <li class="col-sm-6"><span><kbd>Alt</kbd> + <kbd>B</kbd></span> Select Box</li>
            <li class="col-sm-6"><span><kbd>Alt</kbd> + <kbd>P</kbd></span> Select Pcs</li>
            <li class="col-sm-6"><span><kbd>Alt</kbd> + <kbd>A</kbd></span> Add / update item</li>
            <li class="col-sm-6"><kbd>Enter</kbd> Next field; last field adds the item</li>
            <li class="col-sm-6"><span><kbd>Shift</kbd> + <kbd>Enter</kbd></span> Previous field</li>
            <li class="col-sm-6"><span><kbd>Alt</kbd> + <kbd>U</kbd></span> Edit current pack</li>
            <li class="col-sm-6"><span><kbd>Alt</kbd> + <kbd>Delete</kbd></span> Remove current item</li>
            <li class="col-sm-6"><span><kbd>Ctrl</kbd> + <kbd>Enter</kbd></span> Apply item and save</li>
        </ul>
    </details>
    <section class="app-pharmacy-item-editor border rounded p-2 p-md-3 mt-2" aria-labelledby="pharmacy-product-picker-label">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-8 col-lg-5 col-xl-4">
                <label class="form-label" id="pharmacy-product-picker-label" for="pharmacy_product_picker">Add product</label>
                <select class="{{ inputClass('lines', 'form-select') }}" id="pharmacy_product_picker" data-ajax-select data-url="{{ route('pharmacy.items.options', ['product_type' => $productType, 'purpose' => 'sale']) }}" data-placeholder="Search product name or SKU to add" aria-keyshortcuts="F2" {{ inputAttrs('lines', 'pharmacy_product_picker', 'pharmacy-product-feedback') }}>
                    <option value=""></option>
                </select>
                <p class="small text-body-secondary mt-2 mb-0" id="pharmacy-product-feedback" role="status" aria-live="polite">Select a product to start.</p>
                @error('lines')
                    <div class="invalid-feedback d-block" id="pharmacy_product_picker-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-md-4 col-lg-7 col-xl-8 text-md-end text-break" data-pharmacy-active-product hidden>
                <h3 class="small text-body-secondary mb-1">Add item</h3>
                <strong class="d-block app-pharmacy-entry-name"></strong>
                <small class="d-block text-body-secondary"></small>
            </div>
        </div>
        <div class="mt-2" data-pharmacy-editor-panel hidden>
            <div data-pharmacy-item-editor></div>
            <div class="d-flex flex-wrap gap-2 mt-2">
                <button class="btn btn-primary" type="button" data-pharmacy-commit-item aria-keyshortcuts="Alt+A" title="Add / update item (Alt+A)" disabled>Add item</button>
                <button class="btn btn-warning" type="button" data-pharmacy-cancel-item hidden>Cancel edit</button>
            </div>
        </div>
    </section>
    <div hidden data-pharmacy-line-storage>
        @foreach (($hasOldLines ? $lineInputs : $savedLines->take(100)) as $index => $row)
            @php
                $lineIndex = $hasOldLines ? $index : $loop->index;
                $lineValues = $hasOldLines ? $row : [];
                $savedLine = $hasOldLines ? $savedLines->get((int) ($lineValues['original_line_id'] ?? 0)) : $row;
                if ($hasOldLines && $savedLine?->variant_id !== (int) ($lineValues['variant_id'] ?? 0)) {
                    $savedLine = null;
                }
                $variant = $hasOldLines ? $variants->get($lineValues['variant_id'] ?? null) : $savedLine->variant;
            @endphp
            @include('pharmacy::sales.line')
        @endforeach
    </div>
    <div class="table-responsive app-pharmacy-entry-table border rounded mt-2" role="region" aria-label="Invoice items" tabindex="0">
        <table class="table app-table align-middle mb-0">
            <caption class="visually-hidden">Saved invoice items. Select Edit to change an item.</caption>
            <thead>
                <tr>
                    <th scope="col">Product</th>
                    <th scope="col" class="text-end">Qty</th>
                    <th scope="col" class="text-end">Rate</th>
                    @if ($showDiscountColumn)
                        <th scope="col" class="text-end">Discount</th>
                    @endif
                    @if ($showVatColumn)
                        <th scope="col" class="text-end">VAT</th>
                    @endif
                    <th scope="col" class="text-end">Amount</th>
                    <th scope="col" class="text-end"><span class="visually-hidden">Actions</span></th>
                </tr>
            </thead>
            <tbody data-pharmacy-item-list>
                <tr data-pharmacy-empty>
                    <td class="text-center py-4" colspan="{{ $lineColumnCount }}">Added items will appear here.</td>
                </tr>
            </tbody>
        </table>
    </div>
    <template data-pharmacy-trade-template>
        @include('pharmacy::sales.line', ['lineIndex' => '__INDEX__', 'lineValues' => [], 'savedLine' => null, 'hasOldLines' => false, 'variant' => null])
    </template>
    <template data-pharmacy-summary-template>
        <tr>
            <td class="app-pharmacy-entry-product" data-label="Product">
                <strong class="app-pharmacy-entry-name"></strong>
                <small class="app-pharmacy-entry-code"></small>
                <div hidden data-pharmacy-item-payload></div>
            </td>
            <td class="text-end" data-label="Qty"><span data-pharmacy-summary-quantity></span></td>
            <td class="text-end" data-label="Rate"><span data-pharmacy-summary-rate></span></td>
            @if ($showDiscountColumn)
                <td class="text-end" data-label="Discount"><span data-pharmacy-summary-discount></span></td>
            @endif
            @if ($showVatColumn)
                <td class="text-end" data-label="VAT"><span data-pharmacy-summary-vat></span></td>
            @endif
            <td class="text-end fw-semibold" data-label="Amount"><span data-pharmacy-summary-amount></span></td>
            <td class="text-end app-pharmacy-entry-actions" data-label="Actions">
                <div class="d-inline-flex gap-1">
                    <button class="btn btn-outline-primary btn-sm app-action-button" type="button" data-pharmacy-edit-line aria-label="Edit item" title="Edit item">
                        <i class="fa-solid fa-pencil" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm app-action-button" type="button" data-pharmacy-remove-line aria-label="Remove item" aria-keyshortcuts="Alt+Delete" title="Remove item">
                        <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                    </button>
                </div>
            </td>
        </tr>
    </template>
</section>
