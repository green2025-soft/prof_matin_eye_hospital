@extends('hospital::invoices.show')

@php
    $showRoom = $sourceService === 'investigation';
    $showEye = $sourceService !== 'consultation';
    $columnCount = 3 + (int) $showRoom + (int) $showEye;
@endphp

@section('invoice-content')
    <div class="table-responsive">
        <table class="table app-clinical-invoice-table">
            <caption class="visually-hidden">Refunded {{ strtolower($sourceServiceLabel) }} items and their selected net values</caption>
            <thead>
                <tr>
                    <th class="app-clinical-invoice-number" scope="col">No.</th>
                    <th scope="col">Refunded {{ $sourceServiceLabel }}</th>
                    @if ($showRoom)
                        <th class="app-clinical-invoice-room" scope="col">Room</th>
                    @endif
                    @if ($showEye)
                        <th class="app-clinical-invoice-eye" scope="col">Eye</th>
                    @endif
                    <th class="app-clinical-invoice-amount text-end" scope="col">Selected value (Tk)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lines as $line)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-break">{{ $line['name'] }}</td>
                        @if ($showRoom)
                            <td>{{ filled($line['room']) ? $line['room'] : '-' }}</td>
                        @endif
                        @if ($showEye)
                            <td>{{ $line['eye'] ?? '-' }}</td>
                        @endif
                        <td class="text-end text-nowrap">{{ $line['amount'] }}</td>
                    </tr>
                @empty
                    <tr><td class="text-center text-body-secondary" colspan="{{ $columnCount }}">No refunded items</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="row g-4 app-clinical-invoice-summary">
        @include('hospital::invoices.notes', ['wordsLabel' => 'Refund value in words'])
        <section class="col-12 col-sm-6 app-clinical-invoice-totals" aria-labelledby="clinical-invoice-totals-title">
            <h2 class="app-clinical-invoice-section-title" id="clinical-invoice-totals-title">Refund summary</h2>
            <dl class="mb-0">
                <div class="app-clinical-invoice-total"><dt>Selected value</dt><dd>{{ $refundCreditAmount }}</dd></div>
                <div><dt>Due adjustment</dt><dd>{{ $refundDueAdjustment }}</dd></div>
                <div class="app-clinical-invoice-refund-total"><dt>Cash refund</dt><dd>{{ $refundAmount }}</dd></div>
            </dl>
        </section>
    </div>
@endsection
