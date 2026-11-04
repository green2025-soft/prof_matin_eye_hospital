<p class="small text-end mb-2">Amounts in BDT</p>
<div class="table-responsive">
    <table class="table app-clinical-invoice-table app-inventory-invoice-table">
        <caption class="visually-hidden">Saved {{ strtolower($invoiceTitle) }} items and stock values.</caption>
        <thead>
            <tr>
                <th scope="col" class="app-clinical-invoice-number">SL</th>
                <th scope="col">Item / SKU</th>
                <th scope="col" class="text-end">Qty / Unit</th>
                @if ($entry->product_type === 'medicine')
                    <th scope="col">Expiry</th>
                @endif
                <th scope="col" class="text-end app-clinical-invoice-amount">Stock value</th>
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
                            / {{ ucfirst($line->direction) }}
                            @if ($line->stock_lot_id)
                                / Lot #{{ $line->stock_lot_id }}
                            @endif
                        </small>
                    </td>
                    <td class="text-end">
                        {{ $line->quantity }} {{ $line->productUnit->unit->symbol ?: $line->productUnit->unit->name }}
                        @if ($line->factor !== 1)
                            <small class="d-block text-body-secondary">{{ $line->base_quantity }} Pcs</small>
                        @endif
                    </td>
                    @if ($entry->product_type === 'medicine')
                        <td>
                            {{ $line->expiry_date?->format('d-m-Y') ?: '—' }}
                            @if ($line->batch_no)
                                <small class="d-block text-body-secondary">Batch: {{ $line->batch_no }}</small>
                            @endif
                        </td>
                    @endif
                    <td class="text-end fw-semibold">{{ moneyDecimal($line->cost) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
