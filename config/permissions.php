<?php

use Modules\Core\Enums\AccessPermission;

return [
    'tenant_routes' => [
        'tenantcore.accounts.index' => AccessPermission::ViewAccounts->value,
        'tenantcore.accounts.create' => AccessPermission::CreateAccounts->value,
        'tenantcore.accounts.store' => AccessPermission::CreateAccounts->value,
        'tenantcore.accounts.edit' => AccessPermission::UpdateAccounts->value,
        'tenantcore.accounts.update' => AccessPermission::UpdateAccounts->value,
        'tenantcore.accounts.destroy' => AccessPermission::DeleteAccounts->value,
        'core.roles.index' => AccessPermission::ViewRoles->value,
        'core.roles.create' => AccessPermission::CreateRoles->value,
        'core.roles.store' => AccessPermission::CreateRoles->value,
        'core.roles.edit' => AccessPermission::UpdateRoles->value,
        'core.roles.update' => AccessPermission::UpdateRoles->value,
        'core.roles.destroy' => AccessPermission::DeleteRoles->value,
        'core.permissions.index' => AccessPermission::ViewPermissions->value,
        'core.permissions.update' => AccessPermission::ManagePermissions->value,
        'tenantcore.settings.index' => AccessPermission::ViewSettings->value,
        'tenantcore.settings.edit' => AccessPermission::UpdateSettings->value,
        'tenantcore.settings.update' => AccessPermission::UpdateSettings->value,
    ],
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
