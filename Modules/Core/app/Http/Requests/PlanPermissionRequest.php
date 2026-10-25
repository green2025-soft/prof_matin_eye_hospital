<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Validation\Rule;

class PlanPermissionRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    protected array $checkboxes = ['route_permissions' => 'route_permissions_present'];

    public function rules(): array
    {
        return [
            'route_permissions_present' => ['required', 'in:1'],
            'unrestricted_route_access' => ['required', 'boolean'],
            'route_permissions' => ['present', 'array', 'max:50'],
            'route_permissions.*' => ['required', 'string', 'distinct', Rule::in(config('permissions.tenant_routes'))],
        ];
    }

    public function payload(): array
    {
        $values = $this->validated();

        return ['route_permissions' => $values['unrestricted_route_access'] ? null : $values['route_permissions']];
    }

    protected function getRedirectUrl(): string
    {
        return route('core.plan-permissions.index', ['plan' => $this->record()->getKey()]);
    }
}
