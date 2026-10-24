<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Http\Requests\RoleRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Role;

class RoleController extends BaseController
{
    protected string $model = Role::class;

    protected string $title = 'Role';

    protected string $views = 'core::roles';

    protected string $parameter = 'role';

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function formData(Account $actor, Model $record): array
    {
        return ['isReservedRole' => $record->isReserved()];
    }
}
