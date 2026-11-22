@extends('hospital::invoices.show')

@php($hasAdjustments = $creditAmount !== '0.00' || $collectedAmount !== '0.00')

@section('invoice-notes')
    <h2 class="app-clinical-invoice-section-title">Operation details</h2>
    <p class="app-clinical-invoice-note">Operation date: {{ $operationDate ?? '-' }}</p>
    <p class="app-clinical-invoice-note">Patient type: {{ $operationPatientTypeLabel ?? '-' }}</p>
    @if (filled($consultantName))
        <p class="app-clinical-invoice-note">Consultant: {{ $consultantName }}</p>
    @endif
@endsection

@section('invoice-content')
    <div class="table-responsive invoice-body-font">
        <table class="table app-invoice-table">

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
                <tr>
                     <td colspan="2">
                        <div class="app-invoice-note-paid text-start">
                             <div class="note-title">Remarks : {{ $remarks??'' }}</div>
                            <div class="app-invoice-paid">{{ $status }}</div>
                        </div>
                     </td>
                     <td colspan="2" class="p-0">
                        <table class="table m-0 b-0 ">
                            <tbody>
                                <tr>
                                    <th class="text-start">Total Amount</th>
                                    <th>:</th>
                                    <th class="text-end">{{ $amount }}</th>
                                </tr>
                                 <tr>
                                    <th class="text-start">Discount</th>
                                    <th>:</th>
                                    <th class="text-end">{{ $discount }}</th>
                                </tr>

                                 <tr>
                                    <th class="text-start">Sub Total</th>
                                    <th>:</th>
                                    <th class="text-end">{{ $payable }}</th>
                                </tr>
                                <tr>
                                    <th class="text-start">Paid Amount</th>
                                    <th>:</th>
                                    <th class="text-end">{{ $paid }}</th>
                                </tr>
                                <tr>
                                    <th class="text-start">Due Amount</th>
                                    <th>:</th>
                                    <th class="text-end">{{ $due }}</th>
                                </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="app-invoice-bottom-info">
            <p><strong>In Words :</strong> &nbsp; {{amountInWords($paid) }}</p>
        </div>
    </div>

@endsection
