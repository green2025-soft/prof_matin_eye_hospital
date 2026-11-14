@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Add one or more income heads in a single voucher.'])

    <form class="income-expense-form app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif

        <section class="app-form-section" aria-labelledby="income-form-heading">
            <h2 class="app-form-section-title" id="income-form-heading">
                <span class="app-form-section-icon"><i class="fa-solid fa-arrow-trend-up" aria-hidden="true"></i></span>Income details
            </h2>

            <div class="row g-3">
                <div class="col-12 col-md-6 col-xl-3">
                    <label class="form-label" for="entry_date">Date <span class="text-danger" aria-hidden="true">*</span></label>
                    <div class="input-group" data-datepicker>
                        <input class="{{ inputClass('entry_date') }}" id="entry_date" name="entry_date" type="text"
                            inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
                            value="{{ getValue('entry_date', null, $entryDate) }}" required data-input {{ inputAttrs('entry_date') }}>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="entry_date"
                            aria-label="Choose income date" title="Choose income date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                    </div>
                    @error('entry_date')<div class="invalid-feedback d-block" id="entry_date-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <label class="form-label" for="payment_type_id">Payment type <span class="text-danger" aria-hidden="true">*</span></label>
                    <select class="{{ inputClass('payment_type_id', 'form-select') }}" id="payment_type_id" name="payment_type_id"
                        required {{ inputAttrs('payment_type_id') }}>
                        <option value="">Select a payment type</option>
                        @foreach ($paymentTypes as $paymentType)
                            <option value="{{ $paymentType->id }}" @selected((string) $paymentTypeId === (string) $paymentType->id)>{{ $paymentType->name }}</option>
                        @endforeach
                    </select>
                    @error('payment_type_id')<div class="invalid-feedback d-block" id="payment_type_id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-xl-6">
                    <label class="form-label" for="note">Note</label>
                    <textarea class="{{ inputClass('note') }}" id="note" name="note" rows="1" maxlength="2000"
                        {{ inputAttrs('note') }}>{{ getValue('note', $income) }}</textarea>
                    @error('note')<div class="invalid-feedback d-block" id="note-error">{{ $message }}</div>@enderror
                </div>
            </div>

            @include('tenantcore::partials.income-expense-items', [
                'record' => $income,
                'headOptionsUrl' => route('tenantcore.income-heads.options'),
            ])
        </section>

        @include('core::partials.form-actions', ['model' => $income, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
    </form>
@endsection
