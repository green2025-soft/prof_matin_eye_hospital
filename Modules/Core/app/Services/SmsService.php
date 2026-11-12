<?php

namespace Modules\Core\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Sms;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Core\Models\SmsAccount;
use Modules\Core\Models\SmsMessage;
use Modules\Core\Models\SmsOutbox;
use Modules\Core\Models\Tenant;
use Throwable;

use function Illuminate\Support\defer;

class SmsService
{
    public function __construct(private Sms $transport) {}

    // Save with the financial entry. No HTTP request runs inside its transaction.
    public function queue(Model $source, string $feature, string $eventKey, ?string $to, bool $enabled, string $template, array $variables): void
    {
        $tenant = Tenant::current();
        $recipient = $this->recipient($to);

        if (! $enabled || $tenant === null || $recipient === null || trim($template) === ''
            || ! array_key_exists($feature, SmsAccount::availableFeatures())) {
            return;
        }

        $replacements = [
            '{organization}' => $tenant->name,
            '{organization_phone}' => $tenant->phone ?? '',
            '{organization_address}' => $tenant->address ?? '',
        ];
        foreach ($variables as $name => $value) {
            $replacements['{'.$name.'}'] = (string) $value;
        }

        $outbox = new SmsOutbox;
        $outbox->setConnection($source->getConnectionName());
        $outbox->newQuery()->firstOrCreate([
            'event_key' => hash('sha256', $feature.'|'.$eventKey),
        ], [
            'feature' => $feature,
            'recipient' => $recipient,
            'message' => strtr($template, $replacements),
        ]);

        $source->getConnection()->afterCommit(function () use ($tenant): void {
            $this->afterResponse($tenant);
        });
    }

    public function afterResponse(Tenant $tenant): void
    {
        // Restore tenant context even when response middleware has already cleaned it up.
        defer(fn () => $tenant->execute(fn () => $this->processPending($tenant)), 'sms:'.$tenant->getKey())->always();
    }

    // A later action recovers unattempted rows; an existing central event is never resent.
    public function processPending(Tenant $tenant, int $limit = 20): void
    {
        if (! Tenant::current()?->is($tenant)) {
            return;
        }

        try {
            // Automatic retries belong to this event's first 24 hours only.
            SmsOutbox::whereNull('processed_at')
                ->where('created_at', '<=', now()->subDay())
                ->update(['processed_at' => now()]);
            SmsMessage::where('tenant_id', $tenant->getKey())
                ->whereIn('status', ['pending', 'retry'])
                ->where('created_at', '<=', now()->subDay())
                ->update(['status' => 'expired', 'error' => 'The 24-hour retry window has ended.']);

            SmsMessage::where('tenant_id', $tenant->getKey())
                ->where('status', 'sending')
                ->where('attempted_at', '<', now()->subMinutes(5))
                ->update([
                    'status' => 'uncertain',
                    'error' => 'Submission was interrupted. Check the provider before resolving.',
                ]);

            $pending = SmsOutbox::whereNull('processed_at')
                ->orderBy('updated_at')
                ->orderBy('id')
                ->limit($limit)
                ->get();

            $deadline = microtime(true) + 12;
            foreach ($pending as $outbox) {
                if (microtime(true) >= $deadline) {
                    break;
                }
                Cache::lock('sms-outbox:'.$outbox->getKey(), 90)->get(function () use ($tenant, $outbox): void {
                    $current = SmsOutbox::whereKey($outbox->getKey())->whereNull('processed_at')->first();
                    if ($current !== null) {
                        $this->deliver($tenant, $current);
                    }
                });
            }
        } catch (Throwable) {
            // A committed bill must remain successful when SMS storage is unavailable.
            Log::warning('Pending SMS could not be processed.', ['tenant_id' => $tenant->getKey()]);
        }
    }

    private function deliver(Tenant $tenant, SmsOutbox $outbox): void
    {
        $delivery = $this->reserve($tenant, $outbox);
        if ($delivery === null) {
            $outbox->forceFill(['processed_at' => now()])->save();

            return;
        }

        $message = $delivery['message'];
        $gateway = $delivery['gateway'];
        if ($message->status === 'retry') {
            $outbox->touch();

            return;
        }

        // A crash during HTTP leaves a held reservation, not an automatic duplicate.
        $outbox->forceFill(['processed_at' => now()])->save();

        try {
            $response = $this->transport->send($message->recipient, $message->message, $gateway);
            $successField = $gateway['success_field'] ?? '';
            $status = $response->successful() ? 'submitted' : 'uncertain';
            if ($response->successful() && $successField !== '') {
                $value = data_get($response->json(), $successField);
                $expected = (string) ($gateway['success_value'] ?? '');
                if (! is_scalar($value) || $expected === '') {
                    $status = 'uncertain';
                } else {
                    $actual = is_bool($value) ? ($value ? 'true' : 'false') : (string) $value;
                    $failures = array_filter(array_map('trim', explode(',', $gateway['failure_values'] ?? '')), fn (string $failure): bool => $failure !== '');
                    $status = 'uncertain';
                    if ($actual === $expected) {
                        $status = 'submitted';
                    } elseif (in_array($actual, $failures, true)) {
                        $status = 'retry';
                    }
                }
            }

            $this->finish($message, $status, $status === 'submitted' ? null : 'Provider acceptance could not be confirmed.');
        } catch (RequestException $exception) {
            $status = $exception->response->status();
            $rejected = $status >= 400 && $status < 500 && $status !== 408;
            $this->finish($message, $rejected ? 'retry' : 'uncertain', 'The provider returned HTTP '.$status.'.');
        } catch (Throwable) {
            $this->finish($message, 'uncertain', 'Submission could not be confirmed. Check the provider before resolving.');
        }

        if ($message->fresh()->status === 'retry') {
            $outbox->forceFill(['processed_at' => null])->save();
        }
    }

