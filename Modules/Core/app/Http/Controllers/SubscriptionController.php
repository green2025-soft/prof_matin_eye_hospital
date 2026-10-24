<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Http\Requests\SubscriptionRequest;
use Modules\Core\Models\Subscription;
use Modules\Core\Services\SubscriptionService;

class SubscriptionController extends BaseController
{
    protected string $model = Subscription::class;

    protected string $title = 'Subscription';

    protected string $service = SubscriptionService::class;

    protected string $views = 'core::subscriptions';

    protected string $parameter = 'subscription';

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(SubscriptionRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
