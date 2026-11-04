<?php

namespace Modules\Prescription\Models;

use Modules\Core\Models\BaseModel as CoreBaseModel;
use Modules\Core\Models\Setting;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

abstract class BaseModel extends CoreBaseModel
{
    use UsesTenantConnection;

    protected function prescriptionSettings(): array
    {
        $request = request();
        $cacheKey = 'prescription.settings.'.$this->getConnection()->getDatabaseName();

        if ($request->isMethodSafe() && $request->attributes->has($cacheKey)) {
            return $request->attributes->get($cacheKey);
        }

        $settings = Setting::values('prescription', $this->getConnectionName());

        if ($request->isMethodSafe()) {
            $request->attributes->set($cacheKey, $settings);
        }

        return $settings;
    }
}
