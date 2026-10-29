<?php

namespace Modules\TenantCore\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Modules\Core\Http\Requests\ListRequest;
use Modules\TenantCore\Models\Branch;

class BranchDashboardRequest extends ListRequest
{
    public function authorize(): bool
    {
        return Gate::allows('selectAny', Branch::class);
    }
}
