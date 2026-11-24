<?php if($layout['logoUrl']): ?>
    <img class="app-brand-logo" src="<?php echo e($layout['logoUrl']); ?>" alt="" width="36" height="36">
<?php else: ?>
    <span class="app-brand-icon" aria-hidden="true"><?php echo e($layout['appInitial']); ?></span>
<?php endif; ?>
<span class="app-brand-copy">
    <strong><?php echo e($layout['appName']); ?></strong>
    
</span>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\partials\brand.blade.php ENDPATH**/ ?>