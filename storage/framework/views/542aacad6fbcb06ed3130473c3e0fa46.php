{
    "id": <?php echo json_encode($patient->id, 15, 512) ?>,
    "patient_id": <?php echo json_encode($patient->patient_id, 15, 512) ?>,
    "name": <?php echo json_encode($patient->name, 15, 512) ?>,
    "mobile_no": <?php echo json_encode($patient->mobile_no, 15, 512) ?>,
    "age": <?php echo json_encode($patient->age, 15, 512) ?>,
    "age_months": <?php echo json_encode($patient->age_months, 15, 512) ?>,
    "age_days": <?php echo json_encode($patient->age_days, 15, 512) ?>,
    "date_of_birth": <?php echo json_encode($birthDate, 15, 512) ?>,
    "text": <?php echo json_encode($patient->patient_id.' — '.$patient->name.' — '.$patient->mobile_no, 15, 512) ?>
}
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\selectors\patient.blade.php ENDPATH**/ ?>