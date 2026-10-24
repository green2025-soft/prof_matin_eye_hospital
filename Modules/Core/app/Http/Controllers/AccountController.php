<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Modules\Core\Http\Requests\AccountRequest;
use Modules\Core\Services\AccountService;

abstract class AccountController extends BaseController
{
    protected string $service = AccountService::class;

    protected string $views = 'core::accounts';

    protected string $parameter = 'account';

    public function store(AccountRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
