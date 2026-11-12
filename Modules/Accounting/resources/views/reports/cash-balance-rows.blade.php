@foreach ($balances['current']['rows'] as $id => $row)
    <tr>
        <th class="fw-normal" scope="row">{{ $row['account']->code }} - {{ $row['account']->name }}</th>
        @foreach ($periods as $key => $period)
            <td class="text-end text-nowrap">{{ moneyDecimal((string) $balances[$key]['rows']->get($id)[$balanceField]) }}</td>
        @endforeach
    </tr>
@endforeach
