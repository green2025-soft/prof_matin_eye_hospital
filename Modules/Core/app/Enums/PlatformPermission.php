<?php

namespace Modules\Core\Enums;

enum PlatformPermission: string
{
    case ViewTenants = 'tenants.view';
    case CreateTenants = 'tenants.create';
    case UpdateTenants = 'tenants.update';
    case DeleteTenants = 'tenants.delete';
    case EnterTenants = 'tenants.enter';
    case ViewPlans = 'plans.view';
    case CreatePlans = 'plans.create';
    case UpdatePlans = 'plans.update';
    case DeletePlans = 'plans.delete';
    case ViewSubscriptions = 'subscriptions.view';
    case CreateSubscriptions = 'subscriptions.create';
    case UpdateSubscriptions = 'subscriptions.update';
    case ViewInvoices = 'invoices.view';
    case CreateInvoices = 'invoices.create';
    case UpdateInvoices = 'invoices.update';
    case IssueInvoices = 'invoices.issue';
    case VoidInvoices = 'invoices.void';
    case ViewPayments = 'payments.view';
    case CreatePayments = 'payments.create';
}
