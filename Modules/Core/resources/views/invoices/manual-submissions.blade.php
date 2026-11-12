@if ($manualSubmissions->isNotEmpty())
    <section class="app-card mt-4 overflow-hidden" aria-labelledby="manual-payments-heading">
        <div class="app-card-heading p-3 border-bottom">
            <h2 class="h6 mb-1" id="manual-payments-heading">
                {{ $isPlatform ? 'Manual payments awaiting review' : 'Recent manual payment submissions' }}
            </h2>
            <p class="small text-body-secondary mb-0">Showing the latest 20 submissions.</p>
        </div>
        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Invoice</th>
                        <th scope="col">Submitted</th>
                        <th scope="col">Reference</th>
                        <th scope="col">Status</th>
                        <th class="text-end" scope="col">Submitted total</th>
                        @if ($canReviewPayments)
                            <th class="text-end" scope="col">Review</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($manualSubmissions as $submission)
                        <tr>
                            <th scope="row">
                                @can('view', $submission->invoice)
                                    <a href="{{ route($invoiceRoutePrefix.'.show', $submission->invoice_id) }}">{{ $submission->invoice->number }}</a>
                                @else
                                    {{ $submission->invoice->number }}
                                @endcan
                            </th>
                            <td class="text-nowrap">{{ $submission->created_at->format('d M Y H:i') }}</td>
                            <td class="text-break">
                                {{ $submission->submitted_reference ?? '-' }}
                                @if ($submission->notes)
                                    <div class="small text-body-secondary">{{ $submission->notes }}</div>
                                @endif
                            </td>
                            <td><span class="app-tag">{{ ucfirst(str_replace('_', ' ', $submission->status)) }}</span></td>
                            <td class="text-end text-nowrap">
                                {{ $submission->currency }} {{ moneyDecimal($submission->amount_minor) }}
                                @if ($submission->extra_charge_minor !== 0)
                                    <div class="small text-body-secondary">Invoice {{ $submission->currency }} {{ moneyDecimal($submission->amount_minor - $submission->extra_charge_minor) }}</div>
                                    <div class="small text-body-secondary">Extra charge {{ $submission->currency }} {{ moneyDecimal($submission->extra_charge_minor) }}</div>
                                @endif
                            </td>
                            @if ($canReviewPayments)
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <form action="{{ route('core.checkouts.approve', $submission->id) }}" method="POST"
                                            data-confirm="Approve this payment after verifying the received funds?">
                                            @csrf
                                            <button class="btn btn-primary btn-sm" type="submit">Approve</button>
                                        </form>
                                        <details>
                                            <summary class="btn btn-outline-danger btn-sm">Reject</summary>
                                            <form class="mt-2" action="{{ route('core.checkouts.reject', $submission->id) }}"
                                                method="POST" data-confirm="Reject this manual payment submission?">
                                                @csrf
                                                <label class="visually-hidden" for="reject-reason-{{ $submission->id }}">
                                                    Rejection reason
                                                </label>
                                                <textarea class="{{ inputClass('reason', 'form-control form-control-sm') }}"
                                                    id="reject-reason-{{ $submission->id }}" name="reason" rows="2" maxlength="500"
                                                    placeholder="Reason (optional)"
                                                    {{ inputAttrs('reason', 'reject-reason-'.$submission->id) }}>{{ getValue('reason') }}</textarea>
                                                @error('reason')
                                                    <div class="invalid-feedback d-block" id="reject-reason-{{ $submission->id }}-error">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <button class="btn btn-outline-danger btn-sm mt-2" type="submit">Reject</button>
                                            </form>
                                        </details>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endif
