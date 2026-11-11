{
    "results": [
        @foreach ($records as $patient)
            @include('hospital::selectors.patient', ['birthDate' => $patient->date_of_birth?->toDateString()])
            @unless ($loop->last),@endunless
        @endforeach
    ],
    "pagination": {
        "more": @json($records->hasMorePages())
    }
}
