<?php

namespace Modules\TenantCore\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\TenantCore\Http\Requests\DistrictRequest;
use Modules\TenantCore\Models\District;
use Modules\TenantCore\Models\Division;

class DistrictController extends BaseController
{
    protected string $model = District::class;

    protected string $title = 'District';

    protected string $views = 'tenantcore::districts';

    protected string $parameter = 'district';

    protected bool $inlineForm = true;

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(DistrictRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function formData(Account $actor, Model $record): array
    {
        return [
            'selectedDivision' => $this->selectedOption(Division::query(), getValue('division_id', $record)),
        ];
    }
}
