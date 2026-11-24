<?php
    $results = [];

    foreach ($records as $lot) {
        $batchNumber = $lot->batch_no ?: 'No batch';
        $expiryDate = $lot->expiry_date?->format('Y-m-d') ?? 'No expiry';

        $results[] = [
            'id' => $lot->id,
            'name' => '#'.$lot->id.' / '.$batchNumber.' / '.$expiryDate.' / Qty '.$lot->remaining_quantity,
        ];
    }

    $response = [
        'results' => $results,
        'pagination' => [
            'more' => $records->hasMorePages(),
        ],
    ];
?>
<?php echo json_encode($response, 15, 512) ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\selectors\lots.blade.php ENDPATH**/ ?>