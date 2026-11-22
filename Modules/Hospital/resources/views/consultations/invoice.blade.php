@extends('hospital::invoices.show')

@php($hasAdjustments = $creditAmount !== '0.00' || $collectedAmount !== '0.00')

@section('invoice-content')
    <div class="table-responsive invoice-body-font">
        <table class="table app-invoice-table text-center">
            <thead>
                <tr>
                    <th scope="col" class="text-start">Appointment Doctor Name</th>
                    <th scope="col" class="text-center">Type </th>
                    <th class="app-clinical-invoice-amount text-end" scope="col">Fee (Tk)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-start">{{ $doctor?->name ?? '-' }}</td>
                    <td>{{ (patientTypes()[$objData->patient_type] ?? $objData->patient_type) }}</td>
                    <td class="text-end text-nowrap">{{ $objData->amount }}</td>
                </tr>

                <tr>
                    <td>
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
