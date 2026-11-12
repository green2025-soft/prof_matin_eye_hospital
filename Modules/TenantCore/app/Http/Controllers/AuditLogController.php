<?php

namespace Modules\TenantCore\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\SelectorRequest;
use Modules\Core\Models\Tenant;
use Modules\TenantCore\Http\Requests\AuditLogRequest;
use Modules\TenantCore\Models\AuditLog;
use Modules\TenantCore\Models\Branch;

class AuditLogController extends BaseController
{
    protected string $model = AuditLog::class;

    protected string $title = 'Audit log';

    protected string $views = 'tenantcore::audit-logs';

    protected string $parameter = 'audit_log';

    public function index(AuditLogRequest $request): View
    {
        $hasBranches = (bool) Tenant::current()?->has_branches;
        $defaultBranchId = $hasBranches ? Branch::where('is_default', true)->value('id') : null;
        $filters = $request->validated();
        $filters['direction'] = $filters['direction'] ?? 'desc';

        if (! $hasBranches || (int) ($filters['branch_id'] ?? 0) === (int) $defaultBranchId) {
            unset($filters['branch_id']);
        }

        $model = new AuditLog;
        $query = $model->listQuery();

        if (filled($filters['branch_id'] ?? null)) {
            $query->where('branch_id', $filters['branch_id']);
        }

        if (filled($filters['date_from'] ?? null)) {
            $query->where('created_at', '>=', $filters['date_from'].' 00:00:00');
        }

        if (filled($filters['date_to'] ?? null)) {
            $query->where('created_at', '<=', $filters['date_to'].' 23:59:59');
        }

        $records = $model->paginateRecords($filters, $query)->appends(Arr::only($filters, ['branch_id', 'date_from', 'date_to']));
        $branchId = $filters['branch_id'] ?? null;
        $selectedBranch = $this->selectedOption(Branch::where('is_default', false), $branchId);

        // Deleted branches retain their names in the audit snapshots.
        if ($selectedBranch === null && filled($branchId)) {
            $selectedBranch = AuditLog::where('branch_id', $branchId)->latest('audit_logs.id')->toBase()
                ->first(['branch_id as id', 'branch_name as name']);
        }

        $data = [
            'records' => $records,
            'filters' => $filters,
            'eventOptions' => AuditLog::EVENTS,
            'selectedBranch' => $selectedBranch,
            'hasBranches' => $hasBranches,
            'defaultBranchId' => $defaultBranchId,
        ];

        return $this->listData($request, $data);
    }

    public function branches(SelectorRequest $request): JsonResponse
    {
        if (! Tenant::current()?->has_branches) {
            $data = [
                'results' => [],
                'pagination' => [
                    'more' => false,
                ],
            ];

            return response()->json($data);
        }

        $records = (new Branch)->optionRecords($request->validated(), ['id', 'name']);

        $data = [
            'results' => $records->getCollection()
                ->setVisible(['id', 'name']),
            'pagination' => [
                'more' => $records->hasMorePages(),
            ],
        ];

        return response()->json($data);
    }

    public function show(Request $request): View
    {
        $auditLog = $this->record($request);
        Gate::authorize('view', $auditLog);

        $hasBranches = (bool) Tenant::current()?->has_branches;
        $data = [
            'auditLog' => $auditLog,
            'eventLabel' => AuditLog::EVENTS[$auditLog->event] ?? $auditLog->event,
            'pageTitle' => 'Audit log #'.$auditLog->getKey(),
            'routePrefix' => $this->routePrefix(),
            'hasBranches' => $hasBranches,
            'defaultBranchId' => $hasBranches ? Branch::where('is_default', true)->value('id') : null,
        ];

        return view($this->views.'.show', $data);
    }
}
