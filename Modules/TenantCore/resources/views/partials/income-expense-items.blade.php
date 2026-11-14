<div class="table-responsive mt-3">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th scope="col">Head <span class="text-danger" aria-hidden="true">*</span></th>
                <th class="text-end" scope="col">Amount <span class="text-danger" aria-hidden="true">*</span></th>
                <th scope="col"><span class="visually-hidden">Remove</span></th>
            </tr>
        </thead>
        <tbody id="income-expense-rows">
            @forelse (getValue('items', null, $record->items->all()) as $index => $item)
                @include('tenantcore::partials.income-expense-row')
            @empty
                @include('tenantcore::partials.income-expense-row', ['index' => 0, 'item' => null])
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th class="text-end" scope="row">Total</th>
                <td class="text-end fw-semibold"><output id="income-expense-total" aria-live="polite">{{ $record->amount ?? '0.00' }}</output></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
@error('items')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

<button class="btn btn-outline-primary btn-sm mt-2" id="income-expense-add-row" type="button">
    <i class="fa-solid fa-plus me-1" aria-hidden="true"></i>Add head
</button>

<template id="income-expense-row-template">
    @include('tenantcore::partials.income-expense-row', ['index' => '__INDEX__', 'item' => null])
</template>
