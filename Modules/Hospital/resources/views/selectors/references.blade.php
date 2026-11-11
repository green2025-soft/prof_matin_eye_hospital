{
    "results": [
        @foreach ($records as $reference)
            {
                "id": @json($reference->id),
                "reference_id": @json($reference->reference_id),
                "name": @json($reference->name),
                "contact_no": @json($reference->contact_no),
                "text": @json($reference->reference_id.' — '.$reference->name.' ('.$reference->contact_no.')')
            }@unless ($loop->last),@endunless
        @endforeach
    ],
    "pagination": {
        "more": @json($records->hasMorePages())
    }
}
