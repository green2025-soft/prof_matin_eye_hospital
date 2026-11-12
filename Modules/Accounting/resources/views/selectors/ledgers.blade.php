@php
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
@endphp
@json($response)
