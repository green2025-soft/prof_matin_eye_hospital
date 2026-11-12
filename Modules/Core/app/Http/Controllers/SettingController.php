<?php

namespace Modules\Core\Http\Controllers;

use DateTimeZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Http\Requests\SettingRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Nwidart\Modules\Facades\Module;

class SettingController extends BaseController
{
    protected string $model = Setting::class;

    protected string $title = 'Setting';

    protected string $views = 'core::settings';

    protected string $parameter = 'setting';

    public function index(ListRequest $request): View|RedirectResponse
    {
        $group = $request->query('group', 'company');
        $group = is_string($group) ? $group : 'company';
        $group = config('settings.group_aliases', [])[$group] ?? $group;
        $route = config('settings.groups.'.$group.'.route');

        if ($route !== null) {
            abort_unless(Route::has($route), 404);

            return redirect()
                ->route($route, Arr::only($request->query(), ['section']), 303);
        }

        $paymentGroups = config('settings.payment_groups');

        if ($group === 'payments') {
            $provider = $request->query('provider', 'manual');
            abort_unless(is_string($provider) && in_array($provider, $paymentGroups, true), 404);
            $group = $provider;
        }

        $setting = Setting::where('group', $group)
            ->firstOrFail();
        $isPayment = in_array($setting->group, $paymentGroups, true);
        $data = $this->groupData($setting) + [
            'setting' => $setting,
            'activeGroup' => $isPayment ? 'payments' : $setting->group,
            'activeProvider' => $isPayment ? $setting->group : null,
            'isPlatform' => $request->attributes->get('application_scope') === 'central',
            'formAction' => route($this->routePrefix().'.update', $setting),
            'canUpdate' => Gate::forUser($request->user('web'))
                ->allows('update', $setting),
        ];

        return $this->listData($request, $data);
    }

    public function edit(Request $request): RedirectResponse
    {
        $setting = $this->record($request);
        Gate::authorize('update', $setting);

        return $this->redirectAfterWrite($setting);
    }

    public function store(SettingRequest $request): RedirectResponse
    {
        if (! $request->hasFile('logo') && ! $request->hasFile('favicon')) {
            return $this->saveData($request);
        }

        $setting = $this->record($request);
        $disk = Storage::disk('public');
        $setting->writeTransaction($request->user('web'), function (Account $actor) use ($setting, $request, $disk): void {
            $target = $setting->newQuery()
                ->whereKey($setting->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            Gate::forUser($actor)->authorize('update', $target);
            $values = $request->payload();
            $previous = $target->publicValues();

            foreach (['logo', 'favicon'] as $field) {
                if (! $request->hasFile($field)) {
                    continue;
                }

                $path = $disk->putFile('branding', $request->file($field));
                if ($path === false) {
                    throw ValidationException::withMessages([
                        $field => 'The image could not be stored. Please try again.',
                    ]);
                }

                $target->getConnection()
                    ->afterRollBack(fn () => $disk->delete($path));
                $values['data'][$field.'_path'] = $path;

                if ($oldPath = $previous[$field.'_path']) {
                    $target->getConnection()
                        ->afterCommit(fn () => $disk->delete($oldPath));
                }
            }

            $target->fill($values)
                ->save();
        });

        return $this->redirectAfterWrite($setting)
            ->with('status', 'Branding updated.');
    }

    protected function redirectAfterWrite(?Model $record = null): RedirectResponse
    {
        $group = $record instanceof Setting ? $record->group : 'company';
        $isPayment = in_array($group, config('settings.payment_groups'), true);

        return redirect()
            ->route($this->routePrefix().'.index', [
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
            'values' => [
                'data' => $values,
            ],
            'credentialFields' => config('settings.credentials.'.$setting->group, []),
            'configuredCredentials' => $configured,
            'timezones' => $setting->group === 'general' ? DateTimeZone::listIdentifiers() : [],
            'orderableModules' => $setting->group === 'general' && $setting->getConnection()->getName() === 'tenant'
                ? Module::getOrdered('desc') : [],
            'logoUrl' => ! empty($values['logo_path'])
                ? Storage::disk('public')
                    ->url($values['logo_path'])
                : null,
            'faviconUrl' => ! empty($values['favicon_path'])
                ? Storage::disk('public')
                    ->url($values['favicon_path'])
                : null,
        ];
    }
}
