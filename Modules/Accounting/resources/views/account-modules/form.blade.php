@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => 'Edit posting mapping'])
    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-3 mb-3">
            <div class="col-md-6"><label class="form-label" for="name">Name</label><input class="{{ inputClass('name') }}" id="name" name="name" value="{{ getValue('name', $account_module) }}" maxlength="150" required></div>
            <div class="col-md-3">
                <label class="form-label" for="voucher-type">Voucher type</label>
                <select class="{{ inputClass('voucher_type_id', 'form-select') }}" id="voucher-type" name="voucher_type_id" required>
                    @foreach ($voucherTypes as $voucherType)
                        <option value="{{ $voucherType->id }}" {{ getSelected($voucherType->id, 'voucher_type_id', $account_module) }}>{{ $voucherType->name }}</option>
                    @endforeach
                </select>
                @error('voucher_type_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3"><label class="form-label" for="active">Automatic posting</label><select class="form-select" id="active" name="active"><option value="1" {{ getSelected(1, 'active', $account_module) }}>Enabled</option><option value="0" {{ getSelected(0, 'active', $account_module) }}>Disabled</option></select></div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light"><tr><th>Component</th><th>Side</th><th>Default ledger</th><th>Payment type</th></tr></thead>
                <tbody>
                    @foreach ($account_module->components as $index => $component)
                        <tr>
                            <th scope="row">{{ $component->name }}<input type="hidden" name="components[{{ $index }}][id]" value="{{ $component->id }}"></th>
                            <td>{{ ucfirst($component->side) }}</td>
                            <td>
                                @php($selectedLedger = $selectedLedgers->get(getValue('components.'.$index.'.account_head_id', null, $component->account_head_id)))
                                <label class="visually-hidden" for="component-ledger-{{ $component->id }}">{{ $component->name }} ledger</label>
                                <select class="{{ inputClass('components.'.$index.'.account_head_id', 'form-select') }}"
                                    id="component-ledger-{{ $component->id }}" name="components[{{ $index }}][account_head_id]" required
                                    data-ajax-select data-url="{{ route('accounting.ledgers.options', ['active' => 1]) }}"
                                    data-placeholder="Search ledger name or code">
                                    <option value=""></option>
                                    @if ($selectedLedger)
                                        <option value="{{ $selectedLedger->id }}" selected>{{ $selectedLedger->code }} · {{ $selectedLedger->name }}@unless ($selectedLedger->active) (inactive)@endunless</option>
                                    @endif
                                </select>
                                @error('components.'.$index.'.account_head_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </td>
                            <td class="small text-body-secondary">{{ $component->use_payment_type ? 'Default when no Payment Type is selected' : 'Default ledger' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @include('core::partials.form-actions', ['model' => $account_module, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
    </form>
@endsection
