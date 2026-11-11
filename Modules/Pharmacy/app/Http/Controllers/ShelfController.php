<?php

namespace Modules\Pharmacy\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Pharmacy\Http\Requests\ShelfRequest;
use Modules\Pharmacy\Models\Product;
use Modules\Pharmacy\Models\Shelf;

class ShelfController extends BaseController
{
    protected string $model = Shelf::class;

    protected string $title = 'Shelf';

    protected string $views = 'pharmacy::shelf';

    protected string $parameter = 'shelf';

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
        $this->title = Product::types()[$type].' shelf';

        return $this->listData($request);
    }

    public function store(ShelfRequest $request): RedirectResponse
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
            'defaultType'   => $type,
            'pageTitle'     => ($record->exists ? 'Edit ' : 'Create ').Product::types()[$type].' shelf',
        ];

        return $data;
    }
}
