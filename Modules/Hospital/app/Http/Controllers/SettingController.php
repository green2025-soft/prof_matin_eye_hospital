<?php

namespace Modules\Hospital\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Setting;
use Modules\Core\Models\SmsAccount;
use Modules\Hospital\Http\Requests\SettingRequest;

class SettingController extends BaseController
{
    protected string $model = Setting::class;

    protected string $title = 'Hospital settings';

    protected string $views = 'hospital::settings';

    protected string $parameter = 'setting';

    public function index(ListRequest $request): View
    {
        $section = $request->query('section', 'patient');
        abort_unless(is_string($section) && array_key_exists($section, config('settings.hospital_sections')), 404);
        $routePrefix = $this->routePrefix();
        $smsNotifications = Arr::only(config('settings.hospital_sms_notifications'), array_keys(SmsAccount::availableFeatures()));
        abort_if($section === 'sms' && $smsNotifications === [], 403);

        $setting = Setting::where('group', 'hospital')
            ->firstOrFail(['id', 'group', 'data', 'updated_at']);

        $data = [
            'pageTitle' => $this->title,
            'setting' => $setting,
            'values' => [
                'data' => $setting->publicValues(),
            ],
            'groupLabel' => config('settings.groups.hospital.label'),
            'groupIcon' => config('settings.groups.hospital.icon'),
            'activeHospitalSection' => $section,
            'smsNotifications' => $smsNotifications,
            'routePrefix' => $routePrefix,
            'formAction' => route($routePrefix.'.update', $setting),
            'canUpdate' => Gate::forUser($request->user('web'))
                ->allows('update', $setting),
        ];

        return view('hospital::settings.index', $data);
    }

    public function store(SettingRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function redirectAfterWrite(?Model $record = null): RedirectResponse
    {
        $section = request()->input('section', 'patient');
        $section = is_string($section) && array_key_exists($section, config('settings.hospital_sections')) ? $section : 'patient';

        return redirect()
            ->route($this->routePrefix().'.index', [
                'section' => $section,
            ]);
    }
}
