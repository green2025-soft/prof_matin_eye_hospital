<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Inventory\Http\Requests\ProductRequest;
use Modules\Inventory\Models\Dosage;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductCategory;
use Modules\Inventory\Models\ProductUnit;
use Modules\Inventory\Models\ProductVariant;
use Modules\Inventory\Models\Shelf;
use Modules\Inventory\Models\Supplier;

class ProductController extends BaseController
{
    protected string $model = Product::class;

    protected string $title = 'Product';

    protected string $views = 'inventory::products';

    protected string $parameter = 'product';

    protected array $routeFilters = ['type' => 'type'];

    protected array $defaultFilters = ['type' => 'medicine'];

    protected array $selectorFields = ['id', 'name', 'type', 'code', 'barcode', 'purchase_price', 'sale_price'];

    public function index(ListRequest $request): View
    {
        $type = $request->validated('type') ?? 'medicine';
        $request->attributes->set('inventory_product_type', $type);
        $this->title = Product::types()[$type].' product';

        return $this->listData($request);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $type = $record->exists ? $record->type : request()->query('type', 'medicine');
        if (! is_string($type) || ! array_key_exists($type, Product::types())) {
            $type = 'medicine';
        }
        if (! request()->attributes->has('inventory_product_type')) {
            request()->attributes->set('inventory_product_type', $type);
        }
        $canViewSetup = $record->exists
            && Gate::forUser($actor)->allows('viewAny', ProductVariant::class)
            && Gate::forUser($actor)->allows('viewAny', ProductUnit::class);
        $setupVariants = collect();
        $setupUnits = collect();
        if ($canViewSetup) {
            $record->loadCount(['variants', 'units']);
            $setupVariants = $record->variants()
                ->orderBy('id')->limit(3)
                ->get(['id', 'product_id', 'sku', 'name', 'color', 'size', 'sph', 'cyl', 'active']);
            $setupUnits = $record->units()->with('unit:id,name,symbol')
                ->orderBy('factor')->orderBy('id')->limit(3)
                ->get(['id', 'product_id', 'unit_id', 'factor']);
        }

        return [
            'defaultType' => $type,
            'pageTitle' => ($record->exists ? 'Edit ' : 'Create ').Product::types()[$type].' product',
            'selectedCategory' => $this->selectedOption(ProductCategory::query()->where('type', $type), getValue('category_id', $record)),
            'selectedDosage' => $this->selectedOption(Dosage::query(), getValue('dosage_id', $record)),
            'selectedSupplier' => $this->selectedOption(Supplier::query()->where('type', $type), getValue('supplier_id', $record)),
            'selectedShelf' => $this->selectedOption(Shelf::query()->where('type', $type), getValue('shelf_id', $record)),
            'canViewSetup' => $canViewSetup,
            'setupVariants' => $setupVariants,
            'setupUnits' => $setupUnits,
        ];
    }
}
