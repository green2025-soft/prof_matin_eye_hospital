{
    "results": [
        @foreach ($records as $doctor)
            {
                "id": @json($doctor->id),
                "doctor_id": @json($doctor->doctor_id),
                "name": @json($doctor->name),
                "consultation_fee": @json($doctor->consultation_fee),
                "follow_up_fee": @json($doctor->follow_up_fee),
                "text": @json(($doctor->doctor_id ?: $doctor->id).' — '.$doctor->name)
            }@unless ($loop->last),@endunless
        @endforeach
    ],
    "pagination": {
        "more": @json($records->hasMorePages())
    }
}
