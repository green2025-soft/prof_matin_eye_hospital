<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckoutAttempt extends CoreModel
{
    public const STATUS_CREATING = 'creating';

    public const STATUS_READY = 'ready';

    public const STATUS_PAID = 'paid';

    public const STATUS_FAILED = 'failed';

    public const STATUS_REVIEW = 'review';

    protected $guarded = ['*'];

    protected $hidden = ['credential_snapshot', 'token'];

    protected array $exposed = ['id', 'invoice_id', 'provider', 'reference', 'provider_reference', 'amount_minor', 'extra_charge_minor', 'currency', 'status', 'created_at'];

    protected array $searchable = ['reference', 'provider_reference'];

    protected array $sortable = ['id', 'created_at'];

    protected string $defaultSort = 'id';

    protected function casts(): array
    {
        return ['amount_minor' => 'integer', 'extra_charge_minor' => 'integer', 'credential_snapshot' => 'encrypted:array', 'expires_at' => 'immutable_datetime', 'checked_at' => 'immutable_datetime'];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
