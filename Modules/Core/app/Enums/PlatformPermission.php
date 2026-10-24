<?php

namespace Modules\Core\Enums;

enum PlatformPermission: string
{
    case ViewTenants = 'tenants.view';
    case CreateTenants = 'tenants.create';
    case UpdateTenants = 'tenants.update';
}
