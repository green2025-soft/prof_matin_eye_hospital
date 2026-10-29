<?php

namespace Modules\TenantCore\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\TenantCore\Http\Requests\BranchDashboardRequest;
use Modules\TenantCore\Models\Branch;

class BranchDashboardController extends BaseController
{
    protected string $model = Branch::class;

    protected string $title = 'Branch dashboard';

    protected string $views = 'tenantcore::branch-dashboard';

    protected string $parameter = 'branch';

    public function index(BranchDashboardRequest $request): View
    {
        return $this->listData($request, [
            'records' => (new Branch)->paginateRecords($request->validated()),
            'pageTitle' => $this->title,
            'activeBranchId' => $request->attributes->get('branch_id'),
        ]);
    }

    public function store(Request $request, Branch $branch): RedirectResponse
    {
        $actor = $request->user('web');
        Gate::authorize('select', $branch);
        $request->session()->put(['branch_id' => $branch->getKey(), 'branch_user_id' => $actor->getAuthIdentifier()]);

        return redirect()->intended($actor->redirect_url ?: route('home'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Gate::authorize('selectAny', Branch::class);
        $request->session()->forget(['branch_id', 'branch_user_id', 'url.intended']);

        return redirect()->route('tenantcore.branch-dashboard.index');
    }
}
