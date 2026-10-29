<?php

namespace Modules\Core\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Core\Contracts\PaymentGateway;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\CheckoutAttempt;
use Modules\Core\Models\Invoice;
use Modules\Core\Models\Payment;
use Modules\Core\Models\Setting;
use Modules\Core\Models\Tenant;
use Modules\Core\Policies\BillingPolicy;
use SensitiveParameter;

class CheckoutService
{
    public function __construct(private PaymentService $payments, private BillingPolicy $policy) {}

    public function options(Account $actor, Invoice $invoice): array
    {
        $canPay = $this->policy->pay($actor, $invoice);
        $available = $invoice->currency === 'BDT' ? Payment::PROVIDERS : ['manual'];
        $providers = [];
        $manualInstructions = '';

        if ($canPay) {
            $settings = Setting::on('central')->whereIn('group', $available)
                ->where('data->enabled', true)->get(['group', 'data']);

            foreach ($settings as $setting) {
                $values = $setting->publicValues();
                $charge = Money::minor($values['extra_charge']);
                $providers[$setting->group] = [
                    'label' => config('settings.groups.'.$setting->group.'.label'),
                    'extraCharge' => Money::decimal($charge),
                    'total' => Money::decimal($invoice->outstandingMinor() + $charge),
                ];

                if ($setting->group === 'manual') {
                    $manualInstructions = $values['instructions'];
                }
            }
        }

        $active = $canPay ? CheckoutAttempt::query()->where('active_invoice_id', $invoice->getKey())
            ->first(['id', 'provider', 'status', 'redirect_url', 'submitted_reference', 'amount_minor', 'extra_charge_minor', 'created_at']) : null;

        return [
            'canPay' => $canPay,
            'providers' => $providers,
            'defaultProvider' => isset($providers['manual']) ? 'manual' : array_key_first($providers),
            'manualInstructions' => $manualInstructions,
            'activeAttempt' => $active === null ? null : [
                'reference' => $active->submitted_reference,
                'url' => $active->status === CheckoutAttempt::STATUS_READY ? $active->redirect_url : null,
                'extraCharge' => Money::decimal($active->extra_charge_minor),
                'total' => Money::decimal($active->amount_minor),
                'message' => match ($active->status) {
                    CheckoutAttempt::STATUS_REVIEW => 'Your manual payment is awaiting platform approval.',
                    CheckoutAttempt::STATUS_READY => 'A checkout is already open. Resume it to complete your payment.',
                    default => 'Your payment request is being prepared. Please check again shortly.',
                },
            ],
            'checkoutAction' => $canPay ? route('tenantcore.billing.checkouts.store', ['invoice' => $invoice->getKey()]) : null,
        ];
    }

    public function save(Account $actor, ?Model $record, array $values): CheckoutAttempt
    {
        if ($record !== null) {
            throw new AuthorizationException;
        }

        $invoice = Invoice::query()->findOrFail($values['invoice_id']);
        abort_unless($this->policy->pay($actor, $invoice), 403);
        $provider = $values['provider'];
        $settings = Setting::values($provider, 'central');

        if (! $settings['enabled'] || ($provider !== 'manual' && $invoice->currency !== 'BDT')) {
            throw ValidationException::withMessages(['provider' => 'Select an enabled payment provider for this currency.']);
        }

        $this->assertEnvironment($settings);

        $attempt = $invoice->locked(function (Invoice $current) use ($actor, $values, $provider, $settings): CheckoutAttempt {
            abort_if($current->tenant()->lockForUpdate()->value('status') === Tenant::STATUS_DELETING, 409, 'This tenant is being deleted.');
            abort_unless($this->policy->pay($actor, $current), 403);
            $active = CheckoutAttempt::query()->where('active_invoice_id', $current->getKey())->lockForUpdate()->first();

            if ($active !== null) {
                if ($active->provider !== $provider || $active->status === CheckoutAttempt::STATUS_CREATING) {
                    throw ValidationException::withMessages(['provider' => 'A payment request already exists for this invoice. Resume it or wait for its result.']);
                }

                return $active;
            }

            $attempt = new CheckoutAttempt;
            $extraCharge = Money::minor($settings['extra_charge']);
            $amount = $current->outstandingMinor() + $extraCharge;

            if ($amount > Invoice::MAX_MINOR) {
                throw ValidationException::withMessages(['provider' => 'The total including extra charge exceeds the payment limit.']);
            }

            $attempt->forceFill([
                'invoice_id' => $current->getKey(),
                'active_invoice_id' => $current->getKey(),
                'token' => (string) Str::uuid(),
                'reference' => Str::upper(Str::random(20)),
                'provider' => $provider,
                'amount_minor' => $amount,
                'extra_charge_minor' => $extraCharge,
                'currency' => $current->currency,
                'status' => $provider === 'manual' ? CheckoutAttempt::STATUS_REVIEW : CheckoutAttempt::STATUS_CREATING,
                'submitted_reference' => $provider === 'manual' ? $values['submitted_reference'] : null,
                'notes' => $values['notes'] ?? null,
                'credential_snapshot' => $provider === 'manual' ? null : $settings,
                'return_url' => $values['return_url'],
                'expires_at' => now()->addDay(),
            ]);
            $attempt->save();

            return $attempt;
        });

        if ($attempt->status !== CheckoutAttempt::STATUS_CREATING) {
            return $attempt;
        }

        $settings['notify_url'] = $this->callbackUrl($attempt, 'gateway.notify');

        try {
            $checkout = $this->gateway($provider)->start($attempt->load('invoice'), $settings, $this->callbackUrl($attempt));
        } catch (ValidationException $exception) {
            $current = $this->finishStart($attempt, [
                'status' => CheckoutAttempt::STATUS_FAILED,
                'active_invoice_id' => null,
                'credential_snapshot' => null,
            ]);

            if ($current->status !== CheckoutAttempt::STATUS_FAILED) {
                return $current;
            }

            throw $exception;
        }

        return $this->finishStart($attempt, [
            'provider_reference' => $checkout->reference,
            'redirect_url' => $checkout->url,
            'status' => CheckoutAttempt::STATUS_READY,
        ]);
    }

