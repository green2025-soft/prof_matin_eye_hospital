<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Accounting\Http\Requests\JournalEntryRequest;
use Modules\Accounting\Models\AccountHead;
use Modules\Accounting\Models\JournalEntry;
use Modules\Accounting\Models\VoucherType;
use Modules\Accounting\Services\JournalService;
use Modules\Core\Helpers\Money;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\TenantCore\Models\Branch;

class JournalEntryController extends BaseController
{
    protected string $model = JournalEntry::class;

    protected string $title = 'Voucher';

    protected string $service = JournalService::class;

    protected string $views = 'accounting::journal-entries';

    protected string $parameter = 'journal_entry';

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(JournalEntryRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request, JournalEntry $journalEntry): View
    {
        $actor = $request->user('web');
        Gate::forUser($actor)->authorize('view', $journalEntry);
        $journalEntry->load(['voucherType:id,name', 'lines.accountHead:id,code,name']);

        $branch = Branch::find($journalEntry->branch_id, ['id', 'name', 'address', 'mobile_no', 'telephone_no', 'email', 'logo_path']);
        $company = Setting::values('company', $journalEntry->getConnectionName());
        $branding = Setting::values('branding', $journalEntry->getConnectionName());
        $branchPhone = implode(' / ', array_filter([$branch?->mobile_no, $branch?->telephone_no]));
        $logoPath = $branch?->logo_path ?: $branding['logo_path'];

        $data = [
            'pageTitle' => $journalEntry->number,
            'journal_entry' => $journalEntry,
            'company' => [
                'name' => $branch?->name ?: ($company['name'] ?: $branding['app_name']),
                'address' => $branch?->address ?: $company['address'],
                'phone' => $branchPhone ?: $company['phone'],
                'email' => $branch?->email ?: $company['email'],
            ],
            'logoUrl' => filled($logoPath) ? Storage::disk('public')->url($logoPath) : null,
            'amountWords' => Money::words($journalEntry->total),
            'printedBy' => $actor->name,
            'printedAt' => now(config('app.timezone'))->format('d M Y h:i A'),
            'routePrefix' => $this->routePrefix(),
        ];

        return view('accounting::journal-entries.show', $data);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $data = [
            'accountHeads' => AccountHead::postable()
                ->orderBy('code')
                ->get(['id', 'code', 'name']),
            'voucherTypes' => VoucherType::where('active', true)
                ->where('is_manual', true)
                ->orderBy('name')
                ->orderBy('id')
                ->get(['id', 'name']),
            'entryDate' => $record->entry_date?->toDateString() ?? now(config('app.timezone'))->toDateString(),
        ];

        return $data;
    }
}
