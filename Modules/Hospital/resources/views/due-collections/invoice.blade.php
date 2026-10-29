@extends('hospital::invoices.show')

@section('invoice-content')
    <div class="table-responsive">
        <table class="table app-clinical-invoice-table">
            <caption class="visually-hidden">Due payment received against the original clinical invoice</caption>
            <thead>
                <tr>
                    <th scope="col">Service</th>
                    <th scope="col">Original invoice</th>
                    <th scope="col">Payment type</th>
                    <th class="app-clinical-invoice-amount text-end" scope="col">Received (Tk)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $sourceServiceLabel }}</td>
                    <td class="text-break">{{ $originalInvoiceNumber }}</td>
                    <td class="text-break">{{ $collectionPaymentType ?? '-' }}</td>
                    <td class="text-end text-nowrap">{{ $collectionAmount }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="app-clinical-invoice-summary">
        @include('hospital::invoices.notes', ['wordsLabel' => 'Received amount in words'])
        <section class="app-clinical-invoice-totals" aria-labelledby="clinical-invoice-totals-title">
            <h2 class="app-clinical-invoice-section-title" id="clinical-invoice-totals-title">Collection receipt</h2>
            <dl class="mb-0">
                <div class="app-clinical-invoice-total"><dt>Received amount</dt><dd>{{ $collectionAmount }}</dd></div>
                <div><dt>Payment type</dt><dd>{{ $collectionPaymentType ?? '-' }}</dd></div>
            </dl>
            <h3 class="app-clinical-invoice-section-title mt-3">Current invoice balance</h3>
            <dl class="mb-0">
                <div class="app-clinical-invoice-total"><dt>Current payable</dt><dd>{{ $remainingPayable }}</dd></div>
                <div><dt>Net paid</dt><dd>{{ $remainingPaid }}</dd></div>
                <div><dt>Current due</dt><dd>{{ $remainingDue }}</dd></div>
            </dl>
        </section>
    </div>
@endsection
