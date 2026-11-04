<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Inventory\Http\Requests\ProductVariantRequest;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductVariant;

class ProductVariantController extends BaseController
{
    protected string $model = ProductVariant::class;

    protected string $title = 'Product variant';

    protected string $views = 'inventory::variants';

    protected string $parameter = 'product_variant';

    protected array $routeFilters = [
        'product_id' => 'product_id',
    ];

    protected array $selectorFields = ['id', 'product_id', 'sku', 'name', 'product'];

    public function index(ListRequest $request): View
    {
        $product = $this->selectedOption(Product::query(), $request->validated('product_id'), ['id', 'name', 'type']);
        if ($product !== null) {
            $request->attributes->set('inventory_product_type', $product->type);
            $this->title = Product::types()[$product->type].' product variant';
        }

        return $this->listData($request);
    }

    public function store(ProductVariantRequest $request): RedirectResponse
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
            request()->attributes->set('inventory_product_type', $product->type);
        }
        $title = $product ? Product::types()[$product->type].' product variant' : 'product variant';

        return [
            'selectedProduct' => $product,
            'pageTitle' => ($record->exists ? 'Edit ' : 'Create ').$title,
        ];
    }
}
