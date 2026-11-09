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
        $data = $view->getData();
        $dimensions = [
            'a4' => ['width' => 210, 'height' => 297],
            'a5' => ['width' => 148, 'height' => 210],
            'custom' => $data['customPaperSize'],
        ];
        $selectors = [
            'a4' => '.app-prescription-document',
            'a5' => '.app-prescription-paper-a5',
            'custom' => '.app-prescription-paper-custom',
        ];
        $paper = $dimensions[$data['paperSize']];
        $smallPaper = $data['paperSize'] === 'a5';
        $defaultMargin = $smallPaper ? 8 : 10;
        $margins = array_map(
            static fn (string $value): float => $value === '' ? $defaultMargin : (float) $value,
            $data['printMargins'],
        );
        // Keep the footer clear of browser/printer rounding at the page boundary.
        $contentHeight = max(1, (float) $paper['height'] - $margins['top'] - $margins['bottom'] - 3);

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
            'printConfiguration' => [
                'page' => 'prescription-'.$data['paperSize'],
                'paperSize' => $paper['width'].'mm '.$paper['height'].'mm',
                'paperWidth' => $paper['width'].'mm',
                'paperHeight' => $paper['height'].'mm',
                'margins' => implode('mm ', $margins).'mm',
                'minHeight' => $contentHeight.'mm',
                'documentSelector' => $selectors[$data['paperSize']],
            ],
        ]);
    }
}
