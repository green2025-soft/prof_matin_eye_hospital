@extends('hospital::invoices.show')

@section('invoice-notes')
    <h2 class="app-clinical-invoice-section-title">Operation details</h2>
    <p class="app-clinical-invoice-note">Operation date: {{ $operationDate ?? '-' }}</p>
    <p class="app-clinical-invoice-note">Patient type: {{ $operationPatientTypeLabel ?? '-' }}</p>
    @if (filled($consultantName))
        <p class="app-clinical-invoice-note">Consultant: {{ $consultantName }}</p>
    @endif
@endsection

@section('invoice-content')
    <div class="table-responsive">
        <table class="table app-clinical-invoice-table">
            <caption class="visually-hidden">Operation with eye and saved charge</caption>
            <thead>
                <tr>
                    <th class="app-clinical-invoice-number" scope="col">No.</th>
                    <th scope="col">Operation</th>
                    <th class="app-clinical-invoice-eye" scope="col">Eye</th>
                    <th class="app-clinical-invoice-amount text-end" scope="col">Amount (Tk)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lines as $line)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-break">{{ $line['name'] }}</td>
                        <td>{{ $line['eye'] ?? '-' }}</td>
                        <td class="text-end text-nowrap">{{ $line['amount'] }}</td>
                    </tr>
                @empty
                    <tr><td class="text-center text-body-secondary" colspan="4">No operation</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="app-clinical-invoice-summary">
        @include('hospital::invoices.notes', ['wordsLabel' => 'Amount in words'])
        <section class="app-clinical-invoice-totals" aria-labelledby="clinical-invoice-totals-title">
            <h2 class="app-clinical-invoice-section-title" id="clinical-invoice-totals-title">Operation payment summary</h2>
            <dl class="mb-0">
                <div><dt>Operation charge</dt><dd>{{ $amount }}</dd></div>
                <div><dt>Discount</dt><dd>{{ $discount }}</dd></div>
                <div class="app-clinical-invoice-total"><dt>{{ $creditAmount !== '0.00' || $collectedAmount !== '0.00' ? 'Original payable' : 'Payable' }}</dt><dd>{{ $payable }}</dd></div>
                <div><dt>{{ $creditAmount !== '0.00' || $collectedAmount !== '0.00' ? 'Original paid' : 'Paid' }}</dt><dd>{{ $paid }}</dd></div>
                <div><dt>{{ $creditAmount !== '0.00' || $collectedAmount !== '0.00' ? 'Original due' : 'Due' }}</dt><dd>{{ $due }}</dd></div>
            </dl>
            @include('hospital::invoices.balance')
        </section>
    </div>
@endsection
