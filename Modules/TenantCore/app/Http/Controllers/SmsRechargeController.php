<?php

namespace Modules\TenantCore\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Models\SmsAccount;
use Modules\Core\Models\SmsRecharge;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\SmsWalletService;
use Modules\TenantCore\Http\Requests\SmsRechargeRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SmsRechargeController extends BaseController
{
    protected string $model = SmsRecharge::class;

    protected string $parameter = 'sms_recharge';

    public function index(Request $request): View
    {
        Gate::authorize('viewWallet', SmsAccount::class);
        $filters = $request->only(['page', 'per_page']);
        $query = SmsRecharge::where('tenant_id', Tenant::current()->getKey())
            ->orderByDesc('id');
        $data = [
            'pageTitle' => 'SMS recharge history',
            'records' => $this->paginateQuery($query, $filters),
            'filters' => $filters,
            'routePrefix' => 'tenantcore.sms-recharges',
            'statuses' => SmsRecharge::STATUSES,
        ];

        return view('tenantcore::sms-recharges.index', $data);
    }

    public function create(Request $request): View
    {
        Gate::authorize('requestRecharge', SmsRecharge::class);
        $data = [
            'pageTitle' => 'Request SMS recharge',
            'account' => SmsAccount::where('tenant_id', Tenant::current()->getKey())->first(),
            'cancelUrl' => Gate::allows('viewWallet', SmsAccount::class) ? route('tenantcore.sms.index') : route('home'),
        ];

        return view('tenantcore::sms-recharges.form', $data);
    }

    public function store(SmsRechargeRequest $request, SmsWalletService $wallet): RedirectResponse
    {
        $wallet->requestRecharge($request->user('web'), $request->validated());

        $route = Gate::allows('viewWallet', SmsAccount::class) ? 'tenantcore.sms-recharges.index' : 'tenantcore.sms-recharges.create';

        return redirect()->route($route)->with('status', 'Recharge request submitted for review.');
    }

    public function proof(SmsRecharge $smsRecharge): StreamedResponse
    {
        Gate::authorize('viewProof', $smsRecharge);

        return $smsRecharge->tenant->execute(function () use ($smsRecharge): StreamedResponse {
            $disk = Storage::disk('local');
            abort_unless($smsRecharge->proof_path && $disk->exists($smsRecharge->proof_path), 404);

            return $disk->download($smsRecharge->proof_path);
        });
    }
}
