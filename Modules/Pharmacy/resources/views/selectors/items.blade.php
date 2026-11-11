@php
    $results = [];

    foreach ($records as $variant) {
        $product = $variant->product;
        $description = $variant->description();
        $units = [];

        foreach ($product->units as $productUnit) {
            $unit = $productUnit->unit;
            $units[] = [
                'id' => $productUnit->id,
                'unit_id' => $productUnit->unit_id,
                'name' => $unit->symbol ?: $unit->name,
                'symbol' => $unit->symbol,
                'factor' => $productUnit->factor,
            ];
        }

        $results[] = [
            'id' => $variant->id,
            'product_id' => $variant->product_id,
            'name' => $product->name.' / '.$description,
            'description' => $description,
            'product_name' => $product->name,
            'sku' => $variant->sku,
            'available_quantity' => (string) ($balances->get($variant->id) ?? 0),
            'purchase_price' => $product->purchase_price,
            'sale_price' => $product->sale_price,
            'units' => $units,
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