    public function verify(string $provider, #[SensitiveParameter] string $token, array $callback = []): CheckoutAttempt
    {
        $attempt = CheckoutAttempt::query()->where('token', $token)->where('provider', $provider)->firstOrFail();

        if (in_array($attempt->status, [CheckoutAttempt::STATUS_PAID, CheckoutAttempt::STATUS_FAILED], true)) {
            return $attempt;
        }

        $settings = $attempt->credential_snapshot ?? [];
        $this->assertEnvironment($settings);
        $result = $this->gateway($provider)->verify($attempt, $settings, $callback);

        if ($result->status === 'pending') {
            return $attempt;
        }

        return $attempt->invoice->locked(function (Invoice $invoice) use ($attempt, $result): CheckoutAttempt {
            $current = CheckoutAttempt::query()->whereKey($attempt->getKey())->lockForUpdate()->firstOrFail();

            if (in_array($current->status, [CheckoutAttempt::STATUS_PAID, CheckoutAttempt::STATUS_FAILED], true)) {
                return $current;
            }

            if ($result->status === 'paid') {
                if ($result->amountMinor !== $current->amount_minor || $result->currency !== $current->currency
                    || $result->merchantReference !== $current->reference || ! filled($result->reference)) {
                    throw ValidationException::withMessages(['gateway' => 'Payment verification did not match the invoice.']);
                }

                $payment = $this->payments->record($invoice, [
                    'provider' => $current->provider,
                    'idempotency_key' => 'checkout:'.$current->token,
                    'provider_reference' => $result->reference,
                    'amount_minor' => $current->amount_minor - $current->extra_charge_minor,
                    'extra_charge_minor' => $current->extra_charge_minor,
                    'checkout_id' => $current->getKey(),
                ]);
                $current->payment_id = $payment->getKey();
                $current->status = CheckoutAttempt::STATUS_PAID;
            } elseif ($result->status === 'failed') {
                $current->status = CheckoutAttempt::STATUS_FAILED;
            } else {
                throw ValidationException::withMessages(['gateway' => 'The payment provider returned an unknown result.']);
            }

            $current->active_invoice_id = null;
            $current->credential_snapshot = null;
            $current->save();

            return $current;
        });
    }

    public function approve(Account $actor, CheckoutAttempt $attempt): CheckoutAttempt
    {
        return app(AccessManager::class)->transaction($actor, function (Account $freshActor) use ($attempt): CheckoutAttempt {
            Gate::forUser($freshActor)->authorize('create', Payment::class);

            return $attempt->invoice->locked(function (Invoice $invoice) use ($freshActor, $attempt): CheckoutAttempt {
                $current = CheckoutAttempt::query()->whereKey($attempt->getKey())->lockForUpdate()->firstOrFail();

                if ($current->status === CheckoutAttempt::STATUS_PAID) {
                    return $current;
                }

                $this->assertReviewable($current);
                $payment = $this->payments->record($invoice, [
                    'provider' => 'manual',
                    'idempotency_key' => 'checkout:'.$current->token,
                    'provider_reference' => $current->submitted_reference,
                    'amount_minor' => $current->amount_minor - $current->extra_charge_minor,
                    'extra_charge_minor' => $current->extra_charge_minor,
                    'notes' => $current->notes,
                    'checkout_id' => $current->getKey(),
                ], $freshActor);
                $current->forceFill(['payment_id' => $payment->getKey(), 'status' => CheckoutAttempt::STATUS_PAID, 'active_invoice_id' => null])->save();

                return $current;
            });
        });
    }

    public function reconcile(CheckoutAttempt $attempt): CheckoutAttempt
    {
        $current = CheckoutAttempt::query()->whereKey($attempt->getKey())->firstOrFail();

        if ($current->status === CheckoutAttempt::STATUS_CREATING) {
            $current = $current->invoice->locked(function () use ($current): CheckoutAttempt {
                $pending = CheckoutAttempt::query()->whereKey($current->getKey())->lockForUpdate()->firstOrFail();

                // No checkout URL reached the customer before this interrupted initialization.
                if ($pending->status === CheckoutAttempt::STATUS_CREATING
                    && $pending->provider_reference === null && $pending->redirect_url === null
                    && $pending->payment_id === null && $pending->expires_at?->isPast()
                    && $pending->created_at->lte(now()->subDay())) {
                    $pending->forceFill([
                        'status' => CheckoutAttempt::STATUS_FAILED,
                        'active_invoice_id' => null,
                        'credential_snapshot' => null,
                    ])->save();
                }

                return $pending;
            });
        }

        return $current->status === CheckoutAttempt::STATUS_READY
            ? $this->verify($current->provider, $current->token) : $current;
    }

    public function reject(Account $actor, CheckoutAttempt $attempt, ?string $reason): CheckoutAttempt
    {
        return app(AccessManager::class)->transaction($actor, function (Account $freshActor) use ($attempt, $reason): CheckoutAttempt {
            Gate::forUser($freshActor)->authorize('create', Payment::class);

            return $attempt->invoice->locked(function () use ($attempt, $reason): CheckoutAttempt {
                $current = CheckoutAttempt::query()->whereKey($attempt->getKey())->lockForUpdate()->firstOrFail();
                $this->assertReviewable($current);
                $current->forceFill(['status' => CheckoutAttempt::STATUS_FAILED, 'active_invoice_id' => null,
                    'notes' => filled($reason) ? $reason : $current->notes])->save();

                return $current;
            });
        });
    }

    private function assertReviewable(CheckoutAttempt $attempt): void
    {
        if ($attempt->provider !== 'manual' || $attempt->status !== CheckoutAttempt::STATUS_REVIEW) {
            throw ValidationException::withMessages(['payment' => 'Only a pending manual payment can be reviewed.']);
        }
    }

    private function finishStart(CheckoutAttempt $attempt, #[SensitiveParameter] array $values): CheckoutAttempt
    {
        return $attempt->invoice->locked(function () use ($attempt, $values): CheckoutAttempt {
            $current = CheckoutAttempt::query()->whereKey($attempt->getKey())->lockForUpdate()->firstOrFail();

            if ($current->status === CheckoutAttempt::STATUS_CREATING) {
                $current->forceFill($values)->save();
            }

            return $current;
        });
    }

    private function gateway(string $provider): PaymentGateway
    {
        $driver = config('billing.drivers.'.$provider);
        abort_unless(is_string($driver) && is_subclass_of($driver, PaymentGateway::class), 404);

        return app($driver);
    }

    private function callbackUrl(CheckoutAttempt $attempt, string $name = 'gateway.callback'): string
    {
        $path = route($name, ['gateway' => $attempt->provider, 'token' => $attempt->token], false);
        $base = config('billing.callback_base_url');

        if (! filled($base)) {
            $request = request();
            $port = in_array($request->getPort(), [80, 443], true) ? '' : ':'.$request->getPort();
            $base = $request->getScheme().'://'.config('multitenancy.central_domains.0').$port;
        }

        return rtrim($base, '/').$path;
    }

    private function assertEnvironment(#[SensitiveParameter] array $settings): void
    {
        if (app()->isProduction() && ($settings['sandbox'] ?? false)) {
            throw ValidationException::withMessages(['provider' => 'Configure live gateway credentials before accepting production payments.']);
        }
    }
}
