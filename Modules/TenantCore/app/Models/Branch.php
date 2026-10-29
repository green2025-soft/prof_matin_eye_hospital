<?php

namespace Modules\TenantCore\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Services\ContextCache;
use Modules\TenantCore\Policies\BranchPolicy;

#[Fillable(['name', 'address', 'mobile_no', 'telephone_no', 'email', 'logo_path'])]
#[UsePolicy(BranchPolicy::class)]
class Branch extends BaseModel
{
    protected string $crudPermission = 'branches';

    protected array $exposed = ['id', 'name', 'address', 'mobile_no', 'telephone_no', 'email', 'logo_path', 'created_at'];

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
}
