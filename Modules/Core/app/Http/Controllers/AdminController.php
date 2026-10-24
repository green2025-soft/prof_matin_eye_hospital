<?php

namespace Modules\Core\Http\Controllers;

use Modules\Core\Models\Admin;

class AdminController extends AccountController
{
    protected string $model = Admin::class;

    protected string $title = 'Administrator';

    public string $routePrefix = 'core.accounts';
}
