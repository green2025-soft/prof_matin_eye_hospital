{
    "id": @json($patient->id),
    "patient_id": @json($patient->patient_id),
    "name": @json($patient->name),
    "mobile_no": @json($patient->mobile_no),
    "age": @json($patient->age),
    "age_months": @json($patient->age_months),
    "age_days": @json($patient->age_days),
    "date_of_birth": @json($birthDate),
    "text": @json($patient->patient_id.' — '.$patient->name.' — '.$patient->mobile_no)
}
