<?php

namespace Modules\Pharmacy\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Pharmacy\Http\Requests\ProductUnitRequest;
use Modules\Pharmacy\Models\Product;
use Modules\Pharmacy\Models\ProductUnit;
use Modules\Pharmacy\Models\Unit;

class ProductUnitController extends BaseController
{
    protected string $model = ProductUnit::class;

    protected string $title = 'Product unit';

    protected string $views = 'pharmacy::product-units';

    protected string $parameter = 'product_unit';

    protected array $routeFilters = [
        'product_id' => 'product_id',
    ];

    protected array $selectorFields = ['id', 'product_id', 'unit_id', 'factor', 'unit'];

    public function index(ListRequest $request): View
    {
        $product = $this->selectedOption(Product::query(), $request->validated('product_id'), ['id', 'name', 'type']);
        if ($product !== null) {
            $request->attributes->set('pharmacy_product_type', $product->type);
            $this->title = Product::types()[$product->type].' product unit';
        }

        return $this->listData($request);
    }

    public function store(ProductUnitRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $productId = $record->exists ? $record->product_id : getValue('product_id', $record);
        if (! $record->exists && blank($productId)) {
            $productId = request()
                ->query('product_id');
        }
        $product = $this->selectedOption(Product::query(), $productId, ['id', 'name', 'type']);
        if ($product !== null) {
            request()->attributes->set('pharmacy_product_type', $product->type);
        }
        $title = $product ? Product::types()[$product->type].' product unit' : 'product unit';

        return [
            'selectedProduct' => $product,
            'pageTitle' => ($record->exists ? 'Edit ' : 'Create ').$title,
            'selectedUnit' => $this->selectedOption(Unit::query(), $record->exists ? $record->unit_id : getValue('unit_id', $record)),
            'unitLocked' => $record->exists && $record->hasReferences(),
        ];
    }
}
