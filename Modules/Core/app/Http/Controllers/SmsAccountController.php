<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Helpers\Money;
use Modules\Core\Http\Requests\SmsAccountRequest;
use Modules\Core\Models\SmsAccount;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\SmsWalletService;

class SmsAccountController extends BaseController
{
    protected string $model = SmsAccount::class;

    protected string $parameter = 'sms_account';

    protected string $title = 'SMS account';

    protected string $service = SmsWalletService::class;

    public function index(Request $request): View
    {
        Gate::authorize('viewAccounts', SmsAccount::class);
        $filters = $request->only(['search', 'page', 'per_page']);
        $search = $request->string('search')->trim()->toString();
        $query = Tenant::with('smsAccount:id,tenant_id,enabled,balance,rate,monthly_limit,allowed_features')
            ->where('status', '!=', Tenant::STATUS_DELETING)
            ->orderBy('name')
            ->orderBy('id');
        if ($search !== '') {
            $pattern = '%'.addcslashes($search, '\\%_').'%';
            $query->where(fn ($tenants) => $tenants
                ->whereLike('name', $pattern)
                ->orWhereLike('slug', $pattern));
        }

        $data = [
            'pageTitle' => 'SMS accounts',
            'records' => $this->paginateQuery($query, $filters),
            'filters' => $filters,
            'routePrefix' => $this->routePrefix(),
            'accountModel' => SmsAccount::class,
        ];

        return view('core::sms-accounts.index', $data);
    }

    public function create(Request $request): View
    {
        Gate::authorize('manageAccounts', SmsAccount::class);

        return $this->accountForm(new SmsAccount, $request->integer('tenant_id') ?: null);
    }

    public function edit(Request $request): View
    {
        $account = $this->record($request);
        Gate::authorize('manageAccounts', $account);

        return $this->accountForm($account);
    }

    public function store(SmsAccountRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function redirectAfterWrite(?Model $record = null): RedirectResponse
    {
        if (Gate::allows('viewAccounts', SmsAccount::class)) {
            return redirect()->route('core.sms-accounts.index');
        }

        return $record && Gate::allows('manageAccounts', $record)
            ? redirect()->route('core.sms-accounts.edit', $record)
            : redirect()->route('home');
    }

    private function accountForm(SmsAccount $account, ?int $selectedTenantId = null): View
    {
        $account->loadMissing('tenant:id,name');
        $gateway = $account->gateway ?? [];
        $data = [
            'pageTitle' => $account->exists ? 'Edit SMS account' : 'New SMS account',
            'account' => $account,
            'tenants' => $account->exists ? collect() : Tenant::doesntHave('smsAccount')
                ->where('status', '!=', Tenant::STATUS_DELETING)
                ->orderBy('name')
                ->get(['id', 'name']),
            'selectedTenantId' => $selectedTenantId,
            'features' => config('settings.sms_features', []),
            'rate' => Money::decimal($account->rate ?? 0),
            'gatewayValues' => Arr::only($gateway, ['api_url', 'method', 'body_format', 'sender_id', 'success_field', 'success_value', 'failure_values']),
            'gatewayConfigured' => array_map(fn ($value): bool => filled($value), Arr::only($gateway, array_keys(config('settings.credentials.sms')))),
            'hasGateway' => $gateway !== [],
            'credentialFields' => config('settings.credentials.sms'),
            'formAction' => $account->exists ? route('core.sms-accounts.update', $account) : route('core.sms-accounts.store'),
            'formMethod' => $account->exists ? 'PUT' : 'POST',
        ];

        return view('core::sms-accounts.form', $data);
    }
}