    private function reserve(Tenant $tenant, SmsOutbox $outbox): ?array
    {
        if ($outbox->created_at->lte(now()->subDay())) {
            return null;
        }

        return (new SmsAccount)->getConnection()->transaction(function () use ($tenant, $outbox): ?array {
            $currentTenant = Tenant::whereKey($tenant->getKey())->lockForUpdate()->first();
            if ($currentTenant?->status !== Tenant::STATUS_ACTIVE) {
                return null;
            }

            $account = SmsAccount::where('tenant_id', $tenant->getKey())->lockForUpdate()->first();
            $message = SmsMessage::where('tenant_id', $tenant->getKey())
                ->where('event_key', $outbox->event_key)
                ->lockForUpdate()->first();
            if ($message !== null && ! in_array($message->status, ['pending', 'retry'], true)) {
                return null;
            }

            $segments = $this->transport->segments($outbox->message);
            $gateway = $account?->gateway ?? Setting::values('sms', 'central');
            $reason = $this->unavailableReason($account, $outbox->feature, $segments, $gateway);
            $tooLong = mb_strlen($outbox->message) > 1600;
            if ($tooLong) {
                $reason = 'The expanded message exceeds 1600 characters. Shorten the template.';
            }
            $charge = $reason === null ? $account->rate * $segments : 0;

            $message ??= new SmsMessage;
            $message->forceFill([
                'tenant_id' => $tenant->getKey(),
                'feature' => $outbox->feature,
                'event_key' => $outbox->event_key,
                'recipient' => $outbox->recipient,
                'message' => $outbox->message,
                'segments' => $segments,
                'attempts' => $message->attempts + ($reason === null ? 1 : 0),
                'rate' => $account?->rate ?? 0,
                'charge' => $charge,
                'status' => $tooLong ? 'rejected' : ($reason === null ? 'sending' : 'retry'),
                'error' => $reason,
                'attempted_at' => $reason === null ? now() : null,
                'created_at' => $outbox->created_at,
            ])->save();

            if ($reason !== null) {
                return $tooLong ? null : ['message' => $message, 'gateway' => $gateway];
            }

            $account->balance -= $charge;
            $account->save();

            return [
                'message' => $message,
                'gateway' => $gateway,
            ];
        });
    }

    private function unavailableReason(?SmsAccount $account, string $feature, int $segments, array $gateway): ?string
    {
        if (! $account?->enabled || ! in_array($feature, $account->allowed_features ?? [], true)) {
            return 'This SMS feature is not enabled for the tenant.';
        }

        if (! ($gateway['enabled'] ?? false) || empty($gateway['api_url']) || empty($gateway['parameters'])) {
            return 'The SMS gateway is not configured.';
        }

        if (! is_array(json_decode($gateway['parameters'], true))
            || ! is_array(json_decode($gateway['headers'] ?: '{}', true))) {
            return 'The SMS request template is invalid.';
        }

        if ($account->balance < $account->rate * $segments) {
            return 'Insufficient SMS balance.';
        }

        if ($account->monthly_limit !== null) {
            $used = SmsMessage::where('tenant_id', $account->tenant_id)
                ->whereIn('status', ['sending', 'submitted', 'uncertain'])
                ->where('attempted_at', '>=', now()->startOfMonth())
                ->sum('segments');

            if ($used + $segments > $account->monthly_limit) {
                return 'The monthly SMS limit has been reached.';
            }
        }

        return null;
    }

    // Release reserved credit only after a confirmed rejection, never after a timeout.
    private function finish(SmsMessage $message, string $status, ?string $error): void
    {
        $message->getConnection()->transaction(function () use ($message, $status, $error): void {
            $account = SmsAccount::where('tenant_id', $message->tenant_id)->lockForUpdate()->firstOrFail();
            $target = SmsMessage::whereKey($message->getKey())->lockForUpdate()->firstOrFail();

            if (! in_array($target->status, ['sending', 'uncertain'], true)) {
                return;
            }

            if (in_array($status, ['rejected', 'retry'], true)) {
                $account->balance += $target->charge;
                $account->save();
                $target->charge = 0;
            }

            $target->forceFill([
                'status' => $status,
                'error' => $error,
                'submitted_at' => $status === 'submitted' ? now() : null,
            ])->save();
        });
    }

    public function resolve(Account $actor, SmsMessage $message, string $decision): void
    {
        $actor->withAccessLock(function (Account $freshActor) use ($message, $decision): void {
            SmsAccount::where('tenant_id', $message->tenant_id)->lockForUpdate()->firstOrFail();
            $target = SmsMessage::whereKey($message->getKey())->lockForUpdate()->firstOrFail();
            Gate::forUser($freshActor)->authorize('resolveMessage', $target);
            if ($target->status !== 'uncertain') {
                throw ValidationException::withMessages(['record' => 'Only an uncertain SMS can be resolved.']);
            }

            $this->finish($target, $decision, $decision === 'rejected' ? 'Rejection confirmed by an administrator; credit returned.' : null);
        });
    }

    private function recipient(?string $value): ?string
    {
        $number = preg_replace('/[\s()+-]/', '', $value ?? '');
        if (preg_match('/\A01[3-9]\d{8}\z/', $number)) {
            $number = '88'.$number;
        }

        return preg_match('/\A[1-9]\d{9,14}\z/', $number) ? $number : null;
    }
}
