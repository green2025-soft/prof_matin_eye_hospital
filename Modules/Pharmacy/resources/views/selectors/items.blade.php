@php
    $response = [
        'results' => $results,
        'pagination' => [
            'more' => $records->hasMorePages(),
        ],
    ];
@endphp
@json($response)
