@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])

    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
        @csrf
        @if ($formMethod !== 'POST') @method($formMethod) @endif
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                <input class="{{ inputClass('name') }}" id="name" name="name" value="{{ getValue('name', $account_head) }}" maxlength="255" required autofocus {{ inputAttrs('name') }}>
                @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="parent_id">Parent account <span class="text-danger">*</span></label>
                @if ($account_head->exists)
                    <input class="form-control" id="parent_id" value="{{ $account_head->parent->code }} · {{ $account_head->parent->name }}" readonly>
                @else
                    <select class="{{ inputClass('parent_id', 'form-select') }}" id="parent_id" name="parent_id" required {{ inputAttrs('parent_id') }}>
                        <option value="">Select parent account</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}" {{ getSelected($parent->id, 'parent_id', $account_head, $selectedParent?->id) }}>{{ $parent->code }} · {{ $parent->name }}</option>
                        @endforeach
                    </select>
                @endif
                @error('parent_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            @if ($account_head->exists)
                <div class="col-md-6"><label class="form-label" for="code">Code</label><input class="form-control" id="code" value="{{ $account_head->code }}" readonly></div>
            @endif
            <div class="col-md-6">
                <label class="form-label" for="active">Status</label>
                <select class="form-select" id="active" name="active">
                    <option value="1" {{ getSelected(1, 'active', $account_head, 1) }}>Active</option>
                    <option value="0" {{ getSelected(0, 'active', $account_head, 1) }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="is_transaction">Use in vouchers</label>
                @php($defaultPosting = $account_head->exists ? $account_head->is_transaction : $selectedParent?->depth === 4)
                <select class="form-select" id="is_transaction" name="is_transaction">
                    <option value="0" {{ getSelected(0, 'is_transaction', null, $defaultPosting) }}>Group only</option>
                    <option value="1" {{ getSelected(1, 'is_transaction', null, $defaultPosting) }}>Ledger account (level 5)</option>
                </select>
                @error('is_transaction')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="system_key">Cash / bank report role</label>
                <select class="{{ inputClass('system_key', 'form-select') }}" id="system_key" name="system_key">
                    <option value="">Inherit from parent</option>
                    @foreach ($reportRoles as $value => $label)
                        <option value="{{ $value }}" {{ getSelected($value, 'system_key', $account_head) }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('system_key')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="cash_flow_activity">Cash flow activity</label>
                <select class="{{ inputClass('cash_flow_activity', 'form-select') }}" id="cash_flow_activity" name="cash_flow_activity">
                    <option value="">Inherit from parent</option>
                    @foreach ($cashFlowActivities as $value => $label)
                        <option value="{{ $value }}" {{ getSelected($value, 'cash_flow_activity', $account_head) }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('cash_flow_activity')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-12"><label class="form-label" for="remarks">Remarks</label><textarea class="{{ inputClass('remarks') }}" id="remarks" name="remarks" rows="2" maxlength="2000">{{ getValue('remarks', $account_head) }}</textarea></div>
        </div>
        @include('core::partials.form-actions', ['model' => $account_head, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
    </form>
@endsection
