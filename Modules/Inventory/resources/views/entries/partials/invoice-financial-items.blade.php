@php
    $showLineDiscount = $showDiscount ?? true;
    $showLineVat = ($showVat ?? true) && ($document->vat_calculation === 'line' || $document->vat_minor > 0);
    $showLineExpiry = ($showExpiry ?? true) && $document->product_type === 'medicine';
@endphp

<p class="small text-end mb-2">Amounts in BDT</p>
<div class="table-responsive">
    <table class="table app-clinical-invoice-table app-inventory-invoice-table">
        <caption class="visually-hidden">Saved {{ strtolower($documentTitle) }} items and amounts.</caption>
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
                @if ($showLineExpiry)
                    <th scope="col">Expiry</th>
                @endif
                <th scope="col" class="text-end app-clinical-invoice-amount">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($document->lines as $line)
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
                    <td class="text-end" data-label="Rate">{{ \Modules\Core\Helpers\Money::decimal($line->unit_price_minor) }}</td>
                    @if ($showLineDiscount)
                        <td class="text-end" data-label="Discount">
                            {{ \Modules\Core\Helpers\Money::decimal($line->discount_minor) }}
                            @if ($line->discount_calculation === 'percent')
                                <small class="d-block text-body-secondary">{{ $line->discount_percent }}%</small>
                            @elseif ($line->discount_calculation === 'unit' && $line->discount_unit_amount_minor !== null)
                                <small class="d-block text-body-secondary">
                                    {{ \Modules\Core\Helpers\Money::decimal($line->discount_unit_amount_minor) }} / {{ $line->productUnit->unit->symbol ?: $line->productUnit->unit->name }}
                                </small>
                            @endif
                        </td>
                    @endif
                    @if ($showLineVat)
                        <td class="text-end" data-label="VAT">
                            {{ \Modules\Core\Helpers\Money::decimal($line->vat_minor) }}
                            @if ($line->vat_calculation === 'percent' && $line->vat_percent > 0)
                                <small class="d-block text-body-secondary">{{ $line->vat_percent }}%</small>
                            @elseif ($line->vat_calculation === 'unit' && $line->vat_unit_amount_minor !== null)
                                <small class="d-block text-body-secondary">
                                    {{ \Modules\Core\Helpers\Money::decimal($line->vat_unit_amount_minor) }} / {{ $line->productUnit->unit->symbol ?: $line->productUnit->unit->name }}
                                </small>
                            @endif
                            <small class="d-block text-body-secondary">
                                {{ match ($line->vat_mode ?? $document->vat_mode) { 'inclusive' => 'Included', 'exclusive' => 'Added', default => 'No VAT' } }}
                            </small>
                        </td>
                    @endif
                    @if ($showLineExpiry)
                        <td>
                            {{ $line->expiry_date?->format('d-m-Y') ?: '—' }}
                            @if ($line->batch_no)
                                <small class="d-block text-body-secondary">Batch: {{ $line->batch_no }}</small>
                            @endif
                        </td>
                    @endif
                    <td class="text-end fw-semibold" data-label="Amount">
                        {{ \Modules\Core\Helpers\Money::decimal($line->total_minor) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
