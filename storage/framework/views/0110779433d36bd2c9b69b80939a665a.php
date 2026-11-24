<a class="<?php echo \Illuminate\Support\Arr::toCssClasses(['app-nav-link', 'active' => $active]); ?>" href="<?php echo e($url); ?>" title="<?php echo e($label); ?>"
    <?php if($active): ?> aria-current="page" <?php endif; ?>>
    <span class="app-nav-icon"><i class="fa-solid fa-<?php echo e($icon); ?>" aria-hidden="true"></i></span>
    <span class="app-nav-copy"><?php echo e($label); ?></span>
</a>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\partials\navigation-link.blade.php ENDPATH**/ ?>