@php
    $showLineDiscount = $showDiscount ?? true;
    $showLineVat = $showVat ?? false;
@endphp

<p class="small text-end mb-2">Amounts in BDT</p>
<div class="table-responsive">
    <table class="table app-clinical-invoice-table app-inventory-invoice-table">
        <caption class="visually-hidden">Saved {{ strtolower($invoiceTitle) }} items and amounts.</caption>
        <thead>
            <tr>
                <th scope="col" class="app-clinical-invoice-number">SL</th>
                <th scope="col">Item / SKU</th>
                <th scope="col" class="text-end">Qty / Unit</th>
                <th scope="col" class="text-end">Rate</th>
                @if ($showLineDiscount)
                    <th scope="col" class="text-end">Discount</th>
                @endif
                @if ($showLineVat)
                    <th scope="col" class="text-end">VAT</th>
                @endif
                <th scope="col" class="text-end app-clinical-invoice-amount">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entry->lines as $line)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="app-inventory-invoice-product">
                        <strong>{{ $line->variant->product->name }}</strong>
                        <small class="d-block text-body-secondary">
                            {{ $line->variant->sku }}
                            @if ($line->variant->name && $line->variant->name !== 'Default')
                                / {{ $line->variant->name }}
                            @endif
                        </small>
                    </td>
                    <td class="text-end" data-label="Qty / Unit">
                        {{ $line->quantity }} {{ $line->productUnit->unit->symbol ?: $line->productUnit->unit->name }}
                        @if ($line->factor !== 1)
                            <small class="d-block text-body-secondary">{{ $line->base_quantity }} Pcs</small>
                        @endif
                    </td>
                    <td class="text-end" data-label="Rate">{{ moneyDecimal($line->unit_price) }}</td>
                    @if ($showLineDiscount)
                        <td class="text-end" data-label="Discount">
                            {{ moneyDecimal($line->discount) }}
                        </td>
                    @endif
                    @if ($showLineVat)
                        <td class="text-end" data-label="VAT">
                            {{ moneyDecimal($line->vat) }}
                            <small class="d-block text-body-secondary">
                                {{ match ($line->vat_mode) { 'inclusive' => 'Included', 'exclusive' => 'Added', default => 'No VAT' } }}
                            </small>
                        </td>
                    @endif
                    <td class="text-end fw-semibold" data-label="Amount">
                        {{ moneyDecimal($line->total) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
