<?php

namespace Modules\Prescription\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Models\Setting;
use Modules\Prescription\Http\Requests\SettingRequest;

class SettingController extends BaseController
{
    protected string $model = Setting::class;

    protected string $title = 'Prescription settings';

    protected string $parameter = 'setting';

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Setting::class);

        $setting = Setting::where('group', 'prescription')->firstOrFail();

        $data = [
            'pageTitle'     => 'Prescription settings',
            'setting'       => $setting,
            'values'        => $setting->publicValues(),
            'canUpdate'     => Gate::forUser($request->user('web'))->allows('update', $setting),
        ];

        return view('prescription::settings.index', $data);
    }

    public function store(SettingRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
