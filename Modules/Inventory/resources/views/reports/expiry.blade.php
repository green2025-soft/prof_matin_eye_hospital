<div class="table-responsive">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th scope="col">Medicine / SKU</th>
                <th scope="col">Batch</th>
                <th scope="col">Expiry</th>
                <th scope="col" class="text-end">Base quantity</th>
                <th scope="col" class="text-end">Value</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr>
                    <td>
                        {{ $record->name }}
                        <small class="d-block text-body-secondary">
                            {{ $record->sku }}
                        </small>
                    </td>
                    <td>
                        {{ $record->batch_no ?: '—' }}
                    </td>
                    <td>
                        {{ $record->expiry_date }}
                        @if($record->expiry_date < now()->toDateString())
                            <span class="badge text-bg-danger">Expired</span>
                        @endif
                    </td>
                    <td class="text-end">
                        {{ $record->remaining_quantity }}
                    </td>
                    <td class="text-end">
                        {{ \Modules\Core\Helpers\Money::decimal($record->remaining_cost_minor) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-center text-body-secondary py-4" colspan="5">No medicine expires within this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
