@extends('hospital::invoices.show')

@php($hasAdjustments = $creditAmount !== '0.00' || $collectedAmount !== '0.00')

@section('invoice-notes')
    @if ($deliveryDate !== null)
        <h2 class="app-clinical-invoice-section-title">Delivery date</h2>
        <p class="app-clinical-invoice-note">{{ $deliveryDate }}</p>
    @endif
@endsection

@section('invoice-content')
    <div class="table-responsive">
        <table class="table app-invoice-table text-center">
            <thead>
                <tr>
                  <th>No</th>
                  <th class="text-start">Investigation</th>
                  <th>Room</th>
                  <th>Eye</th>
                  <th class="text-end">Amount (Tk)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lines as $line)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-start">{{ $line['name'] }}</td>
                    <td>{{ filled($line['room']) ? $line['room'] : '-' }}</td>
                    <td>{{ $line['eye'] ?? '-' }}</td>
                    <td class="text-end text-nowrap">{{ $line['amount'] }}</td>
                </tr>
                   @empty
                    <tr>
                        <td class="text-center text-body-secondary" colspan="5">No investigations</td>
                    </tr>
                @endforelse
                <tr>
                    <td colspan="3">
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
                                @if($extraDiscount >0)
                                 <tr>
                                    <th class="text-start">Extra Discount</th>
                                    <th>:</th>
                                    <th class="text-end">{{ $extraDiscount }}</th>
                                </tr>
                                @endif
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
