<?php

use Illuminate\Support\Facades\Schedule;
use Modules\Core\Jobs\GenerateDueInvoices;

Schedule::job(new GenerateDueInvoices)->hourly()->withoutOverlapping();
Schedule::command('payments:reconcile --force')->everyFiveMinutes()->withoutOverlapping();
