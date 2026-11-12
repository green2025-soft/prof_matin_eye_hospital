<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Policies\SmsPolicy;

#[UsePolicy(SmsPolicy::class)]
class SmsMessage extends CoreModel
{
    public const STATUSES = [
        'pending' => 'Pending',
        'retry' => 'Pending retry',
        'expired' => 'Retry expired',
        'sending' => 'Sending',
        'submitted' => 'Submitted',
        'rejected' => 'Rejected / refunded',
        'uncertain' => 'Needs review',
        'skipped' => 'Skipped',
    ];

    protected $guarded = ['*'];

    protected $hidden = ['event_key'];

    protected array $exposed = ['id', 'tenant_id', 'feature', 'recipient', 'message', 'segments', 'rate', 'charge', 'attempts', 'status', 'error', 'attempted_at', 'submitted_at', 'created_at'];

    protected array $listWith = ['tenant:id,name'];

    protected function casts(): array
    {
        return [
            'tenant_id' => 'integer',
            'segments' => 'integer',
            'rate' => 'integer',
            'charge' => 'integer',
            'attempts' => 'integer',
            'attempted_at' => 'immutable_datetime',
            'submitted_at' => 'immutable_datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
