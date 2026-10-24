<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\View\View;
use Modules\Core\Http\Requests\BillingRequest;
use Modules\Core\Models\Invoice;
use Modules\Core\Services\InvoiceService;

class BillingController extends BaseController
{
    protected string $model = Invoice::class;

    protected string $title = 'Billing';

    protected string $views = 'core::billing';

    protected string $parameter = 'invoice';

    public function index(BillingRequest $request, InvoiceService $invoices): View
    {
        return $this->listData($request, $invoices->billingData($request->user('web'), $request->validated()));
    }
}
