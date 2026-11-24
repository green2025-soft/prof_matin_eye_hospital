<?php
    $results = [];
    foreach ($records as $ledger) {
        $results[] = [
            'id' => $ledger->id,
            'name' => $ledger->code.' · '.$ledger->name.($ledger->active ? '' : ' (inactive)'),
        ];
    }
    $response = [
        'results' => $results,
        'pagination' => ['more' => $records->hasMorePages()],
    ];
?>
<?php echo json_encode($response, 15, 512) ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\selectors\ledgers.blade.php ENDPATH**/ ?>