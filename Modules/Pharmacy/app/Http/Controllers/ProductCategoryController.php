<?php

namespace Modules\Pharmacy\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Pharmacy\Http\Requests\ProductCategoryRequest;
use Modules\Pharmacy\Models\Product;
use Modules\Pharmacy\Models\ProductCategory;

class ProductCategoryController extends BaseController
{
    protected string $model = ProductCategory::class;

    protected string $title = ' Generic/Category ';

    protected string $views = 'pharmacy::categories';

    protected string $parameter = 'product_category';

    protected array $routeFilters = [
        'type' => 'type',
    ];

    protected array $defaultFilters = [
        'type' => 'medicine',
    ];

    protected bool $inlineForm = true;

    protected array $selectorFields = ['id', 'name', 'type'];

    public function index(ListRequest $request): View
    {
        $type = $request->validated('type') ?? 'medicine';
        $request->attributes->set('pharmacy_product_type', $type);
        $this->title = Product::types()[$type].$this->title;

        return $this->listData($request);
    }

    public function store(ProductCategoryRequest $request): RedirectResponse
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

        $data = [
            'defaultType' => $type,
            'pageTitle' => ($record->exists ? 'Edit ' : 'Create ').Product::types()[$type].' category / generic',
        ];

        return $data;
    }
}
