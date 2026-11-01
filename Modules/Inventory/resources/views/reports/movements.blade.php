<div class="table-responsive">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Entry</th>
                <th scope="col">Movement</th>
                <th scope="col">Product / SKU</th>
                <th scope="col" class="text-end">In</th>
                <th scope="col" class="text-end">Out</th>
                <th scope="col" class="text-end">Value</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr>
                    <td class="text-nowrap">
                        {{ $record->movement_date }}
                    </td>
                    <td>
                        {{ $record->number }}
                    </td>
                    <td>
                        {{ ucfirst(str_replace('_', ' ', $record->type)) }}
                        @if($record->is_reversal)
                            <span class="badge text-bg-secondary">Reversal</span>
                        @endif
                    </td>
                    <td>
                        {{ $record->name }}
                        <small class="d-block text-body-secondary">
                            {{ $record->sku }}
                        </small>
                    </td>
                    <td class="text-end">
                        {{ $record->quantity > 0 ? $record->quantity : '—' }}
                    </td>
                    <td class="text-end">
                        {{ $record->quantity < 0 ? abs($record->quantity) : '—' }}
                    </td>
                    <td class="text-end">
                        {{ $record->cost_minor < 0 ? '−' : '' }}{{ \Modules\Core\Helpers\Money::decimal(abs((int)$record->cost_minor)) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-center text-body-secondary py-4" colspan="7">No stock movements found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
