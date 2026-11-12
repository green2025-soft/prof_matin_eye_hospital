<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Accounting\Http\Requests\AccountModuleRequest;
use Modules\Accounting\Models\AccountHead;
use Modules\Accounting\Models\AccountModule;
use Modules\Accounting\Models\VoucherType;
use Modules\Accounting\Services\AccountModuleService;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;

class AccountModuleController extends BaseController
{
    protected string $model = AccountModule::class;

    protected string $title = 'Posting mapping';

    protected string $views = 'accounting::account-modules';

    protected string $parameter = 'account_module';

    protected string $service = AccountModuleService::class;

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(AccountModuleRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $selectedIds = $record->components->pluck('account_head_id')
            ->merge(array_column($this->oldRows('components'), 'account_head_id'));

        $data = [
            'selectedLedgers' => AccountHead::whereKey($selectedIds)
                ->get(['id', 'code', 'name', 'active'])->keyBy('id'),
            'voucherTypes' => VoucherType::where('active', true)->orderBy('name')->get(['id', 'name']),
        ];

        return $data;
    }
}
