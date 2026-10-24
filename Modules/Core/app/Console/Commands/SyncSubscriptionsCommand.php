<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Modules\Core\Jobs\GenerateDueInvoices;

class SyncSubscriptionsCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'subscriptions:sync {--force}';

    protected $description = 'Renew free periods, issue paid-period invoices, and expire or cancel due subscriptions.';

    public function handle(): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $result = GenerateDueInvoices::dispatchSync(failOnError: false);
        $this->info('Generated '.$result['generated'].' invoices; processed '.$result['processed'].' due subscriptions.');

        foreach ($result['failed'] as $id) {
            $this->error('Subscription #'.$id.' failed. Review the application log before retrying.');
        }

        return $result['failed'] === [] ? self::SUCCESS : self::FAILURE;
    }
}
