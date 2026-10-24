<div class="table-responsive">
    <table class="table app-table align-middle mb-0">
        <thead>
            <tr>
                <th scope="col">Invoice</th>
                @if ($showWorkspace)
                    <th scope="col">Workspace</th>
                @endif
                <th scope="col">Status</th>
                <th class="text-end" scope="col">Total</th>
                <th class="text-end" scope="col">Balance</th>
                <th class="text-end" scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $invoice)
                <tr>
                    <th scope="row">
                        <a href="{{ route($invoiceRoutePrefix.'.show', $invoice) }}">
                            {{ $invoice->number ?: 'Invoice #'.$invoice->id }}
                        </a>
                        <div class="small text-body-secondary fw-normal">
                            {{ $invoice->created_at->format('d M Y') }}
                        </div>
                    </th>
                    @if ($showWorkspace)
                        <td>{{ $invoice->tenant->name }}</td>
                    @endif
                    <td>
                        <span class="app-tag">{{ $statusOptions[$invoice->status] }}</span>
                    </td>
                    <td class="text-end text-nowrap">{{ $invoice->currency }} {{ $invoice->total_amount }}</td>
                    <td class="text-end text-nowrap">{{ $invoice->balance_amount }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2 text-nowrap">
                            <a
                                class="btn btn-outline-secondary btn-sm app-action-button"
                                href="{{ route($invoiceRoutePrefix.'.show', $invoice) }}"
                                aria-label="View invoice {{ $invoice->number ?: $invoice->id }}"
                                title="View invoice"
                            >
                                <i class="fa-solid fa-eye" aria-hidden="true"></i>
                            </a>
                            @can('issue', $invoice)
                                <form action="{{ route('core.invoices.issue', $invoice) }}" method="POST" data-confirm="Issue this invoice? Its details will become final.">
                                    @csrf
                                    <button class="btn btn-outline-primary btn-sm" type="submit">Issue</button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
