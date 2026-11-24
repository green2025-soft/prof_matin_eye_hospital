<?php
    $response = [
        'results' => $results,
        'pagination' => [
            'more' => $records->hasMorePages(),
        ],
    ];
?>
<?php echo json_encode($response, 15, 512) ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\selectors\items.blade.php ENDPATH**/ ?>