<?php

namespace Modules\Hospital\Models;

class DoctorPermission extends BaseModel
{
    protected $table = 'doctor_permissions';

    protected $primaryKey = 'permission_id';

    public $incrementing = false;

    public $timestamps = false;
}
