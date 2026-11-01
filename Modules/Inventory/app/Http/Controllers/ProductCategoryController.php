<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Inventory\Http\Requests\ProductCategoryRequest;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductCategory;

class ProductCategoryController extends BaseController
{
    protected string $model = ProductCategory::class;

    protected string $title = 'Category / generic';

    protected string $views = 'inventory::categories';

    protected string $parameter = 'product_category';

    protected array $routeFilters = ['type' => 'type'];

    protected array $defaultFilters = ['type' => 'medicine'];

    protected bool $inlineForm = true;

    protected array $selectorFields = ['id', 'name', 'type'];

    public function index(ListRequest $request): View
    {
        $type = $request->validated('type') ?? 'medicine';
        $request->attributes->set('inventory_product_type', $type);
        $this->title = Product::types()[$type].' category / generic';

        return $this->listData($request);
    }

    public function store(ProductCategoryRequest $request): RedirectResponse
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

        return [
            'defaultType' => $type,
            'pageTitle' => ($record->exists ? 'Edit ' : 'Create ').Product::types()[$type].' category / generic',
        ];
    }
}
