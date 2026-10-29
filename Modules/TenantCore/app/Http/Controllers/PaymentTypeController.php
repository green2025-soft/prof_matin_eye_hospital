<?php

namespace Modules\TenantCore\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\TenantCore\Http\Requests\PaymentTypeRequest;
use Modules\TenantCore\Models\PaymentType;

class PaymentTypeController extends BaseController
{
    protected string $model = PaymentType::class;

    protected string $title = 'Payment type';

    protected string $views = 'tenantcore::payment-types';

    protected string $parameter = 'payment_type';

    protected bool $inlineForm = true;

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(PaymentTypeRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
