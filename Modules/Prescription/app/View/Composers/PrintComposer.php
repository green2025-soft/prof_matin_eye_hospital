<?php

namespace Modules\Prescription\View\Composers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Core\Models\Setting;
use Modules\TenantCore\Models\Branch;

class PrintComposer
{
    public function __construct(private Request $request) {}

    public function compose(View $view): void
    {
        $branch = Branch::query()->findOrFail($this->request->attributes->get('branch_id'));
        $company = Setting::values('company', $branch->getConnectionName());
        $branding = Setting::values('branding', $branch->getConnectionName());
        $phone = implode(' / ', array_filter([$branch->mobile_no, $branch->telephone_no]));
        $logo = $branch->logo_path ?: $branding['logo_path'];

        $view->with([
            'company' => [
                'name' => $company['name'] ?: $branding['app_name'],
                'branch_name' => $branch->name,
                'address' => $branch->address ?: $company['address'],
                'phone' => $phone ?: $company['phone'],
                'email' => $branch->email ?: $company['email'],
            ],
            'logoUrl' => filled($logo) ? Storage::disk('public')->url($logo) : null,
            'printedBy' => $this->request->user('web')->name,
            'printedAt' => now(config('app.timezone'))->format('d M Y, h:i A'),
        ]);
    }
}
