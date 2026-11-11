{
    "results": [
        @foreach ($records as $invoice)
            @php
                $serviceLabel = ucfirst($invoice->service);
                $labelParts = array_filter(
                    [$invoice->invoice_no, $invoice->patient_name, $invoice->mobile_no],
                    fn ($value): bool => filled($value),
                );
                $labelParts[] = $serviceLabel;
                $option = [
                    ...(array) $invoice,
                    'id' => $invoice->service.':'.$invoice->source_id,
                    'text' => implode(' — ', $labelParts),
                    'service_label' => $serviceLabel,
                ];
            @endphp
            @json($option)@unless ($loop->last),@endunless
        @endforeach
    ],
    "pagination": {
        "more": @json($records->hasMorePages())
    }
}
