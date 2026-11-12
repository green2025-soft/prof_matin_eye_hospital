<?php

namespace Modules\Pharmacy\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Setting;
use Modules\Core\Models\SmsAccount;
use Modules\Pharmacy\Http\Requests\SettingRequest;

class SettingController extends BaseController
{
    protected string $model = Setting::class;

    protected string $title = 'Pharmacy settings';

    protected string $parameter = 'setting';

    public function index(ListRequest $request): View
    {
        $section = $request->query('section', 'general');
        abort_unless(is_string($section) && array_key_exists($section, config('settings.pharmacy_sections')), 404);
        $smsFeatures = SmsAccount::availableFeatures();
        $smsNotifications = array_filter(config('settings.pharmacy_sms_notifications'), fn (array $notification): bool => isset($smsFeatures[$notification['feature']]));
        abort_if($section === 'sms' && $smsNotifications === [], 403);

        $setting = Setting::where('group', 'pharmacy')
            ->firstOrFail(['id', 'group', 'data', 'updated_at']);

        $data = [
            'pageTitle' => $this->title,
            'setting' => $setting,
            'activePharmacySection' => $section,
            'smsNotifications' => $smsNotifications,
            'formAction' => route('pharmacy.settings.update', $setting),
            'values' => [
                'data' => $setting->publicValues(),
            ],
            'canUpdate' => Gate::forUser($request->user('web'))
                ->allows('update', $setting),
        ];

        return view('pharmacy::settings.index', $data);
    }

    public function store(SettingRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function redirectAfterWrite(?Model $record = null): RedirectResponse
    {
        return redirect()
            ->route('pharmacy.settings.index', ['section' => request()->input('section', 'general')]);
    }
}
