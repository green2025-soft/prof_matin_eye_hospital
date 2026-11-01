<div class="table-responsive">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Activity</th>
                <th scope="col" class="text-end">Entries</th>
                <th scope="col" class="text-end">Value</th>
                <th scope="col" class="text-end">Cash</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr>
                    <td>
                        {{ $record->activity_date }}
                    </td>
                    <td>
                        {{ ucfirst(str_replace('_', ' ', $record->activity)) }}
                    </td>
                    <td class="text-end">
                        {{ $record->entries }}
                    </td>
                    <td class="text-end">
                        {{ \Modules\Core\Helpers\Money::decimal($record->value_minor) }}
                    </td>
                <td class="text-end">{{ $record->cash_minor < 0 ? '−' : '' }}{{ \Modules\Core\Helpers\Money::decimal(abs((int)$record->cash_minor)) }}</td>
                </tr>
            @empty
                <tr>
                    <td class="text-center text-body-secondary py-4" colspan="5">No activity found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
