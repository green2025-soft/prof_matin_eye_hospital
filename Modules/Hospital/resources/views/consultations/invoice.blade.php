@extends('hospital::invoices.show')

@section('invoice-content')
    <div class="table-responsive">
        <table class="table app-clinical-invoice-table">
            <caption class="visually-hidden">Consultation and saved consultation fee</caption>
            <thead>
                <tr>
                    <th class="app-clinical-invoice-number" scope="col">No.</th>
                    <th scope="col">Consultation</th>
                    <th class="app-clinical-invoice-amount text-end" scope="col">Fee (Tk)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lines as $line)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-break">{{ $line['name'] }}</td>
                        <td class="text-end text-nowrap">{{ $line['amount'] }}</td>
                    </tr>
                @empty
                    <tr><td class="text-center text-body-secondary" colspan="3">No consultation</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="app-clinical-invoice-summary">
        @include('hospital::invoices.notes', ['wordsLabel' => 'Amount in words'])
        <section class="app-clinical-invoice-totals" aria-labelledby="clinical-invoice-totals-title">
            <h2 class="app-clinical-invoice-section-title" id="clinical-invoice-totals-title">Consultation payment summary</h2>
            <dl class="mb-0">
                <div><dt>Consultation fee</dt><dd>{{ $amount }}</dd></div>
                <div><dt>Discount</dt><dd>{{ $discount }}</dd></div>
                <div class="app-clinical-invoice-total"><dt>{{ $creditAmount !== '0.00' || $collectedAmount !== '0.00' ? 'Original payable' : 'Payable' }}</dt><dd>{{ $payable }}</dd></div>
                <div><dt>{{ $creditAmount !== '0.00' || $collectedAmount !== '0.00' ? 'Original paid' : 'Paid' }}</dt><dd>{{ $paid }}</dd></div>
                <div><dt>{{ $creditAmount !== '0.00' || $collectedAmount !== '0.00' ? 'Original due' : 'Due' }}</dt><dd>{{ $due }}</dd></div>
            </dl>
            @include('hospital::invoices.balance')
        </section>
    </div>
@endsection
