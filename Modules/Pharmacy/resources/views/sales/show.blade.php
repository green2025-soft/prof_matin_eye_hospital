@extends('core::layouts.app')

@section('title', ucfirst($entry->product_type).' Sales Invoice')

@section('content')
    <div class="app-print-hidden text-end ">
        {{-- @include('core::partials.heading', ['title' => ucfirst($entry->product_type).' '.$entry->number]) --}}
        <div class="flex-wrap gap-2 mb-3 text-end">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route($routePrefix.'.index', ['product_type' => $entry->product_type]) }}">
                <i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Back
            </a>
            <button class="btn btn-primary btn-sm" type="button" data-print>
                <i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print
            </button>
            @can('update', $entry)
                <a class="btn btn-outline-primary btn-sm" href="{{ route($routePrefix.'.edit', [$resourceParameter => $entry, 'product_type' => $entry->product_type]) }}">
                    <i class="fa-solid fa-pen-to-square me-2" aria-hidden="true"></i>Edit
                </a>
            @endcan
            {{-- @can('delete', $entry)
                <form action="{{ route($routePrefix.'.destroy', [$resourceParameter => $entry, 'product_type' => $entry->product_type]) }}" method="POST" data-confirm="Delete this sales invoice and reverse its stock and amounts?">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm" type="submit"><i class="fa-solid fa-trash-can me-2" aria-hidden="true"></i>Delete</button>
                </form>
            @endcan --}}

        </div>
    </div>

    @php
        $invoiceTitle = 'Sales Invoice';
        $outstandingMinor = $entry->voided_at ? 0 : $entry->outstandingMinor();
        $invoiceStatus = $entry->voided_at ? 'VOID' : ($outstandingMinor > 0 ? 'DUE' : 'PAID');
        $invoicePaper = in_array($invoicePaper ?? null, ['a4', 'a5', 'pos'], true) ? $invoicePaper : 'a4';
        $isPos = $invoicePaper === 'pos';
        $hasVat = $entry->vat > 0;
        $itemDiscountTotal = $entry->lines->sum('discount');
        $invoiceDiscountTotal = $entry->discount - $itemDiscountTotal;
        $salePosWidth = is_string($salePosWidth ?? null) && preg_match('/\A\d{1,3}(?:\.\d{1,2})?\z/', $salePosWidth)
            && (float) $salePosWidth >= 20 && (float) $salePosWidth <= 300 ? $salePosWidth : '80';
        $salePosWidth = rtrim(rtrim(number_format((float) $salePosWidth, 2, '.', ''), '0'), '.');
    @endphp

    <style>
        @media print {
            @page pharmacy-sale {
                size: {{ match ($invoicePaper) { 'a5' => 'A5', 'pos' => $salePosWidth.'mm 297mm', default => 'A4' } }};
                margin: {{ $isPos ? '2mm 0 0' : '10mm' }};
            }

            .app-shell,
            .app-page,
            .app-content,
            .app-sale-invoice {
                page: pharmacy-sale;
            }
        }
    </style>
     @if ($isPos)
    <style>
         @media print {
            .app-shell,
            .app-page,
            .app-content {
                width: 100%;
                max-width: none;
                min-height: 0;
                margin: 0 !important;
                padding: 0 !important;
                overflow: visible;
            }
         }
    </style>
    @endif


    <article class="app-card app-invoice-document app-clinical-invoice app-pharmacy-invoice app-sale-invoice app-sale-invoice-{{ $invoicePaper }}" @if ($isPos) style="--sale-pos-width: {{ $salePosWidth }}mm" @endif aria-labelledby="pharmacy-invoice-title">

        <header class="app-clinical-invoice-header">
            <div class="app-clinical-invoice-brand">
                @if ($logoUrl)
                    <img class="app-clinical-invoice-logo" src="{{ $logoUrl }}" alt="" width="76" height="76">
                @endif
                <div>
                    <h2 class="app-clinical-invoice-company">{{ $company['name'] }}</h2>
                    @if ($company['branch_name'] ?? '')
                        {{-- <p class="fw-semibold mb-1">{{ $company['branch_name'] }}</p> --}}
                    @endif
                    @if ($company['address'] ?? '')
                        <p class="app-clinical-invoice-address">{{ $company['address'] }}</p>
                    @endif
                    <div class="app-clinical-invoice-contact">
                        @if ($company['phone'] ?? '')
                            <span>Phone: {{ $company['phone'] }}</span>
                        @endif
                        @if ($company['email'] ?? '')
                            <span>{{ $company['email'] }}</span>
                        @endif
                        @if ($company['tax_id'] ?? '')
                            <span>Tax ID: {{ $company['tax_id'] }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="app-clinical-invoice-title-row">
                <div>
                    <h1 id="pharmacy-invoice-title" class="text-center">{{ $invoiceTitle }}</h1>
                </div>

            </div>
        </header>

        <div class="app-pharmacy-invoice-parties">
            <table>
                <tr>
                    <td><b>Inv.No :</b> {{ $entry->number }}</td>
                    <td class="text-end"><b>Date :</b> {{ $entry->entry_date->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <td colspan="2"><b>Name : </b> {{ $customer['name'] ?? '—' }}</td>
                </tr>
                  @if ($customer['mobile_no'] ?? '')
                  <tr>
                      <td colspan="2"><b>Mobile : </b> {{ $customer['mobile_no'] ?? '—' }}</td>
                  </tr>
                @endif
                @if ($customer['address'] ?? '')
                <tr>
                      <td colspan="2"><b>Address : </b> {{ $customer['address'] ?? '—' }}</td>
                  </tr>
                @endif
            </table>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>P.Name</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Discount</th>
                        <th class="text-end">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entry->lines as $line)
                    <tr>
                        <td>{{ $line->variant->product->name }}</td>
                        <td  class="text-center">
                            {{ $line->quantity }} {{ $line->productUnit->unit->symbol ?: $line->productUnit->unit->name }}
                            @if ($line->factor !== 1)
                                <small class="d-block text-body-secondary">{{ $line->base_quantity }} Pcs</small>
                            @endif
                        </td>
                        <td class="text-end">{{ moneyDecimal($line->discount) }}</td>
                        <td class="text-end fw-semibold">
                            {{ moneyDecimal($line->total + $line->invoice_discount) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Sub Total  </th>
                        <th class="text-end">{{ moneyDecimal($entry->subtotal) }}</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">Item Discount</th>
                        <th class="text-end">{{ moneyDecimal($itemDiscountTotal) }}</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">Invoice Discount</th>
                        <th class="text-end">{{ moneyDecimal($invoiceDiscountTotal) }}</th>
                    </tr>
                     @if ($hasVat)
                    <tr>
                        <th colspan="3" class="text-end">VAT</th>
                        <th class="text-end">{{ moneyDecimal($entry->vat) }}</th>
                    </tr>
                    @endif
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end">{{ moneyDecimal($entry->total) }}</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">Paid</th>
                        <th class="text-end">{{ moneyDecimal($entry->paid) }}</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">Due</th>
                        <th class="text-end">{{ moneyDecimal($outstandingMinor) }}</th>
                    </tr>
                </tfoot>
            </table>
             @if ($entry->notes)
              <p>
                <b class="app-clinical-invoice-section-title">Remarks:</b>
                <span  class="app-pharmacy-invoice-note">{{ $entry->notes }}</span>
            </p>
            @endif
            <div class="app-pharmacy-invoice-signatures">
                <div class="app-clinical-invoice-signature"><span>Received by</span></div>
                <div class="app-clinical-invoice-signature"><span>Authorized signature</span></div>
            </div>
        </div>
    </article>
@endsection
