<div class="table-responsive">
    <table class="table app-table table-sm align-middle mb-0">
        <caption class="visually-hidden">Daily cash movements and closing dues</caption>
        <thead>
            <tr><th scope="col">Date</th><th class="text-end" scope="col">Opening</th><th class="text-end" scope="col">Received</th><th class="text-end" scope="col">Paid</th><th class="text-end" scope="col">Closing</th><th class="text-end" scope="col">Receivable</th><th class="text-end" scope="col">Payable</th><th class="text-end" scope="col">Balance</th></tr>
        </thead>
        <tbody>
            @forelse ($days as $day)
                <tr><th class="text-nowrap" scope="row">{{ $day['date'] }}</th><td class="text-end text-nowrap">{{ $day['opening'] }}</td><td class="text-end text-nowrap">{{ $day['received'] }}</td><td class="text-end text-nowrap">{{ $day['paid'] }}</td><td class="text-end text-nowrap">{{ $day['closing'] }}</td><td class="text-end text-nowrap">{{ $day['receivable'] }}</td><td class="text-end text-nowrap">{{ $day['payable'] }}</td><td class="text-end text-nowrap">{{ $day['balance'] }}</td></tr>
            @empty
                <tr><td class="text-center text-body-secondary py-4" colspan="8">No activity in this period.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
