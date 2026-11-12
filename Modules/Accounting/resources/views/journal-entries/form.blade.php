@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @php($postedLines = old('lines', $journal_entry->lines))
    @php($lineRows = collect(is_iterable($postedLines) ? $postedLines : [])->take(100))

    @include('core::partials.heading', ['title' => $pageTitle])
    <form class="app-card p-3 p-md-4" id="accounting-journal-form" action="{{ $formAction }}" method="POST">
        @csrf
        @if ($formMethod !== 'POST') @method($formMethod) @endif

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label" for="voucher-type">Voucher type <span class="text-danger">*</span></label>
                <select class="{{ inputClass('voucher_type_id', 'form-select') }}" id="voucher-type" name="voucher_type_id"
                    required {{ inputAttrs('voucher_type_id', 'voucher-type') }}>
                    <option value="">Select type</option>
                    @foreach ($voucherTypes as $type)
                        <option value="{{ $type->id }}" {{ getSelected($type->id, 'voucher_type_id', $journal_entry) }}>{{ $type->name }}</option>
                    @endforeach
                </select>
                @error('voucher_type_id')<div class="invalid-feedback d-block" id="voucher-type-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="entry-date">Date <span class="text-danger">*</span></label>
                <div class="input-group" data-datepicker>
                    <input class="{{ inputClass('entry_date') }}" id="entry-date" name="entry_date" type="text"
                        value="{{ getValue('entry_date', null, $entryDate) }}" placeholder="YYYY-MM-DD" autocomplete="off"
                        required data-input {{ inputAttrs('entry_date', 'entry-date') }}>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose voucher date" aria-controls="entry-date">
                        <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                    </button>
                </div>
                @error('entry_date')<div class="invalid-feedback d-block" id="entry-date-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="reference">Reference</label>
                <input class="{{ inputClass('reference') }}" id="reference" name="reference" maxlength="100"
                    value="{{ getValue('reference', $journal_entry) }}" {{ inputAttrs('reference') }}>
                @error('reference')<div class="invalid-feedback d-block" id="reference-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
            <h2 class="h6 mb-0">Voucher lines</h2>
            <button class="btn btn-outline-primary btn-sm" id="accounting-add-line" type="button">
                <i class="fa-solid fa-plus me-2" aria-hidden="true"></i>Add line
            </button>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-2">
                <thead>
                    <tr>
                        <th scope="col">Account head</th>
                        <th class="text-end" scope="col">Debit (BDT)</th>
                        <th class="text-end" scope="col">Credit (BDT)</th>
                        <th scope="col">Remarks</th>
                        <th scope="col"><span class="visually-hidden">Actions</span></th>
                    </tr>
                </thead>
                <tbody id="accounting-journal-lines">
                    @forelse ($lineRows as $index => $line)
                        @include('accounting::journal-entries.line', ['index' => $index, 'line' => $line])
                    @empty
                        @include('accounting::journal-entries.line', ['index' => 0, 'line' => []])
                        @include('accounting::journal-entries.line', ['index' => 1, 'line' => []])
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th scope="row">Total</th>
                        <td class="text-end fw-semibold" id="accounting-debit-total">0.00</td>
                        <td class="text-end fw-semibold" id="accounting-credit-total">0.00</td>
                        <td colspan="2"><span class="small" id="accounting-balance-state" aria-live="polite"></span></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @error('lines')<div class="invalid-feedback d-block mb-3">{{ $message }}</div>@enderror

        <div class="mt-3">
            <label class="form-label" for="narration">Narration</label>
            <textarea class="{{ inputClass('narration') }}" id="narration" name="narration" rows="2" maxlength="2000"
                {{ inputAttrs('narration') }}>{{ getValue('narration', $journal_entry) }}</textarea>
            @error('narration')<div class="invalid-feedback d-block" id="narration-error">{{ $message }}</div>@enderror
        </div>
        @include('core::partials.form-actions', ['model' => $journal_entry])
        <template id="accounting-journal-line-template">
            @include('accounting::journal-entries.line', ['index' => '__INDEX__', 'line' => []])
        </template>
    </form>
@endsection
