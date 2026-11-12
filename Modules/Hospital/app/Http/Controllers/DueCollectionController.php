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
use Modules\Hospital\Http\Requests\DueCollectionRequest;
use Modules\Hospital\Models\DueCollection;
use Modules\Hospital\Services\DueCollectionService;
use Modules\TenantCore\Models\PaymentType;

class DueCollectionController extends BaseController
{
    protected string $model = DueCollection::class;

    protected string $title = 'Due collection';

    protected string $service = DueCollectionService::class;

    protected string $views = 'hospital::due-collections';

    protected string $parameter = 'due_collection';

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(DueCollectionRequest $request, DueCollectionService $entries): RedirectResponse
    {
        $collection = $entries->save(
            $request->user('web'),
            $this->record($request),
            $request->payload(),
            (int) $request->attributes->get('branch_id')
        );

        return $this->redirectAfterWrite($collection)
            ->with('status', 'Due collection'.($this->isUpdate($request) ? ' updated.' : ' created.'));
    }

    public function show(Request $request, DueCollectionService $entries): View
    {
        $data = $entries->invoiceData($request->user('web'), $this->record($request));

        return view('hospital::due-collections.invoice', $data);
    }

    public function invoices(DueCollectionRequest $request, DueCollectionService $entries): JsonResponse
    {
        $records = $entries->invoices($request->user('web'), (int) $request->attributes->get('branch_id'), $request->validated());
        $data = [
            'records' => $records,
        ];

        return JsonResponse::fromJsonString(view('hospital::selectors.invoices', $data)->render());
    }

    public function invoice(DueCollectionRequest $request, DueCollectionService $entries): JsonResponse
    {
        $data = $entries->preview(
            $request->user('web'),
            $request->validated('invoice'),
            (int) $request->attributes->get('branch_id'),
        );

        return response()->json($data);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $branchId = max(0, (int) request()->attributes->get('branch_id', 0));
        $selectedService = $record->exists ? $record->serviceType() : getValue('service', null, 'all');
        $entries = app(DueCollectionService::class);
        $invoice = (string) getValue('invoice');
        $collectionPreview = null;
        if ($record->exists) {
            $collectionPreview = $entries->preview($actor, '', $branchId, $record);
        } elseif ($invoice !== '') {
            try {
                $collectionPreview = $entries->preview($actor, $invoice, $branchId);
            } catch (ValidationException) {
                // Leave a stale invoice selection empty when reopening the form.
            }
        }

        return [
            'serviceOptions' => clinicalServiceOptions(),
            'selectedService' => $selectedService,
            'selectedInvoice' => $collectionPreview['invoice'] ?? null,
            'collectionPreview' => $collectionPreview,
            'collectionDate' => $record->collection_date?->toDateString() ?? now(config('app.timezone'))
                ->toDateString(),
            'paymentTypes' => PaymentType::orderBy('ordering')
                ->orderBy('id')
                ->get(['id', 'name']),
        ];
    }
}
