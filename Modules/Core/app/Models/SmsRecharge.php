<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Policies\SmsPolicy;

#[UsePolicy(SmsPolicy::class)]
class SmsRecharge extends CoreModel
{
    public const STATUSES = ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'];

    protected $guarded = ['*'];

    protected $hidden = ['proof_path'];

    protected $attributes = ['status' => 'pending'];

    protected array $exposed = ['id', 'tenant_id', 'amount', 'payment_method', 'payment_reference', 'status', 'requested_by', 'reviewed_by', 'review_note', 'reviewed_at', 'created_at'];

    protected array $listWith = ['tenant:id,name'];

    protected function casts(): array
    {
        return [
            'tenant_id' => 'integer',
            'amount' => 'integer',
            'reviewed_by' => 'integer',
            'reviewed_at' => 'immutable_datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
