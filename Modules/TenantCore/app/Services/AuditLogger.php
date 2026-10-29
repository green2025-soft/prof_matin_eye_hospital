<?php

namespace Modules\TenantCore\Services;

use Illuminate\Auth\Events\CurrentDeviceLogout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Core\Models\Account;
use Modules\Core\Models\BaseModel as CoreBaseModel;
use Modules\Core\Models\Role;
use Modules\Core\Services\AccessManager;
use Modules\TenantCore\Models\AuditLog;
use Modules\TenantCore\Models\Branch;
use Spatie\Permission\Events\PermissionAttachedEvent;
use Spatie\Permission\Events\PermissionDetachedEvent;
use Spatie\Permission\Events\RoleAttachedEvent;
use Spatie\Permission\Events\RoleDetachedEvent;

class AuditLogger
{
    public function __construct(private Request $request, private AccessManager $access) {}

    public function modelChanged(string $event, array $payload): void
    {
        $model = $payload[0] ?? null;
        $actor = $this->actor();

        if ($actor === null || ! $model instanceof Model || ! $this->canRecord($actor, $model)) {
            return;
        }

        $action = Str::after(Str::before($event, ':'), 'eloquent.');
        $fields = array_diff($model->queryContract()['exposed'], [$model->getKeyName(), 'created_at', 'updated_at'], $model->getHidden());
        $changedFields = array_keys($action === 'updated' ? $model->getChanges() : $model->getAttributes());
        $old = [];
        $new = [];

        foreach ($fields as $field) {
            if ($this->sensitiveKey($field) || ($action === 'updated' && ! in_array($field, $changedFields, true))) {
                continue;
            }

            if ($action !== 'created') {
                $old[$field] = $this->redact($model->getOriginal($field));
            }
            if ($action !== 'deleted') {
                $new[$field] = $this->redact($model->getAttribute($field));
            }
        }

        // Record that protected values changed without recording their contents.
        foreach (array_intersect(['password', 'secrets', 'credentials'], $changedFields) as $field) {
            if ($action !== 'created') {
                $old[$field] = '[REDACTED]';
            }
            if ($action !== 'deleted') {
                $new[$field] = '[REDACTED]';
            }
        }

        if ($action === 'updated' && $old === [] && $new === []) {
            return;
        }

        $this->write($actor, $model, $action, $old, $new);
    }

    public function login(Login $event): void
    {
        $actor = $event->user instanceof Account ? $this->actor($event->user) : null;
        if ($actor !== null) {
            $this->write($actor, $actor, 'login');
        }
    }

    public function logout(Logout|CurrentDeviceLogout $event): void
    {
        $actor = $event->user instanceof Account ? $this->actor($event->user) : null;
        if ($actor !== null) {
            $this->write($actor, $actor, 'logout');
        }
    }

    public function rolesAttached(RoleAttachedEvent $event): void
    {
        $this->accessChanged($event->model, 'role_ids', $event->rolesOrIds, 'attached');
    }

    public function rolesDetached(RoleDetachedEvent $event): void
    {
        $this->accessChanged($event->model, 'role_ids', $event->rolesOrIds, 'detached');
    }

    public function permissionsAttached(PermissionAttachedEvent $event): void
    {
        $this->accessChanged($event->model, 'permission_ids', $event->permissionsOrIds, 'attached');
    }

    public function permissionsDetached(PermissionDetachedEvent $event): void
    {
        $this->accessChanged($event->model, 'permission_ids', $event->permissionsOrIds, 'detached');
    }

    private function accessChanged(Model $model, string $field, mixed $values, string $operation): void
    {
        $actor = $this->actor();
        if ($actor === null || ! $this->canRecord($actor, $model)) {
            return;
        }

        if ($values instanceof Collection) {
            $values = $values->all();
        } elseif ($values instanceof Model) {
            $values = [$values];
        }

        $ids = [];
        foreach ((array) $values as $value) {
            $id = $value instanceof Model ? $value->getKey() : $value;
            if (is_int($id) || is_string($id)) {
                $ids[] = (string) $id;
            }
        }
        if ($ids === []) {
            return;
        }

        $change = [$field.'_'.$operation => array_values(array_unique($ids))];
        $this->write($actor, $model, 'updated', $operation === 'detached' ? $change : [], $operation === 'attached' ? $change : []);
    }

    private function actor(?Account $user = null): ?Account
    {
        // Console/provisioning and central requests have no tenant user context.
        if ($this->request->attributes->get('application_provider') !== 'users') {
            return null;
        }

        $user ??= $this->request->user('web');

        return $user instanceof Account && $this->access->actorMatchesContext($user) ? $user : null;
    }

    private function canRecord(Account $actor, Model $model): bool
    {
        if ($model instanceof AuditLog || (! $model instanceof CoreBaseModel && ! $model instanceof Account && ! $model instanceof Role)) {
            return false;
        }

        return $model->getConnection()->getName() === $actor->getConnection()->getName()
            && $model->getConnection()->getDatabaseName() === $actor->getConnection()->getDatabaseName();
    }

    private function branch(Account $actor): ?Branch
    {
        if ($this->request->attributes->has('active_branch')) {
            $branch = $this->request->attributes->get('active_branch');

            return $branch instanceof Branch ? $branch : null;
        }

        // Auth routes skip ResolveBranch, so revalidate fixed/session ownership here.
        $branchId = $actor->branch_id;
        if ($branchId === null && $this->request->hasSession()
            && $this->request->session()->get('branch_user_id') === $actor->getAuthIdentifier()) {
            $branchId = $this->request->session()->get('branch_id');
        }

        return $branchId === null ? null : Branch::query()->find($branchId, ['id', 'name']);
    }

    private function write(Account $actor, Model $model, string $event, array $old = [], array $new = []): void
    {
        $branch = $this->branch($actor);
        $attributes = $model->getAttributes();

        // Direct insert avoids recursive model events and joins the caller's transaction.
        (new AuditLog)->getConnection()->table('audit_logs')->insert([
            'branch_id' => $branch?->getKey(),
            'branch_name' => $branch?->name,
            'actor_id' => $actor->getKey(),
            'actor_type' => $actor->getTable(),
            'actor_name' => $actor->name,
            'event' => $event,
            'resource' => $model->getTable(),
            'record_id' => (string) $model->getKey(),
            'record_name' => $attributes['name'] ?? $attributes['group'] ?? null,
            'old_values' => json_encode($old, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
            'new_values' => json_encode($new, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
            'ip_address' => $this->request->ip(),
            'user_agent' => Str::substr(mb_scrub($this->request->userAgent() ?? '', 'UTF-8'), 0, 512),
            'created_at' => now(),
        ]);
    }

    private function sensitiveKey(string $key): bool
    {
        return preg_match('/password|token|secret|credential|api[_-]?key|app[_-]?key|private[_-]?key|hash[_-]?key/i', $key) === 1;
    }

    private function redact(mixed $value): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        foreach ($value as $key => $item) {
            $value[$key] = $this->sensitiveKey((string) $key) ? '[REDACTED]' : $this->redact($item);
        }

        return $value;
    }
}
