<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Accounting\Http\Requests\PaymentAccountRequest;
use Modules\Accounting\Models\AccountHead;
use Modules\Accounting\Models\PaymentAccount;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\TenantCore\Models\PaymentType;

class PaymentAccountController extends BaseController
{
    protected string $model = PaymentAccount::class;

    protected string $title = 'Payment ledger';

    protected string $views = 'accounting::payment-accounts';

    protected string $parameter = 'payment_account';

    protected bool $inlineForm = true;

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(PaymentAccountRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $data = [
            'paymentTypes' => PaymentType::orderBy('ordering')
                ->orderBy('id')
                ->get(['id', 'name']),
            'ledgers' => AccountHead::postable()
                ->where('type', 'asset')
                ->orderBy('code')
                ->get(['id', 'code', 'name']),
        ];

        return $data;
    }
}
