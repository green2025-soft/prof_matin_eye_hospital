@forelse ($headCatalog as $module => $heads)
    @foreach (array_keys($heads, $side, true) as $label)
        <tr>
            <td class="ps-3">
                {{ $label }} <span class="d-block d-print-inline small text-body-secondary">{{ ucfirst($module) }}</span>
            </td>
            <td class="text-end text-nowrap pe-3">{{ moneyDecimal((string) ($headTotals[$module.'|'.$label][$side] ?? 0)) }}</td>
        </tr>
    @endforeach
@empty
    <tr><td colspan="2" class="text-body-secondary p-3">No report heads available.</td></tr>
@endforelse
