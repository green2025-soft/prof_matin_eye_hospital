<?php

$debit = ['side' => 'debit', 'use_payment_type' => false];
$credit = ['side' => 'credit', 'use_payment_type' => false];
$cashDebit = ['name' => 'Cash / Bank', 'account' => 'cash', 'side' => 'debit', 'use_payment_type' => true];
$cashCredit = ['name' => 'Cash / Bank', 'account' => 'cash', 'side' => 'credit', 'use_payment_type' => true];
$clinical = [
    'settlement' => $cashDebit,
    'receivable' => ['name' => 'Patient due', 'account' => 'patient_receivable'] + $debit,
    'discount' => ['name' => 'Discount', 'account' => 'discount_allowed'] + $debit,
];

return [
    'hospital.consultation' => [
        'name' => 'Consultation',
        'module' => 'hospital',
        'voucher' => 'journal',
        'components' => $clinical + [
            'revenue' => ['name' => 'Consultation income', 'account' => 'consultation_income'] + $credit,
        ],
    ],
    'hospital.investigation' => [
        'name' => 'Patient investigation',
        'module' => 'hospital',
        'voucher' => 'journal',
        'components' => $clinical + [
            'revenue' => ['name' => 'Investigation income', 'account' => 'investigation_income'] + $credit,
        ],
    ],
    'hospital.operation' => [
        'name' => 'Patient operation',
        'module' => 'hospital',
        'voucher' => 'journal',
        'components' => $clinical + [
            'revenue' => ['name' => 'Operation income', 'account' => 'operation_income'] + $credit,
        ],
    ],
    'hospital.due-collection' => [
        'name' => 'Hospital due collection',
        'module' => 'hospital',
        'voucher' => 'receipt',
        'components' => [
            'settlement' => $cashDebit,
            'receivable' => ['name' => 'Patient due', 'account' => 'patient_receivable'] + $credit,
        ],
    ],
    'hospital.refund' => [
        'name' => 'Hospital refund',
        'module' => 'hospital',
        'voucher' => 'payment',
        'components' => [
            'refund' => ['name' => 'Refund', 'account' => 'hospital_refunds'] + $debit,
            'settlement' => $cashCredit,
            'receivable' => ['name' => 'Patient due adjustment', 'account' => 'patient_receivable'] + $credit,
        ],
    ],
    'hospital.commission' => [
        'name' => 'Reference commission payment',
        'module' => 'hospital',
        'voucher' => 'payment',
        'components' => [
            'expense' => ['name' => 'Commission expense', 'account' => 'commission_expense'] + $debit,
            'settlement' => $cashCredit,
        ],
    ],
];
