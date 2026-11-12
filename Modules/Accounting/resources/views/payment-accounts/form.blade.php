<form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
    @csrf
    @if ($formMethod !== 'POST') @method($formMethod) @endif
    <h2 class="h6 mb-3">{{ $pageTitle }}</h2>
    <div class="mb-3">
        <label class="form-label" for="payment_type_id">Payment type</label>
        <select class="{{ inputClass('payment_type_id', 'form-select') }}" id="payment_type_id" name="payment_type_id" required>
            <option value="">Select payment type</option>
            @foreach ($paymentTypes as $type)
                <option value="{{ $type->id }}" {{ getSelected($type->id, 'payment_type_id', $payment_account) }}>{{ $type->name }}</option>
            @endforeach
        </select>
        @error('payment_type_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="form-label" for="account_head_id">Cash / bank ledger</label>
        <select class="{{ inputClass('account_head_id', 'form-select') }}" id="account_head_id" name="account_head_id" required>
            <option value="">Select ledger</option>
            @foreach ($ledgers as $ledger)
                <option value="{{ $ledger->id }}" {{ getSelected($ledger->id, 'account_head_id', $payment_account) }}>{{ $ledger->code }} · {{ $ledger->name }}</option>
            @endforeach
        </select>
        @error('account_head_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    @include('core::partials.form-actions', ['model' => $payment_account, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
</form>
