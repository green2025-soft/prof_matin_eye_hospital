<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Core\Http\Requests\SmsRechargeRequest;
use Modules\Core\Models\SmsRecharge;
use Modules\Core\Services\SmsWalletService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SmsRechargeController extends BaseController
{
    protected string $model = SmsRecharge::class;

    protected string $parameter = 'sms_recharge';

    public function index(Request $request): View
    {
        Gate::authorize('viewRecharges', SmsRecharge::class);
        $filters = $request->only(['status', 'page', 'per_page']);
        $status = $request->string('status')->toString();
        $query = SmsRecharge::with('tenant:id,name')
            ->orderByDesc('id');

        if (array_key_exists($status, SmsRecharge::STATUSES)) {
            $query->where('status', $status);
        }

        $data = [
            'pageTitle' => 'SMS recharges',
            'records' => $this->paginateQuery($query, $filters),
            'filters' => $filters,
            'routePrefix' => $this->routePrefix(),
            'statuses' => SmsRecharge::STATUSES,
        ];

        return view('core::sms-recharges.index', $data);
    }

    public function show(SmsRecharge $smsRecharge): View
    {
        Gate::authorize('viewRecharges', SmsRecharge::class);
        $smsRecharge->load('tenant:id,name');

        $data = [
            'pageTitle' => 'SMS recharge #'.$smsRecharge->getKey(),
            'recharge' => $smsRecharge,
            'statusLabel' => SmsRecharge::STATUSES[$smsRecharge->status],
        ];

        return view('core::sms-recharges.show', $data);
    }

    public function store(SmsRechargeRequest $request, SmsWalletService $wallet): RedirectResponse
    {
        $recharge = $this->record($request);
        $actor = $request->user('web');
        $note = $request->validated('review_note');

        if ($request->validated('decision') === 'approve') {
            $wallet->approve($actor, $recharge, $note);
        } else {
            $wallet->reject($actor, $recharge, $note);
        }

        return Gate::allows('viewRecharges', SmsRecharge::class)
            ? redirect()
                ->route('core.sms-recharges.show', $recharge)
                ->with('status', 'Recharge review saved.')
            : redirect()
                ->route('home')
                ->with('status', 'Recharge review saved.');
    }

    public function proof(SmsRecharge $smsRecharge): StreamedResponse
    {
        Gate::authorize('viewProof', $smsRecharge);

        return $smsRecharge->tenant->execute(function () use ($smsRecharge): StreamedResponse {
            $disk = Storage::disk('local');
            abort_unless($smsRecharge->proof_path && $disk->exists($smsRecharge->proof_path), 404);

            return $disk->download($smsRecharge->proof_path);
        });
    }
}
