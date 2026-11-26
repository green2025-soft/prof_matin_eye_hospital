@extends('hospital::invoices.show')

@section('invoice-content')

    <div class="table-responsive invoice-body-font">
        <table class="table app-invoice-table">

            <thead>
                <tr>
                    <th scope="col">Service</th>
                    <th scope="col">Invoice</th>
                    {{-- <th scope="col">Payment type</th> --}}
                    <th scope="col" class="text-end">Discount</th>
                    <th class="app-clinical-invoice-amount text-end" scope="col">Amount</th>
                </tr>
            </thead>
            <tbody>

                @php
                $invSubTotal = $objData->service;

                 @endphp
                <tr>
                    <td>{{ $sourceServiceLabel }}</td>
                    <td class="text-break">{{ $originalInvoiceNumber }}</td>
                    {{-- <td class="text-break">{{ $collectionPaymentType ?? '-' }}</td> --}}
                    <td class="text-break text-end">{{ $objData->discount  }}</td>
                    <td class="text-end text-nowrap">{{ $collectionAmount }}</td>
                </tr>
                {{-- <td colspan="2">
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
                                <th class="text-end">{{ $collectionAmount }}</th>
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
                </td> --}}
            </tbody>
        </table>
        <div class="app-invoice-bottom-info">
            <p><strong>In Words :</strong> &nbsp; {{amountInWords($collectionAmount) }}</p>
        </div>
    </div>

@endsection
