<div class="table-responsive">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Invoice / supplier</th>
                <th scope="col" class="text-end">Item discounts</th>
                <th scope="col" class="text-end">Invoice discount</th>
                <th scope="col" class="text-end">Settlement discount</th>
                <th scope="col" class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr>
                    <td>
                        {{ $record->document_date }}
                    </td>
                    <td>
                        {{ $record->number }}
                        <small class="d-block text-body-secondary">
                            {{ $record->name }}
                        </small>
                    </td>
                    <td class="text-end">
                        {{ \Modules\Core\Helpers\Money::decimal($record->line_discount_minor) }}
                    </td>
                    <td class="text-end">
                    {{ \Modules\Core\Helpers\Money::decimal($record->discount_minor - $record->line_discount_minor) }}
                    </td>
                    <td class="text-end">
                        {{ \Modules\Core\Helpers\Money::decimal($record->settlement_discount_minor) }}
                    </td>
                    <td class="text-end">
                        {{ \Modules\Core\Helpers\Money::decimal($record->total_minor) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-center text-body-secondary py-4" colspan="6">No purchases found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
