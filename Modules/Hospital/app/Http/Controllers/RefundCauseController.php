<?php

namespace Modules\Hospital\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Hospital\Http\Requests\RefundCauseRequest;
use Modules\Hospital\Models\RefundCause;

class RefundCauseController extends BaseController
{
    protected string $model = RefundCause::class;

    protected string $title = 'Refund cause';

    protected string $views = 'hospital::refund-causes';

    protected string $parameter = 'refund_cause';

    protected bool $inlineForm = true;

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(RefundCauseRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
