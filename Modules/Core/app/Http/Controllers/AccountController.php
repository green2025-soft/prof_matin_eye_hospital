<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Requests\AccountRequest;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Core\Services\AccessManager;
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

    public function index(ListRequest $request, AccessManager $access): View
    {
        $isTenantUser = $request->attributes->get('application_provider') === 'users';
        $modelClass = $this->modelClass();
        $model = new $modelClass;
        $query = $model->listQuery();

        if ($access->isOwner($request->user('web'))) {
            $query->without(['roles.permissions', 'permissions']);
        }

        return $this->listData($request, [
            'records' => $model->paginateRecords($request->validated(), $query),
            'filterOptions' => $model->queryContract()['filterable'],
        ])->with([
            'pageTitle' => $isTenantUser ? 'Users' : 'Accounts',
            'isTenantUser' => $isTenantUser,
            'accountModel' => $modelClass,
        ]);
    }

    public function store(AccountRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
