<div class="table-responsive">
    <table class="app-report-table table app-table table-sm align-middle mb-0">
        <caption class="visually-hidden">Daily cash movements and closing dues</caption>
        <thead>
            <tr>
                <th scope="col">Date</th>
                <th class="text-end" scope="col">Opening</th>
                <th class="text-end" scope="col">Received</th>
                <th class="text-end" scope="col">Paid</th>
                <th class="text-end" scope="col">Closing</th>
                <th class="text-end" scope="col">Receivable</th>
                <th class="text-end" scope="col">Payable</th>
                <th class="text-end" scope="col">Balance</th>
            </tr>
        </thead>
        <tbody>
            @php
                $previousDay = $openingSnapshot;
            @endphp
            @foreach (\Carbon\CarbonPeriodImmutable::create($fromDate, $toDate) as $date)
                @php
                    $day = $days[$date->toDateString()] ?? [
                        'opening' => $previousDay['closing'],
                        'received' => 0,
                        'paid' => 0,
                        'closing' => $previousDay['closing'],
                        'receivable' => $previousDay['receivable'],
                        'payable' => $previousDay['payable'],
                        'balance' => $previousDay['balance'],
                    ];
                    $previousDay = $day;
                @endphp
                <tr>
                    <th class="text-nowrap" scope="row">{{ $date->toDateString() }}</th>
                    <td class="text-end text-nowrap">{{ moneyDecimal((string) $day['opening']) }}</td>
                    <td class="text-end text-nowrap">{{ moneyDecimal((string) $day['received']) }}</td>
                    <td class="text-end text-nowrap">{{ moneyDecimal((string) $day['paid']) }}</td>
                    <td class="text-end text-nowrap">{{ moneyDecimal((string) $day['closing']) }}</td>
                    <td class="text-end text-nowrap">{{ moneyDecimal((string) $day['receivable']) }}</td>
                    <td class="text-end text-nowrap">{{ moneyDecimal((string) $day['payable']) }}</td>
                    <td class="text-end text-nowrap">{{ moneyDecimal((string) $day['balance']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
