<?php

namespace Modules\Hospital\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Hospital\Http\Requests\InvestigationRequest;
use Modules\Hospital\Models\Investigation;
use Modules\Hospital\Models\InvestigationCategory;

class InvestigationController extends BaseController
{
    protected string $model = Investigation::class;

    protected string $title = 'Investigation';

    protected string $views = 'hospital::investigations';

    protected string $parameter = 'investigation';

    protected array $selectorFields = ['id', 'name', 'category_id', 'single_rate', 'both_rate', 'category'];

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(InvestigationRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function formData(Account $actor, Model $record): array
    {
        return [
            'selectedCategory' => $this->selectedOption(InvestigationCategory::query(), getValue('category_id', $record)),
        ];
    }
}
