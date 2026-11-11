<?php

namespace Modules\Hospital\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Modules\Hospital\Http\Requests\CommissionRequest;
use Modules\Hospital\Models\Reference;
use Modules\Hospital\Services\ClinicalEntryService;

class CommissionController extends Controller
{
    public function index(CommissionRequest $request, ClinicalEntryService $entries): View
    {
        $actor = $request->user('web');
        $permissions = $request->attributes->get('navigation_permissions', []);
        $filters = $request->filters();
        $branchId = (int) $request->attributes->get('branch_id');
        $selectedReference = null;
        if (filled($filters['filter_reference_id'] ?? null)) {
            $selectedReference = Reference::where('branch_id', $branchId)
                ->whereKey($filters['filter_reference_id'])
                ->first(['id', 'reference_id', 'name', 'contact_no']);
        }

        $data = [
            'pageTitle' => 'Commission',
            'routePrefix' => 'hospital.commissions',
            'records' => $entries->commissions($actor, $filters, $branchId),
            'filters' => $filters,
            'selectedReference' => $selectedReference,
            'canManageCommissions' => isset($permissions['commissions.update']),
        ];

        return view('hospital::commissions.index', $data);
    }

    public function update(CommissionRequest $request, ClinicalEntryService $entries): RedirectResponse
    {
        $invoiceId = filter_var(
            $request->route('invoice'),
            FILTER_VALIDATE_INT,
            [
                'options' => [
                    'min_range' => 1,
                ],
            ]
        );
        abort_if($invoiceId === false, 404);
        $status = $request->validated('target_status');
        $filters = $request->filters();

        try {
            $entries->setCommissionStatus(
                $request->user('web'),
                (string) $request->route('service'),
                $invoiceId,
                $status,
                (int) $request->attributes->get('branch_id'),
            );
        } catch (ValidationException $exception) {
            throw $exception->redirectTo(route('hospital.commissions.index', $filters));
        }

        return redirect()
            ->route('hospital.commissions.index', $filters)
            ->with('status', 'Commission marked '.($status === 'paid' ? 'Paid.' : 'Unpaid.'));
    }
}
