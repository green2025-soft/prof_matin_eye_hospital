<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Requests\AccountRequest;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Core\Services\AccountService;

class AccountController extends BaseController
{
    protected string $title = 'Account';

    protected string $service = AccountService::class;

    protected string $views = 'core::accounts';

    protected string $parameter = 'account';

    public function modelClass(): string
    {
        return Account::modelForProvider(request()->attributes->get('application_provider'));
    }

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(AccountRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
