<?php

namespace Modules\TenantCore\View\Composers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Core\Models\Setting;
use Modules\TenantCore\Models\Branch;

class ReportComposer
{
    public function __construct(private Request $request) {}

    public function compose(View $view): void
    {
        $branch = Branch::findOrFail($this->request->attributes->get('branch_id'));
        $company = Setting::values('company', $branch->getConnectionName());
        $branding = Setting::values('branding', $branch->getConnectionName());
        $name = $company['name'] ?: $branding['app_name'];
        $logoPath = $branch->logo_path ?: $branding['logo_path'];
        $phone = implode(' / ', array_filter([$branch->mobile_no, $branch->telephone_no]));

        $view->with([
            'reportBrand' => [
                'name' => $name,
                'branch_name' => ! $branch->is_default && $branch->name !== $name ? $branch->name : '',
                'tagline' => $branding['tagline'],
                'address' => $branch->address ?: $company['address'],
                'contact' => implode(' · ', array_filter([$phone ?: $company['phone'], $branch->email ?: $company['email']])),
                'logo_url' => filled($logoPath) ? Storage::disk('public')->url($logoPath) : null,
            ],
            'printedBy' => $this->request->user('web')->name,
            'printedAt' => now(config('app.timezone'))->format('d M Y, h:i A'),
        ]);
    }
}
