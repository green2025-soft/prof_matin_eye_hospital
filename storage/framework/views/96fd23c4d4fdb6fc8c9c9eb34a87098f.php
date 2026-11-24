<?php
    $history = $prescription->history_details;
    $personal = $history?->personal ?? null;
    $labelledHistory = collect([
        'Drug' => $history?->drug ?? null,
        'Investigation' => $history?->investigation ?? null,
        'Personal' => $history?->personal_notes ?? null,
        'Family' => $history?->family ?? null,
        'Surgical' => $history?->surgical ?? null,
        'Allergic' => $history?->allergic ?? null,
        'Other' => $history?->other ?? null,
    ])->filter('filled');
    $habits = collect([
        'Smoker' => $personal?->smoker ?? false,
        'Ex-smoker' => $personal?->ex_smoker ?? false,
        'Alcoholic' => $personal?->alcoholic ?? false,
        'Tobacco' => $personal?->tobacco ?? false,
    ])->filter()->keys()->join(', ');
?>

<?php if(filled($prescription->history) || $labelledHistory->isNotEmpty() || filled($habits)): ?>
    <section class="min-height100">
        <h3>History :</h3>
        <div class="mb-1">
            <?php if(filled($prescription->history)): ?>
                <?php echo $__env->make('prescription::prints.clinical-lines', [
                    'text' => $prescription->history,
                    'details' => $historyNotes,
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php $__currentLoopData = $labelledHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p class="app-prescription-text mb-0"><?php echo e($label); ?>: <?php echo e($value); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if(filled($habits)): ?>
                <p class="app-prescription-text mb-0"><?php echo e($habits); ?></p>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Prescription\resources\views\prints\history.blade.php ENDPATH**/ ?>