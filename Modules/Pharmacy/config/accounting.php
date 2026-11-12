<?php

$debit = ['side' => 'debit', 'use_payment_type' => false];
$credit = ['side' => 'credit', 'use_payment_type' => false];
$cashDebit = ['name' => 'Cash / Bank', 'account' => 'cash', 'side' => 'debit', 'use_payment_type' => true];
$cashCredit = ['name' => 'Cash / Bank', 'account' => 'cash', 'side' => 'credit', 'use_payment_type' => true];
$integrations = [];

// Medicine and Optic use the same accounting components with their own default ledgers.
foreach (['medicine' => 'Medicine', 'optic' => 'Optic'] as $type => $label) {
    $prefix = 'pharmacy.'.$type.'.';
    $inventoryDebit = ['name' => 'Inventory', 'account' => $type.'_inventory'] + $debit;
    $inventoryCredit = ['name' => 'Inventory', 'account' => $type.'_inventory'] + $credit;
    $receivableDebit = ['name' => 'Customer due', 'account' => 'customer_receivable'] + $debit;
    $receivableCredit = ['name' => 'Customer due', 'account' => 'customer_receivable'] + $credit;
    $payableDebit = ['name' => 'Supplier due', 'account' => 'supplier_payable'] + $debit;
    $payableCredit = ['name' => 'Supplier due', 'account' => 'supplier_payable'] + $credit;

    $features = [
        'purchase' => [
            'name' => 'Purchase',
            'voucher' => 'journal',
            'components' => ['inventory' => $inventoryDebit, 'settlement' => $cashCredit, 'payable' => $payableCredit],
        ],
        'sale' => [
            'name' => 'Sale',
            'voucher' => 'journal',
            'components' => [
                'settlement' => $cashDebit,
                'receivable' => $receivableDebit,
                'discount' => ['name' => 'Sales discount', 'account' => 'discount_allowed'] + $debit,
                'revenue' => ['name' => 'Sales income', 'account' => $type.'_sales'] + $credit,
                'vat' => ['name' => 'Output VAT', 'account' => 'vat_payable'] + $credit,
                'cogs' => ['name' => 'Cost of sales', 'account' => $type.'_cogs'] + $debit,
                'inventory' => $inventoryCredit,
            ],
        ],
        'sale-return' => [
            'name' => 'Sales return',
            'voucher' => 'journal',
            'components' => [
                'returns' => ['name' => 'Sales return', 'account' => 'sales_returns'] + $debit,
                'vat' => ['name' => 'VAT reversal', 'account' => 'vat_payable'] + $debit,
                'settlement' => $cashCredit,
                'receivable' => $receivableCredit,
                'inventory' => $inventoryDebit,
                'cogs' => ['name' => 'Cost reversal', 'account' => $type.'_cogs'] + $credit,
            ],
        ],
        'purchase-return' => [
            'name' => 'Purchase return',
            'voucher' => 'journal',
            'components' => [
                'settlement' => $cashDebit,
                'payable' => $payableDebit,
                'inventory' => $inventoryCredit,
                'variance_loss' => ['name' => 'Valuation loss', 'account' => 'purchase_return_loss'] + $debit,
                'variance_gain' => ['name' => 'Valuation gain', 'account' => 'purchase_return_gain'] + $credit,
            ],
        ],
        'customer-payment' => [
            'name' => 'Customer payment',
            'voucher' => 'receipt',
            'components' => [
                'settlement' => $cashDebit,
                'discount' => ['name' => 'Settlement discount', 'account' => 'discount_allowed'] + $debit,
                'receivable' => $receivableCredit,
            ],
        ],
        'supplier-payment' => [
            'name' => 'Supplier payment',
            'voucher' => 'payment',
            'components' => [
                'payable' => $payableDebit,
                'settlement' => $cashCredit,
                'discount' => ['name' => 'Settlement discount', 'account' => 'supplier_discount'] + $credit,
            ],
        ],
        'opening-stock' => [
            'name' => 'Opening stock',
            'voucher' => 'journal',
            'components' => [
                'inventory' => $inventoryDebit,
                'equity' => ['name' => 'Opening balance', 'account' => 'opening_balance'] + $credit,
            ],
        ],
        'wastage' => [
            'name' => 'Damage / Wastage',
            'voucher' => 'journal',
            'components' => [
                'expense' => ['name' => 'Damage / Wastage', 'account' => 'wastage'] + $debit,
                'inventory' => $inventoryCredit,
            ],
        ],
        'adjustment' => [
            'name' => 'Stock adjustment',
            'voucher' => 'journal',
            'components' => [
                'inventory_in' => $inventoryDebit,
                'gain' => ['name' => 'Adjustment gain', 'account' => 'adjustment_gain'] + $credit,
                'loss' => ['name' => 'Adjustment loss', 'account' => 'adjustment_loss'] + $debit,
                'inventory_out' => $inventoryCredit,
            ],
        ],
        'transfer-out' => [
            'name' => 'Branch transfer — Out',
            'voucher' => 'journal',
            'components' => [
                'clearing' => ['name' => 'Interbranch clearing', 'account' => 'interbranch_clearing'] + $debit,
                'inventory' => $inventoryCredit,
            ],
        ],
        'transfer-in' => [
            'name' => 'Branch transfer — In',
            'voucher' => 'journal',
            'components' => [
                'inventory' => $inventoryDebit,
                'clearing' => ['name' => 'Interbranch clearing', 'account' => 'interbranch_clearing'] + $credit,
            ],
        ],
        'stock-variance' => [
            'name' => 'Stock cost correction',
            'voucher' => 'journal',
            'components' => [
                'inventory_in' => $inventoryDebit,
                'gain' => ['name' => 'Cost correction', 'account' => 'valuation_variance'] + $credit,
                'loss' => ['name' => 'Cost correction', 'account' => 'valuation_variance'] + $debit,
                'inventory_out' => $inventoryCredit,
            ],
        ],
    ];

    foreach ($features as $featureKey => $feature) {
        $integrations[$prefix.$featureKey] = [
            'name' => $label.' — '.$feature['name'],
            'module' => 'pharmacy',
            'voucher' => $feature['voucher'],
            'components' => $feature['components'],
        ];
    }
}

return $integrations;
