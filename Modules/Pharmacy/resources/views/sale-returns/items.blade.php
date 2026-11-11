<section class="app-form-section" aria-labelledby="return-items-heading">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
        <h2 class="h6 mb-0" id="return-items-heading">Items to return</h2>
        <span class="small text-body-secondary"><span data-pharmacy-return-count>0</span> selected</span>
    </div>
    @error('lines')<div class="alert alert-danger">{{ $message }}</div>@enderror

    <div class="app-pharmacy-entry-table app-pharmacy-return-table border rounded" role="region" aria-label="Returnable invoice items" tabindex="0">
        <table class="table table-sm app-table align-middle mb-0">
            <caption class="visually-hidden">Enter the return quantity. Original and available quantities are in Pcs; amounts use the original invoice.</caption>
            <thead>
                <tr>
                    <th scope="col">Qty</th>
                    <th scope="col">Product / SKU</th>
                    <th class="text-end" scope="col">Original <small>(Pcs)</small></th>
                    <th class="text-end" scope="col">Available <small>(Pcs)</small></th>
                    <th scope="col">Unit</th>
                    <th class="text-end" scope="col">Rate</th>
                    <th class="text-end" scope="col">Return amount</th>
                </tr>
            </thead>
            <tbody data-pharmacy-lines>
            @php
                $savedLines = $entry->exists ? $entry->lines->keyBy('sale_item_id') : collect();
                $returnQuantities = $savedLines->pluck('base_quantity', 'sale_item_id');
                $flashedLines = collect($lineInputs);
                // Reuse submitted row keys so validation errors stay with their items.
                $oldLineIndexes = $flashedLines->mapWithKeys(fn ($input, $index) => [(int) ($input['sale_item_id'] ?? 0) => $index]);
                $nextLineIndex = 0;
                $hasReturnableLines = false;
            @endphp
            @foreach ($source->lines as $sourceLine)
                @php
                    $remainingQuantity = $sourceLine->base_quantity - $sourceLine->returned_base_quantity + ($returnQuantities->get($sourceLine->id) ?? 0);
                @endphp
                @continue($remainingQuantity <= 0)
                @php
                    $hasReturnableLines = true;
                    $savedLine = $savedLines->get($sourceLine->id);
                    $oldIndex = $hasOldLines ? $oldLineIndexes->get($sourceLine->id) : null;
                    if ($oldIndex === null) {
                        while ($flashedLines->has($nextLineIndex)) {
                            $nextLineIndex++;
                        }
                        $lineIndex = $nextLineIndex++;
                    } else {
                        $lineIndex = $oldIndex;
                    }
                    $lineValues = $oldIndex === null ? [] : $flashedLines->get($oldIndex);
                    $variant = $sourceLine->variant;
                    $returnedAmount = $returnedAmounts->get($sourceLine->id)?->total ?? 0;
                    $remainingAmount = $sourceLine->total - $returnedAmount;
                @endphp
                @include('pharmacy::sale-returns.item-line')
            @endforeach
            @unless ($hasReturnableLines)
                <tr><td class="small text-body-secondary p-3" colspan="7">All items on this invoice have already been returned.</td></tr>
            @endunless
            </tbody>
        </table>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-7">
            @include('pharmacy::entries.partials.notes-field')
            <p class="small text-body-secondary mt-2 mb-0">Set Qty to 0 to skip an item. Original invoice totals apply.</p>
        </div>
        <div class="col-lg-5">
            <div class="border rounded bg-body-tertiary p-2">
                <dl class="mb-0">
                    <div class="d-flex justify-content-between gap-2 py-1">
                        <dt class="small text-body-secondary fw-normal">Invoice credit</dt>
                        <dd class="mb-0 text-end" data-pharmacy-return-total>0.00</dd>
                    </div>
                    <div class="d-flex justify-content-between gap-2 py-1">
                        <dt class="small fw-semibold">Due adjusted</dt>
                        <dd class="mb-0 text-end" data-pharmacy-return-due>0.00</dd>
                    </div>
                    <div class="d-flex justify-content-between gap-2 border-top pt-2 mt-1 fw-bold">
                        <dt class="small fw-semibold">Cash refunded</dt>
                        <dd class="mb-0 text-end" data-pharmacy-return-cash>0.00</dd>
                    </div>
                </dl>
            </div>
            <p class="small text-danger mt-2 mb-0" data-pharmacy-return-payment-note hidden>Choose a payment type for the cash amount.</p>
        </div>
    </div>
</section>
