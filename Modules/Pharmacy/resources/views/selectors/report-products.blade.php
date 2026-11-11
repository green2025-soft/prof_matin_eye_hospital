@php
    $results = [];

    foreach ($records as $variant) {
        $results[] = [
            'id' => $variant->id,
            'name' => $variant->product->name.' / '.$variant->sku.' / Stock: '.(int) $variant->stock_quantity.' Pcs',
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
