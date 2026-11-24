<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <form class="d-flex flex-wrap gap-2 mb-3" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Hospital settings sections">
        <button class="btn btn-sm <?php echo e($activeHospitalSection === 'patient' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="section" value="patient" aria-pressed="<?php echo e($activeHospitalSection === 'patient' ? 'true' : 'false'); ?>">Patient</button>
        <button class="btn btn-sm <?php echo e($activeHospitalSection === 'doctor' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="section" value="doctor" aria-pressed="<?php echo e($activeHospitalSection === 'doctor' ? 'true' : 'false'); ?>">Doctor</button>
        <button class="btn btn-sm <?php echo e($activeHospitalSection === 'reference' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="section" value="reference" aria-pressed="<?php echo e($activeHospitalSection === 'reference' ? 'true' : 'false'); ?>">Reference</button>
        <button class="btn btn-sm <?php echo e($activeHospitalSection === 'appointment' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="section" value="appointment" aria-pressed="<?php echo e($activeHospitalSection === 'appointment' ? 'true' : 'false'); ?>">Appointment</button>
        <button class="btn btn-sm <?php echo e($activeHospitalSection === 'consultation' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="section" value="consultation" aria-pressed="<?php echo e($activeHospitalSection === 'consultation' ? 'true' : 'false'); ?>">Consultation</button>
        <button class="btn btn-sm <?php echo e($activeHospitalSection === 'investigation' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="section" value="investigation" aria-pressed="<?php echo e($activeHospitalSection === 'investigation' ? 'true' : 'false'); ?>">Investigation</button>
        <button class="btn btn-sm <?php echo e($activeHospitalSection === 'operation' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="section" value="operation" aria-pressed="<?php echo e($activeHospitalSection === 'operation' ? 'true' : 'false'); ?>">Operation</button>
        <button class="btn btn-sm <?php echo e($activeHospitalSection === 'refund' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="section" value="refund" aria-pressed="<?php echo e($activeHospitalSection === 'refund' ? 'true' : 'false'); ?>">Refund</button>
        <button class="btn btn-sm <?php echo e($activeHospitalSection === 'due_collection' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="section" value="due_collection" aria-pressed="<?php echo e($activeHospitalSection === 'due_collection' ? 'true' : 'false'); ?>">Due Collection</button>
        <?php if($smsNotifications !== []): ?>
            <button class="btn btn-sm <?php echo e($activeHospitalSection === 'sms' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="section" value="sms" aria-pressed="<?php echo e($activeHospitalSection === 'sms' ? 'true' : 'false'); ?>">SMS</button>
        <?php endif; ?>
    </form>

    <?php echo $__env->make('hospital::settings.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\settings\index.blade.php ENDPATH**/ ?>