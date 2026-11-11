<?php

namespace Modules\TenantCore\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\ContextCache;
use Modules\TenantCore\Policies\BranchPolicy;

#[Fillable(['name', 'address', 'mobile_no', 'telephone_no', 'email', 'logo_path'])]
#[UsePolicy(BranchPolicy::class)]
class Branch extends BaseModel
{
    protected string $crudPermission = 'branches';

    protected array $queryConstraints = ['is_default' => false];

    protected array $exposed = ['id', 'name', 'address', 'mobile_no', 'telephone_no', 'email', 'logo_path', 'created_at'];

    protected function casts(): array
    {
        return ['is_default' => 'boolean'];
    }

    protected static function booted(): void
    {
        $clearContext = static fn (self $branch) => app(ContextCache::class)->flush($branch->getConnectionName(), 'branches');
        static::saved($clearContext);
        static::deleted($clearContext);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path === null ? null : Storage::disk('public')->url($this->logo_path);
    }

    public function isCrudEnabled(): bool
    {
        return (bool) Tenant::current()?->has_branches && ! $this->is_default;
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        return parent::resolveRouteBindingQuery($query, $value, $field)
            ->where($this->qualifyColumn('is_default'), false);
    }
}
