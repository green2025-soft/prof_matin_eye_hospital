<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

#[Fillable(['event_key', 'feature', 'recipient', 'message'])]
class SmsOutbox extends BaseModel
{
    use UsesTenantConnection;

    protected $table = 'sms_outbox';

    protected function casts(): array
    {
        return ['processed_at' => 'immutable_datetime'];
    }
}
