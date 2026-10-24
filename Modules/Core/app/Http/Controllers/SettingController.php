<?php

namespace Modules\Core\Http\Controllers;

use DateTimeZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Http\Requests\SettingRequest;
use Modules\Core\Models\Setting;

class SettingController extends BaseController
{
    protected string $model = Setting::class;

    protected string $title = 'Setting';

    protected string $views = 'core::settings';

    protected string $parameter = 'setting';

    public function index(ListRequest $request): View
    {
        $group = $request->query('group', 'company');
        $group = is_string($group) ? $group : 'company';
        $paymentGroups = config('settings.payment_groups');

        if ($group === 'payments') {
            $provider = $request->query('provider', 'manual');
            abort_unless(is_string($provider) && in_array($provider, $paymentGroups, true), 404);
            $group = $provider;
        }

        $setting = Setting::query()->where('group', $group)->firstOrFail();
        $isPayment = in_array($setting->group, $paymentGroups, true);

        return $this->listData($request, $this->groupData($setting) + [
            'setting' => $setting,
            'activeGroup' => $isPayment ? 'payments' : $setting->group,
            'activeProvider' => $isPayment ? $setting->group : null,
            'isPlatform' => $request->attributes->get('application_scope') === 'central',
            'formAction' => route($this->routePrefix().'.update', $setting),
            'canUpdate' => Gate::forUser($request->user('web'))->allows('update', $setting),
        ]);
    }

    public function edit(Request $request): RedirectResponse
    {
        $setting = $this->record($request);
        Gate::authorize('update', $setting);

        return $this->redirectAfterWrite($setting);
    }

    public function store(SettingRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function redirectAfterWrite(?Model $record = null): RedirectResponse
    {
        $group = $record instanceof Setting ? $record->group : 'company';
        $isPayment = in_array($group, config('settings.payment_groups'), true);

        return redirect()->route($this->routePrefix().'.index', [
            'group' => $isPayment ? 'payments' : $group,
            'provider' => $isPayment ? $group : null,
        ]);
    }

    private function groupData(Setting $setting): array
    {
        $group = config('settings.groups.'.$setting->group);
        $values = $setting->publicValues();
        $configured = $setting->configuredCredentials();
        $setting->setRawAttributes(Arr::only($setting->getAttributes(), ['id', 'group', 'data', 'updated_at']), true);

        return [
            'groupLabel' => $group['label'],
            'groupDescription' => $group['description'],
            'groupIcon' => $group['icon'],
            'values' => ['data' => $values],
            'credentialFields' => config('settings.credentials.'.$setting->group, []),
            'configuredCredentials' => $configured,
            'timezones' => $setting->group === 'general' ? DateTimeZone::listIdentifiers() : [],
        ];
    }
}
