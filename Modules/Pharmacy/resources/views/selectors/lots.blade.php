@php
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
@endphp
@json($response)
