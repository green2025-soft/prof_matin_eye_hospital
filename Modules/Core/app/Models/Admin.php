<?php

namespace Modules\Core\Models;

class Admin extends Account
{
    protected $connection = 'central';

    protected string $profileRoutePrefix = 'core.accounts';
}
