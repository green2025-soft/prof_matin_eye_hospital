<?php

namespace Modules\Pharmacy\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Pharmacy\Http\Requests\ProductRequest;
use Modules\Pharmacy\Models\Dosage;
use Modules\Pharmacy\Models\Product;
use Modules\Pharmacy\Models\ProductCategory;
use Modules\Pharmacy\Models\ProductUnit;
use Modules\Pharmacy\Models\ProductVariant;
use Modules\Pharmacy\Models\Shelf;
use Modules\Pharmacy\Models\Supplier;

class ProductController extends BaseController
{
    protected string $model = Product::class;

    protected string $title = 'Medicine/Product';

    protected string $views = 'pharmacy::products';

    protected string $parameter = 'product';

    protected array $routeFilters = [
        'type' => 'type',
    ];

    protected array $defaultFilters = [
        'type' => 'medicine',
    ];

    protected array $selectorFields = [
        'id', 'name', 'type', 'code', 'barcode', 'purchase_price', 'sale_price',
        'category_id', 'dosage_id', 'supplier_id', 'category', 'dosage', 'supplier',
    ];

    public function index(ListRequest $request): View
    {
        $type = $request->validated('type') ?? 'medicine';
        $request->attributes->set('pharmacy_product_type', $type);
        $this->title = Product::types()[$type].' product';

        return $this->listData($request);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $request = request();
        $type = $record->exists ? $record->type : $request->query('type', 'medicine');

        if (! in_array($type, array_keys(Product::types()), true)) {
            $type = 'medicine';
        }

        $request->attributes->set('pharmacy_product_type', $type);
        $gate = Gate::forUser($actor);
        $canViewSetup = $record->exists
            && $gate->allows('viewAny', ProductVariant::class)
            && $gate->allows('viewAny', ProductUnit::class);
        $setupVariants = collect();
        $setupUnits = collect();

        if ($canViewSetup) {
            $record->loadCount(['variants', 'units']);
            $setupVariants = $record->variants()
                ->orderBy('id')
                ->limit(3)
                ->get(['id', 'product_id', 'sku', 'name', 'color', 'size', 'sph', 'cyl', 'active']);
            $setupUnits = $record->units()
                ->with('unit:id,name,symbol')
                ->orderBy('factor')
                ->orderBy('id')
                ->limit(3)
                ->get(['id', 'product_id', 'unit_id', 'factor']);
        }

        $data = [
            'defaultType' => $type,
            'selectedCategory' => $this->selectedOption(
                ProductCategory::where('type', $type),
                getValue('category_id', $record)
            ),
            'selectedDosage' => $this->selectedOption(Dosage::query(), getValue('dosage_id', $record)),
            'selectedSupplier' => $this->selectedOption(
                Supplier::where('type', $type),
                getValue('supplier_id', $record)
            ),
            'selectedShelf' => $this->selectedOption(
                Shelf::where('type', $type),
                getValue('shelf_id', $record)
            ),
            'canViewSetup' => $canViewSetup,
            'setupVariants' => $setupVariants,
            'setupUnits' => $setupUnits,
        ];

        return $data;
    }
}
