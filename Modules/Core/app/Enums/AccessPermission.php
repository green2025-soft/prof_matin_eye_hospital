<?php

namespace Modules\Core\Enums;

enum AccessPermission: string
{
    case ViewAccounts = 'accounts.view';
    case CreateAccounts = 'accounts.create';
    case UpdateAccounts = 'accounts.update';
    case DeleteAccounts = 'accounts.delete';
    case ViewRoles = 'roles.view';
    case CreateRoles = 'roles.create';
    case UpdateRoles = 'roles.update';
    case DeleteRoles = 'roles.delete';
    case AssignRoles = 'roles.assign';
    case ViewPermissions = 'permissions.view';
    case ManagePermissions = 'permissions.manage';
    case ViewSettings = 'settings.view';
    case UpdateSettings = 'settings.update';
    case ViewBilling = 'billing.view';
    case PayInvoices = 'billing.pay';
}
