<?php

namespace Modules\TenantCore\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\TenantCore\Http\Requests\ThanaRequest;
use Modules\TenantCore\Models\District;
use Modules\TenantCore\Models\Thana;

class ThanaController extends BaseController
{
    protected string $model = Thana::class;

    protected string $title = 'Thana';

    protected string $views = 'tenantcore::thanas';

    protected string $parameter = 'thana';

    protected bool $inlineForm = true;

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(ThanaRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function formData(Account $actor, Model $record): array
    {
        return [
            'selectedDistrict' => $this->selectedOption(District::query(), getValue('district_id', $record)),
        ];
    }
}
