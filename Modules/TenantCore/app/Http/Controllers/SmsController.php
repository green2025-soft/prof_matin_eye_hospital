<?php

namespace Modules\TenantCore\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Models\SmsAccount;
use Modules\Core\Models\SmsMessage;
use Modules\Core\Models\SmsRecharge;
use Modules\Core\Models\Tenant;

class SmsController extends BaseController
{
    protected string $model = SmsMessage::class;

    public function index(Request $request): View
    {
        Gate::authorize('viewWallet', SmsAccount::class);
        $tenantId = Tenant::current()->getKey();
        $filters = $request->only(['page', 'per_page']);
        $account = SmsAccount::where('tenant_id', $tenantId)
            ->first();
        $messages = SmsMessage::where('tenant_id', $tenantId)
            ->orderByDesc('id');
        $data = [
            'pageTitle' => 'SMS wallet',
            'account' => $account,
            'records' => $this->paginateQuery($messages, $filters),
            'filters' => $filters,
            'routePrefix' => 'tenantcore.sms',
            'rechargeModel' => SmsRecharge::class,
            'recharges' => SmsRecharge::where('tenant_id', $tenantId)->orderByDesc('id')->limit(5)->get(),
            'statuses' => SmsMessage::STATUSES,
            'rechargeStatuses' => SmsRecharge::STATUSES,
            'features' => config('settings.sms_features', []),
        ];

        return view('tenantcore::sms.index', $data);
    }
}
