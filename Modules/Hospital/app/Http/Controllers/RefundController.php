<?php

namespace Modules\Hospital\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Hospital\Http\Requests\RefundRequest;
use Modules\Hospital\Models\Refund;
use Modules\Hospital\Models\RefundCause;
use Modules\Hospital\Services\RefundService;

class RefundController extends BaseController
{
    protected string $model = Refund::class;

    protected string $title = 'Refund';

    protected string $service = RefundService::class;

    protected string $views = 'hospital::refunds';

    protected string $parameter = 'refund';

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(RefundRequest $request, RefundService $entries): RedirectResponse
    {
        $refund = $entries->save(
            $request->user('web'),
            $this->record($request),
            $request->payload(),
            (int) $request->attributes->get('branch_id')
        );

        return $this->redirectAfterWrite($refund)
            ->with('status', 'Refund'.($this->isUpdate($request) ? ' updated.' : ' created.'));
    }

    public function show(Request $request, RefundService $entries): View
    {
        $data = $entries->invoiceData($request->user('web'), $this->record($request));

        return view('hospital::refunds.invoice', $data);
    }

    public function invoice(RefundRequest $request, RefundService $entries): JsonResponse
    {
        $data = $entries->preview(
            $request->user('web'),
            $request->validated('invoice'),
            (int) $request->attributes->get('branch_id'),
        );

        return response()->json($data);
    }

    public function invoices(RefundRequest $request, RefundService $entries): JsonResponse
    {
        $records = $entries->invoices($request->user('web'), (int) $request->attributes->get('branch_id'), $request->validated());
        $data = [
            'records' => $records,
        ];

        return JsonResponse::fromJsonString(view('hospital::selectors.invoices', $data)->render());
    }

    protected function formData(Account $actor, Model $record): array
    {
        $branchId = max(0, (int) request()->attributes->get('branch_id', 0));
        $selectedService = $record->exists ? $record->serviceType() : getValue('service', null, 'investigation');
        $entries = app(RefundService::class);
        $invoice = (string) getValue('invoice');
        $refundPreview = null;
        if ($record->exists) {
            $refundPreview = $entries->preview($actor, '', $branchId, $record);
        } elseif ($invoice !== '') {
            try {
                $refundPreview = $entries->preview($actor, $invoice, $branchId);
            } catch (ValidationException) {
                // Leave a stale invoice selection empty when reopening the form.
            }
        }
        $selectedInvoice = $refundPreview['invoice'] ?? null;

        $selectedItemIds = [];
        if ($refundPreview !== null) {
            $selectedInvoice['remaining_paid'] = $refundPreview['remaining_paid'];
            $selectedInvoice['remaining_due'] = $refundPreview['remaining_due'];
            $selectedInvoice['remaining_amount'] = $refundPreview['remaining_amount'];
            $availableIds = array_column($refundPreview['items'], 'id');
            if ($record->exists) {
                $selectedItemIds = $refundPreview['selected_item_ids'];
            } else {
                $hasOldInvoice = request()
                    ->hasSession() && request()
                    ->session()
                    ->hasOldInput('invoice');
                $selectedIds = $hasOldInvoice ? old('item_ids', []) : ($refundPreview['selected_item_ids'] ?: $availableIds);
                $selectedIds = is_array($selectedIds) ? array_filter(
                    $selectedIds,
                    fn ($id): bool => (is_int($id) && $id > 0)
                    || (is_string($id) && ctype_digit($id) && strlen($id) <= 18 && (int) $id > 0)
                ) : [];
                $selectedItemIds = array_values(array_intersect($availableIds, array_map('intval', $selectedIds)));
            }
        }

        return [
            'serviceOptions' => clinicalServiceOptions(),
            'selectedService' => $selectedService,
            'selectedInvoice' => $selectedInvoice,
            'refundPreview' => $refundPreview,
            'selectedItemIds' => $selectedItemIds,
            'refundCauses' => RefundCause::orderBy('ordering')
                ->orderBy('id')
                ->get(['id', 'name']),
        ];
    }
}
