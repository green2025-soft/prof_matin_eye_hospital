<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Modules\Core\Models\CheckoutAttempt;
use Modules\Core\Services\CheckoutService;
use Throwable;

class ReconcilePaymentsCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'payments:reconcile {--limit=50 : Maximum pending checkouts per run} {--force}';

    protected $description = 'Verify pending checkouts with their provider and recover interrupted session creation';

    public function handle(CheckoutService $payments): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $limit = filter_var($this->option('limit'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 500]]);

        if ($limit === false) {
            $this->error('Use a limit between 1 and 500.');

            return self::FAILURE;
        }

        $attempts = CheckoutAttempt::query()->whereIn('status', [CheckoutAttempt::STATUS_READY, CheckoutAttempt::STATUS_CREATING])
            ->where('created_at', '<', now()->subMinute())->orderBy('checked_at')->orderBy('id')->limit($limit)->get();
        $failed = 0;

        foreach ($attempts as $attempt) {
            try {
                $result = $payments->reconcile($attempt);
                $this->line('Checkout #'.$attempt->getKey().': '.$result->status);
            } catch (Throwable $exception) {
                report($exception);
                $failed++;
                $this->error('Checkout #'.$attempt->getKey().' could not be reconciled. Review the application log.');
            } finally {
                CheckoutAttempt::query()->whereKey($attempt->getKey())->update(['checked_at' => now()]);
            }
        }

        $this->info('Processed '.$attempts->count().' checkout(s).');

        return $failed === 0 ? self::SUCCESS : self::FAILURE;
    }
}
