

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Register the patient and their contact details at the selected branch.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>

        <?php echo $__env->make('hospital::patients.fields', ['fieldPrefix' => 'patient_'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php echo $__env->make('core::partials.form-actions', ['model' => $patient, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\patients\form.blade.php ENDPATH**/ ?>