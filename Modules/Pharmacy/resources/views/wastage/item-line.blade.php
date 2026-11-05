<div class="border rounded p-3 mb-2" data-pharmacy-line data-index="{{ $lineIndex }}">
    <fieldset class="border-0 p-0 m-0">
        <legend class="visually-hidden">Item {{ $lineIndex }}</legend>
        <div class="row g-2 align-items-end">
            @include('pharmacy::entries.partials.stock-product-fields')
            <div class="col-6 col-lg-2">
                <button class="btn btn-outline-danger w-100" type="button" data-pharmacy-remove-line>
                    Remove
                </button>
            </div>
            @include('pharmacy::entries.partials.stock-lot-field')
        </div>
    </fieldset>
</div>
