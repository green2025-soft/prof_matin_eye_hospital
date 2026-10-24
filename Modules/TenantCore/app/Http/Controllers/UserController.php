<?php

namespace Modules\TenantCore\Http\Controllers;

use Modules\Core\Http\Controllers\AccountController;
use Modules\TenantCore\Models\User;

class UserController extends AccountController
{
    protected string $model = User::class;

    protected string $title = 'User';

    public string $routePrefix = 'tenantcore.accounts';
}
