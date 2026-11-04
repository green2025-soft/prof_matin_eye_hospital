<div class="border rounded p-3 mb-2" data-inventory-line data-index="{{ $lineIndex }}">
    <fieldset class="border-0 p-0 m-0">
        <legend class="visually-hidden">Item {{ $lineIndex }}</legend>
        <div class="row g-2 align-items-end">
            @include('inventory::entries.partials.stock-product-fields')
            <div class="col-6 col-lg-2">
                <button class="btn btn-outline-danger w-100" type="button" data-inventory-remove-line>
                    Remove
                </button>
            </div>
        </div>
    </fieldset>
</div>
