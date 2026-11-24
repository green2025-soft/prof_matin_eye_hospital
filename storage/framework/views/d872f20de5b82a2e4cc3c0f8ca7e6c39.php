<?php $__currentLoopData = ['status' => 'success', 'success' => 'success', 'error' => 'error', 'warning' => 'warning', 'info' => 'info']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(session()->has($key)): ?>
        <span hidden data-toast-message="<?php echo e(session($key)); ?>" data-toast-type="<?php echo e($type); ?>"></span>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php
    $validationErrors = isset($errors) ? clone $errors->getBag('default') : null;
    $recordErrors = $validationErrors?->get('record') ?? [];
    $validationErrors?->forget('record');
?>
<?php $__currentLoopData = $recordErrors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <span hidden data-toast-message="<?php echo e($error); ?>" data-toast-type="error"></span>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php if($validationErrors?->any()): ?>
    <div class="alert alert-danger" id="validation-errors" role="alert">
        <p class="fw-semibold mb-2"><i class="fa-solid fa-circle-exclamation me-2" aria-hidden="true"></i>Please check the following:</p>
        <ul class="mb-0 ps-3">
            <?php $__currentLoopData = $validationErrors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\app\Providers/../../resources/views/partials/feedback.blade.php ENDPATH**/ ?>