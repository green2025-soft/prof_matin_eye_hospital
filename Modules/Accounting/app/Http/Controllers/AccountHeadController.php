<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Accounting\Http\Requests\AccountHeadRequest;
use Modules\Accounting\Models\AccountHead;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;

class AccountHeadController extends BaseController
{
    protected string $model = AccountHead::class;

    protected string $title = 'Account head';

    protected string $views = 'accounting::account-heads';

    protected string $parameter = 'account_head';

    public function index(ListRequest $request): View
    {
        $data = [
            'records' => (new AccountHead)->listQuery()->without('parent')
                ->orderBy('code')->orderBy('id')->get(),
            'canCreateAccount' => Gate::forUser($request->user('web'))
                ->allows('create', AccountHead::class),
        ];

        return $this->listData($request, $data);
    }

    public function store(AccountHeadRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $parents = AccountHead::where('active', true)->where('depth', '<', 5)
            ->orderBy('code')->get(['id', 'code', 'name', 'depth']);

        $data = [
            'parents' => $parents,
            'selectedParent' => $parents->find(getValue('parent_id', $record, request()->integer('parent_id'))),
            'reportRoles' => AccountHead::REPORT_ROLES,
            'cashFlowActivities' => AccountHead::CASH_FLOW_ACTIVITIES,
        ];

        return $data;
    }
}
