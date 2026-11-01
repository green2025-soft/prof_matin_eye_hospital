<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Hospital\Contracts\InventoryCustomers;
use Modules\Inventory\Http\Requests\SaleRequest;
use Modules\Inventory\Models\Document;
use Modules\Inventory\Models\Sale;
use Modules\Inventory\Services\InventoryAccess;
use Modules\Inventory\Services\ItemService;
use Modules\Inventory\Services\SaleService;
use Modules\Inventory\Services\StockService;
use Modules\TenantCore\Models\PaymentType;

class SaleController extends BaseController
{
    protected string $model = Sale::class;

    protected string $service = SaleService::class;

    protected string $title = 'Sale';

    protected string $views = 'inventory::sales';

    protected string $parameter = 'sale';

    protected array $routeFilters = ['product_type' => 'product_type'];

    protected array $defaultFilters = ['product_type' => 'medicine'];

    public function index(ListRequest $request): View
    {
        $view = $this->listData($request);
        $request->attributes->set('inventory_product_type', $view->getData()['filters']['product_type']);
        $actor = $request->user('web');
        $access = app(InventoryAccess::class);
        $customerIds = $view->getData()['records']->pluck('customer_id')->filter()->unique()->all();

        return $view->with([
            'resourceParameter' => $this->parameter,
            'canCreate' => $access->allows($actor, 'inventory-sales.create'),
            'canUpdateSales' => $access->allows($actor, 'inventory-sales.update'),
            'canDeleteSales' => $access->allows($actor, 'inventory-sales.delete'),
            'customers' => $customerIds === [] ? [] : app(InventoryCustomers::class)->findMany($actor, $customerIds),
        ]);
    }

    public function store(SaleRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request, Sale $sale, SaleService $entries): View
    {
        $request->attributes->set('inventory_product_type', $sale->product_type);
        $settings = Setting::values('inventory', $sale->getConnectionName());

        return view($this->views.'.show', $entries->invoiceData($request->user('web'), $sale) + [
            'routePrefix' => $this->routePrefix(), 'resourceParameter' => $this->parameter,
            'invoicePaper' => $settings['sale_invoice_paper'], 'salePosWidth' => $settings['sale_pos_width_mm'],
        ]);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $productType = $record->exists ? $record->product_type : request()->query('product_type', 'medicine');
        $productType = in_array($productType, ['medicine', 'optic'], true) ? $productType : 'medicine';
        request()->attributes->set('inventory_product_type', $productType);
        if ($record->exists) {
            $record->load([
                'lines' => fn ($query) => $query->orderBy('id'),
                'lines.variant.product:id,name,type,purchase_price,sale_price',
                'lines.variant.product.units' => fn ($query) => $query->orderBy('factor')->orderBy('id'),
                'lines.variant.product.units.unit:id,name,symbol',
            ]);
        }
        $savedLines = $record->exists ? $record->lines->keyBy('id') : collect();
        $hasOldLines = array_key_exists('lines', request()->session()->getOldInput());
        $lineInputs = $hasOldLines ? $this->oldRows('lines') : [];
        $settings = Setting::values('inventory', $record->getConnectionName());
        if ($record->exists) {
            $settings['vat_enabled'] = $record->vat_mode !== 'none';
            $settings['vat_mode'] = $record->vat_mode;
            $settings['vat_percent'] = $record->vat_percent;
        }
        $fields = Document::visibleItemFields($settings, Sale::TYPE, $productType);
        $variants = $hasOldLines ? app(ItemService::class)->selectedVariants($lineInputs) : $savedLines->pluck('variant')->keyBy('id');
        // Show current stock consistently with the picker; posting also checks the invoice date.
        $stockDate = now()->toDateString();
        $customerId = getValue('customer_id', $record);
        $customerId = is_scalar($customerId) && ctype_digit((string) $customerId) ? (int) $customerId : null;
        $customers = $customerId === null ? [] : app(InventoryCustomers::class)->findMany($actor, [$customerId]);
        $access = app(InventoryAccess::class);

        return [
            'document' => $record, 'lineInputs' => $lineInputs, 'variants' => $variants,
            'savedLines' => $savedLines, 'hasOldLines' => $hasOldLines,
            'inventorySettings' => $settings, 'itemFields' => $fields,
            'stockQuantities' => app(StockService::class)->availableQuantities($productType, $stockDate, $variants->keys()->all()),
            'revisionStock' => $record->exists ? $this->revisionStockQuantities($record, $stockDate) : collect(),
            'paymentTypes' => PaymentType::query()->orderBy('ordering')->orderBy('id')->get(['id', 'name']),
            'selectedCustomer' => $customers[$customerId] ?? null,
            'canCreateCustomer' => $access->allows($actor, 'inventory-customers.create')
                && $access->allows($actor, 'inventory-sales.create'),
        ];
    }

    private function revisionStockQuantities(Sale $sale, string $stockDate): Collection
    {
        $connection = $sale->getConnection();
        $lineIds = $sale->lines->modelKeys();
        $allocations = $connection->table('inventory_stock_allocations')->whereIn('document_line_id', $lineIds)
            ->whereNull('source_allocation_id')->whereNull('reversed_at');
        $receipts = (clone $allocations)->whereNotNull('lot_id')->select('lot_id', 'quantity');
        $covered = $connection->table('inventory_stock_shortage_settlements as settlements')
            ->whereIn('allocation_id', (clone $allocations)->select('id'))->whereNull('settlements.reversed_at')
            ->select('settlements.lot_id', 'settlements.quantity');
        $query = $connection->query()->fromSub($receipts->unionAll($covered), 'reserved')
            ->join('inventory_stock_lots as lots', 'lots.id', '=', 'reserved.lot_id')
            ->where('lots.branch_id', $sale->branch_id)->whereNull('lots.reversed_at')->where('lots.received_at', '<=', $stockDate);
        if ($sale->product_type === 'medicine') {
            $expiryDate = max(now()->toDateString(), $stockDate);
            $query->where(fn ($query) => $query->whereNull('lots.expiry_date')->orWhere('lots.expiry_date', '>=', $expiryDate));
        }

        return $query->groupBy('lots.variant_id')->selectRaw('lots.variant_id, SUM(reserved.quantity) AS quantity')->pluck('quantity', 'variant_id');
    }

    public function reverse(Request $request, Sale $sale, SaleService $entries): RedirectResponse
    {
        $entries->reverse($request->user('web'), $sale);

        return redirect()->route($this->routePrefix().'.show', [
            $this->parameter => $sale, 'product_type' => $sale->product_type,
        ])->with('status', 'Entry voided. Stock and balances have been reversed.');
    }
}
