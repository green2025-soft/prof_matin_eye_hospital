<?php

return [
    'groups' => [
        'accounts' => ['label' => 'Accounts', 'area' => 'Access control'],
        'roles' => ['label' => 'Roles', 'area' => 'Access control'],
        'permissions' => ['label' => 'Permissions', 'area' => 'Access control'],
        'tenants' => ['label' => 'Tenants', 'area' => 'Platform'],
        'plans' => ['label' => 'Plans', 'area' => 'Platform'],
        'subscriptions' => ['label' => 'Subscriptions', 'area' => 'Platform'],
        'invoices' => ['label' => 'Invoices', 'area' => 'Billing'],
        'payments' => ['label' => 'Payments', 'area' => 'Billing'],
        'billing' => ['label' => 'Billing portal', 'area' => 'Billing'],
        'settings' => ['label' => 'Settings', 'area' => 'Administration'],
    ],
    'actions' => [
        'view' => 'View',
        'create' => 'Create',
        'update' => 'Update',
        'enter' => 'Open dashboard',
        'delete' => 'Delete',
        'assign' => 'Assign roles',
        'manage' => 'Manage permissions',
        'issue' => 'Issue invoices',
        'void' => 'Void invoices',
        'pay' => 'Pay invoices',
    ],
];
