<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Services\ContextCache;

#[Fillable(['domain'])]
class Domain extends Model
{
    protected $connection = 'central';

    protected static function booted(): void
    {
        $clearContext = static fn (self $domain) => app(ContextCache::class)->flush($domain->getConnectionName(), 'tenant-domains');
        static::saved($clearContext);
        static::deleted($clearContext);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
