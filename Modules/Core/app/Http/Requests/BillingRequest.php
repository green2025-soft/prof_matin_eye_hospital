<?php

namespace Modules\Core\Http\Requests;

use Modules\Core\Policies\BillingPolicy;

class BillingRequest extends ListRequest
{
    public function authorize(): bool
    {
        return app(BillingPolicy::class)->view($this->user('web'));
    }
}
