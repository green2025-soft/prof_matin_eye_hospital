<section class="app-form-section" aria-labelledby="return-items-heading">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
        <h2 class="h6 mb-0" id="return-items-heading">Items to return</h2>
        <span class="small text-body-secondary"><span data-pharmacy-return-count>0</span> selected</span>
    </div>
    <?php $__errorArgs = ['lines'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="alert alert-danger"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

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
            <?php
                $savedLines = $entry->exists ? $entry->lines->keyBy('purchase_item_id') : collect();
                $returnQuantities = $savedLines->pluck('base_quantity', 'purchase_item_id');
                $flashedLines = collect($lineInputs);
                // Reuse submitted row keys so validation errors stay with their items.
                $oldLineIndexes = $flashedLines->mapWithKeys(fn ($input, $index) => [(int) ($input['purchase_item_id'] ?? 0) => $index]);
                $nextLineIndex = 0;
                $hasReturnableLines = false;
            ?>
            <?php $__currentLoopData = $source->lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sourceLine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $remainingQuantity = $sourceLine->base_quantity - $sourceLine->returned_base_quantity + ($returnQuantities->get($sourceLine->id) ?? 0);
                ?>
                <?php if($remainingQuantity <= 0) continue; ?>
                <?php
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
                ?>
                <?php echo $__env->make('pharmacy::purchase-returns.item-line', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if (! ($hasReturnableLines)): ?>
                <tr><td class="small text-body-secondary p-3" colspan="7">All items on this invoice have already been returned.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-7">
            <?php echo $__env->make('pharmacy::entries.partials.notes-field', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
                        <dt class="small fw-semibold">Cash received</dt>
                        <dd class="mb-0 text-end" data-pharmacy-return-cash>0.00</dd>
                    </div>
                </dl>
            </div>
            <p class="small text-danger mt-2 mb-0" data-pharmacy-return-payment-note hidden>Choose a payment type for the cash amount.</p>
        </div>
    </div>
</section>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\purchase-returns\items.blade.php ENDPATH**/ ?>