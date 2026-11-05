@php
    $showLineVat = $entry->lines->contains(fn ($line) => $line->vat_mode !== 'none' || $line->vat > 0);
@endphp

<p class="small text-end mb-2">Amounts in BDT</p>
<div class="table-responsive">
    <table class="table app-clinical-invoice-table app-pharmacy-invoice-table">
        <caption class="visually-hidden">Saved {{ strtolower($invoiceTitle) }} items and amounts.</caption>
        <thead>
            <tr>
                <th scope="col" class="app-clinical-invoice-number">SL</th>
                <th scope="col">Item / SKU</th>
                <th scope="col" class="text-end">Qty / Unit</th>
                <th scope="col" class="text-end">Rate</th>
                @if ($showSalePrice)
                    <th scope="col" class="text-end" aria-label="Sale price per piece">S.Price</th>
                @endif
                <th scope="col" class="text-end">Discount</th>
                @if ($showLineVat)
                    <th scope="col" class="text-end">VAT</th>
                @endif
                @if ($entry->product_type === 'medicine')
                    <th scope="col">Expiry</th>
                @endif
                <th scope="col" class="text-end app-clinical-invoice-amount">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entry->lines as $line)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="app-pharmacy-invoice-product">
                        <strong>{{ $line->variant->product->name }}</strong>
                        <small class="d-block text-body-secondary">
                            {{ $line->variant->sku }}
                            @if ($line->variant->name && $line->variant->name !== 'Default')
                                / {{ $line->variant->name }}
                            @endif
                        </small>
                    </td>
                    <td class="text-end">
                        {{ $line->quantity }} {{ $line->productUnit->unit->symbol ?: $line->productUnit->unit->name }}
                        @if ($line->factor !== 1)
                            <small class="d-block text-body-secondary">{{ $line->base_quantity }} Pcs</small>
                        @endif
                    </td>
                    <td class="text-end">{{ moneyDecimal($line->unit_price) }}</td>
                    @if ($showSalePrice)
                        <td class="text-end">
                            {{ $line->sale_price_per_piece !== null ? moneyDecimal($line->sale_price_per_piece) : '—' }}
                        </td>
                    @endif
                    <td class="text-end">
                        {{ moneyDecimal($line->discount) }}
                        @if ($line->discount_calculation === 'percent')
                            <small class="d-block text-body-secondary">{{ $line->discount_percent }}%</small>
                        @elseif ($line->discount_calculation === 'unit' && $line->discount_unit_amount !== null)
                            <small class="d-block text-body-secondary">
                                {{ moneyDecimal($line->discount_unit_amount) }} / {{ $line->productUnit->unit->symbol ?: $line->productUnit->unit->name }}
                            </small>
                        @endif
                    </td>
                    @if ($showLineVat)
                        <td class="text-end">
                            {{ moneyDecimal($line->vat) }}
                            @if ($line->vat_calculation === 'percent' && $line->vat_percent > 0)
                                <small class="d-block text-body-secondary">{{ $line->vat_percent }}%</small>
                            @elseif ($line->vat_calculation === 'unit' && $line->vat_unit_amount !== null)
                                <small class="d-block text-body-secondary">
                                    {{ moneyDecimal($line->vat_unit_amount) }} / {{ $line->productUnit->unit->symbol ?: $line->productUnit->unit->name }}
                                </small>
                            @endif
                            <small class="d-block text-body-secondary">
                                {{ match ($line->vat_mode) { 'inclusive' => 'Included', 'exclusive' => 'Added', default => 'No VAT' } }}
                            </small>
                        </td>
                    @endif
                    @if ($entry->product_type === 'medicine')
                        <td>
                            {{ $line->expiry_date?->format('d-m-Y') ?: '—' }}
                            @if ($line->batch_no)
                                <small class="d-block text-body-secondary">Batch: {{ $line->batch_no }}</small>
                            @endif
                        </td>
                    @endif
                    <td class="text-end fw-semibold">
                        {{ moneyDecimal($line->total) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
