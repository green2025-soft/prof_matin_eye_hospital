<?php

namespace Modules\TenantCore\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\SelectorRequest;
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
        $filters = $request->validated();
        $filters['direction'] = $filters['direction'] ?? 'desc';
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

        $records = $model->paginateRecords($filters, $query)
            ->appends(Arr::only($filters, ['branch_id', 'date_from', 'date_to']));
        $branchId = $filters['branch_id'] ?? null;
        $selectedBranch = $this->selectedOption(Branch::query(), $branchId);

        if ($selectedBranch === null && filled($branchId)) {
            $selectedBranch = AuditLog::query()->where('branch_id', $branchId)->latest('audit_logs.id')
                ->toBase()->first(['branch_id as id', 'branch_name as name']);
        }

        return $this->listData($request, [
            'records' => $records,
            'filters' => $filters,
            'eventOptions' => AuditLog::EVENTS,
            'selectedBranch' => $selectedBranch,
        ]);
    }

    public function branches(SelectorRequest $request): JsonResponse
    {
        $records = (new Branch)->optionRecords($request->validated(), ['id', 'name']);

        return response()->json([
            'results' => $records->getCollection()->map(fn (Branch $branch): array => $branch->only(['id', 'name']))->all(),
            'pagination' => ['more' => $records->hasMorePages()],
        ]);
    }

    public function show(Request $request): View
    {
        $auditLog = $this->record($request);
        Gate::authorize('view', $auditLog);

        return view($this->views.'.show', [
            'auditLog' => $auditLog,
            'oldValues' => json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
            'newValues' => json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
            'eventLabel' => AuditLog::EVENTS[$auditLog->event] ?? $auditLog->event,
            'pageTitle' => 'Audit log #'.$auditLog->getKey(),
            'routePrefix' => $this->routePrefix(),
        ]);
    }
}
