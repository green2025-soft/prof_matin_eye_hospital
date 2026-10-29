<?php

namespace Modules\Hospital\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Hospital\Http\Requests\OperationRequest;
use Modules\Hospital\Models\Operation;
use Modules\Hospital\Models\OperationCategory;

class OperationController extends BaseController
{
    protected string $model = Operation::class;

    protected string $title = 'Operation';

    protected string $views = 'hospital::operations';

    protected string $parameter = 'operation';

    protected array $selectorFields = ['id', 'name', 'single_rate', 'both_rate'];

    public function index(ListRequest $request): View
    {
        return $this->listData($request)->with('commissionTypes', commissionTypes());
    }

    public function store(OperationRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function formData(Account $actor, Model $record): array
    {
        return [
            'commissionTypes' => commissionTypes(),
            'selectedCategory' => $this->selectedOption(OperationCategory::query(), getValue('category_id', $record)),
        ];
    }
}
