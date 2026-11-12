<?php

return [
    'tenantcore.income' => [
        'name' => 'Income entry',
        'module' => 'tenantcore',
        'voucher' => 'receipt',
        'components' => [
            'settlement' => ['name' => 'Cash / Bank', 'account' => 'cash', 'side' => 'debit', 'use_payment_type' => true],
            'value' => ['name' => 'Income', 'account' => 'other_income', 'side' => 'credit', 'use_payment_type' => false],
        ],
    ],
    'tenantcore.expense' => [
        'name' => 'Expense entry',
        'module' => 'tenantcore',
        'voucher' => 'payment',
        'components' => [
            'value' => ['name' => 'Expense', 'account' => 'general_expense', 'side' => 'debit', 'use_payment_type' => false],
            'settlement' => ['name' => 'Cash / Bank', 'account' => 'cash', 'side' => 'credit', 'use_payment_type' => true],
        ],
    ],
];
