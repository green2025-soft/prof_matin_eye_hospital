<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Requests\InvoiceActionRequest;
use Modules\Core\Http\Requests\InvoiceRequest;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Jobs\GenerateDueInvoices;
use Modules\Core\Models\Invoice;
use Modules\Core\Services\InvoiceService;

class InvoiceController extends BaseController
{
    protected string $model = Invoice::class;

    protected string $title = 'Invoice';

    protected string $service = InvoiceService::class;

    protected string $views = 'core::invoices';

    protected string $parameter = 'invoice';

    public function index(ListRequest $request): View
    {
        return $this->listData($request, app(InvoiceService::class)->indexData($request->user('web'), $request->validated()));
    }

    public function generate(): RedirectResponse
    {
        Gate::authorize('create', Invoice::class);
        $result = GenerateDueInvoices::dispatchSync(failOnError: false);
        $response = Gate::allows('viewAny', Invoice::class)
            ? redirect()->route('core.invoices.index')
            : redirect()->route('home');
        $response->with('status', 'Generated '.$result['generated'].' invoices; processed '.$result['processed'].' due subscriptions.');

        if ($result['failed'] !== []) {
            $response->with('warning', 'Some subscriptions failed: '.implode(', ', $result['failed']).'. Review the application log, fix the cause, and generate again.');
        }

        return $response;
    }

    public function store(InvoiceRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request, Invoice $invoice, InvoiceService $invoices): View
    {
        return view('core::invoices.show', $invoices->showData($request->user('web'), $invoice));
    }

    public function issue(InvoiceActionRequest $request, InvoiceService $invoices): RedirectResponse
    {
        $invoice = $invoices->issue($request->user('web'), $this->record($request));

        return redirect()->route('core.invoices.show', $invoice)->with('status', 'Invoice issued.');
    }

    public function void(InvoiceActionRequest $request, InvoiceService $invoices): RedirectResponse
    {
        $invoice = $invoices->void($request->user('web'), $this->record($request), $request->validated('reason'));

        return redirect()->route('core.invoices.show', $invoice)->with('status', 'Invoice voided.');
    }

    protected function redirectAfterWrite(?Model $record = null): RedirectResponse
    {
        return $record && Gate::allows('view', $record)
            ? redirect()->route('core.invoices.show', $record)
            : redirect()->route('home');
    }
}
