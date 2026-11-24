<header class="app-clinical-invoice-header">
    <div class="app-clinical-invoice-brand">
        <?php if($logoUrl): ?>
            <img class="app-clinical-invoice-logo" src="<?php echo e($logoUrl); ?>" alt="" width="76" height="76">
        <?php endif; ?>
        <div>
            <h2 class="app-clinical-invoice-company"><?php echo e($company['name']); ?></h2>
            <?php if($company['branch_name'] ?? ''): ?>
                <p class="fw-semibold mb-1"><?php echo e($company['branch_name']); ?></p>
            <?php endif; ?>
            <?php if($company['address'] ?? ''): ?>
                <p class="app-clinical-invoice-address"><?php echo e($company['address']); ?></p>
            <?php endif; ?>
            <div class="app-clinical-invoice-contact">
                <?php if($company['phone'] ?? ''): ?>
                    <span>Phone: <?php echo e($company['phone']); ?></span>
                <?php endif; ?>
                <?php if($company['email'] ?? ''): ?>
                    <span><?php echo e($company['email']); ?></span>
                <?php endif; ?>
                <?php if($company['tax_id'] ?? ''): ?>
                    <span>Tax ID: <?php echo e($company['tax_id']); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="app-clinical-invoice-title-row">
        <div>
            <h1 id="pharmacy-invoice-title" class="text-center"><?php echo e($invoiceTitle); ?></h1>
            <p class="fw-semibold mb-0 text-center" style="font-size: 16px"><?php echo e($entry->number); ?></p>
        </div>

    </div>

</header>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Pharmacy\resources/views/entries/partials/invoice-header.blade.php ENDPATH**/ ?>