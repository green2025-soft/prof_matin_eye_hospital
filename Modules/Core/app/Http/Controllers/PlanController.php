<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Http\Requests\PlanRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Plan;

class PlanController extends BaseController
{
    protected string $model = Plan::class;

    protected string $title = 'Plan';

    protected string $views = 'core::plans';

    protected string $parameter = 'plan';

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(PlanRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function formData(Account $actor, Model $record): array
    {
        return [
            'price' => $record->price,
            'featuresText' => implode("\n", array_keys(array_filter($record->features ?? []))),
            'currency' => $record->currency ?? config('billing.currency', 'BDT'),
            'intervals' => Plan::INTERVALS,
        ];
    }
}
