<section class="app-form-section mt-3" aria-labelledby="inventory-products-heading">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
        <h2 class="h6 mb-0" id="inventory-products-heading">Products</h2>
        <span class="small text-body-secondary"><span data-inventory-line-count>{{ $hasOldLines ? count($lineInputs) : min(100, $savedLines->count()) }}</span> items</span>
    </div>
    <details class="app-inventory-shortcuts mb-2">
        <summary>Keyboard shortcuts</summary>
        <ul class="row g-2 list-unstyled small mb-0 mt-1">
            <li class="col-sm-6"><kbd>F2</kbd> Add product</li>
            @if ($pharmacyPurchase)
                <li class="col-sm-6"><kbd>F4</kbd> Unit: use arrows or type its name</li>
                <li class="col-sm-6"><span><kbd>Alt</kbd> + <kbd>B</kbd></span> Select Box</li>
                <li class="col-sm-6"><span><kbd>Alt</kbd> + <kbd>P</kbd></span> Select Pcs</li>
            @endif
            <li class="col-sm-6"><span><kbd>Alt</kbd> + <kbd>A</kbd></span> Add / update item</li>
            <li class="col-sm-6"><kbd>Enter</kbd> Next field; last field adds the item</li>
            <li class="col-sm-6"><span><kbd>Shift</kbd> + <kbd>Enter</kbd></span> Previous field</li>
            @if ($pharmacyPurchase)
                <li class="col-sm-6"><span><kbd>Alt</kbd> + <kbd>U</kbd></span> Edit current pack</li>
            @endif
            <li class="col-sm-6"><span><kbd>Alt</kbd> + <kbd>Delete</kbd></span> Remove current item</li>
            <li class="col-sm-6"><span><kbd>Ctrl</kbd> + <kbd>Enter</kbd></span> Apply item and save</li>
        </ul>
    </details>
    <section class="app-inventory-item-editor border rounded p-2 p-md-3 mt-2" aria-labelledby="inventory-product-picker-label">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-8 col-lg-5 col-xl-4">
                <label class="form-label" id="inventory-product-picker-label" for="inventory_product_picker">Add product</label>
                <select class="{{ inputClass('lines', 'form-select') }}" id="inventory_product_picker"
                data-ajax-select
                data-url="{{
                route('inventory.items.options',
                ['product_type' => request()->attributes->get('inventory_product_type', 'medicine'), 'purpose' => 'purchase']) }}"
                 data-placeholder="Search product name or SKU to add"
                 aria-keyshortcuts="F2" {{ inputAttrs('lines', 'inventory_product_picker', 'inventory-product-feedback') }}>
                    <option value=""></option>
                </select>
                <p class="small text-body-secondary mt-2 mb-0" id="inventory-product-feedback" role="status" aria-live="polite">Select a product to start.</p>
                @error('lines')
                    <div class="invalid-feedback d-block" id="inventory_product_picker-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-md-4 col-lg-7 col-xl-8 text-md-end text-break" data-inventory-active-product hidden>
                <h3 class="small text-body-secondary mb-1">Add item</h3>
                <strong class="d-block app-inventory-entry-name"></strong>
                <small class="d-block text-body-secondary"></small>

            </div>
        </div>
        <div class="mt-2" data-inventory-editor-panel hidden>
            <div data-inventory-item-editor></div>
            <div class="d-flex flex-wrap gap-2 mt-2">
                <button class="btn btn-primary" type="button" data-inventory-commit-item aria-keyshortcuts="Alt+A" title="Add / update item (Alt+A)" disabled>Add item</button>
                <button class="btn btn-warning" type="button" data-inventory-cancel-item hidden>Cancel edit</button>
            </div>
        </div>
    </section>
    <div hidden data-inventory-line-storage>
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
            @if ($pharmacyPurchase)
                @include('inventory::purchases.medicine-line')
            @else
                @include('inventory::purchases.optic-line')
            @endif
        @endforeach
    </div>
    <div class="table-responsive app-inventory-entry-table border rounded mt-2" role="region" aria-label="Invoice items" tabindex="0">
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
                    @if ($showExpiryColumn)
                        <th scope="col">Expiry</th>
                    @endif
                    <th scope="col" class="text-end">Amount</th>
                    <th scope="col" class="text-end"><span class="visually-hidden">Actions</span></th>
                </tr>
            </thead>
            <tbody data-inventory-item-list>
                <tr data-inventory-empty>
                    <td class="text-center py-4" colspan="{{ $lineColumnCount }}">Added items will appear here.</td>
                </tr>
            </tbody>
        </table>
    </div>
    <template data-inventory-trade-template>
        @if ($pharmacyPurchase)
            @include('inventory::purchases.medicine-line', ['lineIndex' => '__INDEX__', 'lineValues' => [], 'savedLine' => null, 'hasOldLines' => false, 'variant' => null])
        @else
            @include('inventory::purchases.optic-line', ['lineIndex' => '__INDEX__', 'lineValues' => [], 'savedLine' => null, 'hasOldLines' => false, 'variant' => null])
        @endif
    </template>
    <template data-inventory-summary-template>
        <tr>
            <td class="app-inventory-entry-product" data-label="Product">
                <strong class="app-inventory-entry-name"></strong>
                <small class="app-inventory-entry-code"></small>
                <div hidden data-inventory-item-payload></div>
            </td>
            <td class="text-end" data-label="Qty"><span data-inventory-summary-quantity></span></td>
            <td class="text-end" data-label="Rate"><span data-inventory-summary-rate></span></td>
            @if ($showDiscountColumn)
                <td class="text-end" data-label="Discount"><span data-inventory-summary-discount></span></td>
            @endif
            @if ($showVatColumn)
                <td class="text-end" data-label="VAT"><span data-inventory-summary-vat></span></td>
            @endif
            @if ($showExpiryColumn)
                <td data-label="Expiry"><span class="text-nowrap" data-inventory-summary-expiry></span></td>
            @endif
            <td class="text-end fw-semibold" data-label="Amount"><span data-inventory-summary-amount></span></td>
            <td class="text-end app-inventory-entry-actions" data-label="Actions">
                <div class="d-inline-flex gap-1">
                    <button class="btn btn-outline-primary btn-sm app-action-button" type="button" data-inventory-edit-line aria-label="Edit item" title="Edit item">
                        <i class="fa-solid fa-pencil" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm app-action-button" type="button" data-inventory-remove-line aria-label="Remove item" aria-keyshortcuts="Alt+Delete" title="Remove item">
                        <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                    </button>
                </div>
            </td>
        </tr>
    </template>
</section>
