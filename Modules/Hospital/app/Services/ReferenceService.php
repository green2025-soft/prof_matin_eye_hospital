<?php

namespace Modules\Hospital\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Core\Models\Subscription;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\AccessManager;
use Modules\Hospital\Models\Reference;

class ReferenceService
{
    public function __construct(private AccessManager $access) {}

    public function save(Account $actor, ?Reference $record, array $values, int $branchId): Reference
    {
        Gate::forUser($actor)->authorize($record ? 'update' : 'create', $record ?? Reference::class);
        if ($branchId < 1 || $branchId !== (int) request()->attributes->get('branch_id')
            || ($record !== null && $record->branch_id !== $branchId)) {
            throw new AuthorizationException('This reference does not belong to the selected branch.');
        }

        try {
            return $this->access->transaction($actor, function (Account $freshActor) use ($record, $values, $branchId): Reference {
                $reference = $record ? $record->newQuery()->whereKey($record->getKey())->lockForUpdate()->firstOrFail() : new Reference;
                Gate::forUser($freshActor)->authorize($reference->exists ? 'update' : 'create', $reference);
                $tenant = Tenant::current();
                $subscription = $tenant === null ? null : Subscription::forTenant($tenant);
                if (! $subscription?->isUsable()
                    || ! $subscription->plan->allowsRoutePermission('hospital.view')
                    || ! $subscription->plan->allowsRoutePermission($reference->exists ? 'references.update' : 'references.create')
                    || (! $this->access->isOwner($freshActor) && ! $freshActor->can('hospital.view'))) {
                    throw new AuthorizationException('Your account and plan must allow this Hospital action.');
                }

                $settings = Setting::values('hospital', $reference->getConnectionName());
                foreach (['consultation_percent', 'investigation_percent', 'operation_percent'] as $field) {
                    if (! $settings['reference_percentages_enabled'] || ! $settings['reference_'.$field.'_enabled']) {
                        if (isset($values[$field])) {
                            throw ValidationException::withMessages([$field => 'Reference percentage fields are disabled in Hospital settings.']);
                        }
                        unset($values[$field]);
                    }
                    if (! $reference->exists && ! array_key_exists($field, $values)) {
                        $values[$field] = $settings['reference_defaults_enabled'] ? $settings['reference_'.$field] : '0.00';
                    }
                }

                if (! $reference->exists) {
                    $reference->branch_id = $branchId;
                    $reference->reference_id = $this->nextReferenceId($reference, $settings);
                }
                $reference->fill($values)->save();

                return $reference;
            });
        } catch (UniqueConstraintViolationException $exception) {
            if (str_contains($exception->errorInfo[2] ?? '', 'references_reference_id_unique')) {
                throw ValidationException::withMessages(['record' => 'The generated Reference ID already exists. Choose a distinct Reference ID pattern in Hospital settings.']);
            }

            throw $exception;
        } catch (QueryException $exception) {
            if ((int) ($exception->errorInfo[1] ?? 0) === 1452) {
                throw ValidationException::withMessages(['record' => 'The selected branch has changed. Reload the form and select it again.']);
            }

            throw $exception;
        }
    }

    private function nextReferenceId(Reference $reference, array $settings): string
    {
        $branchNumbering = $settings['reference_id_scope'] === 'branch';
        $scope = $branchNumbering ? 'branch:'.$reference->branch_id : 'tenant';
        $sequences = $reference->getConnection()->table('reference_sequences');
        $sequences->insertOrIgnore(['scope' => $scope, 'last_number' => 0]);
        $sequences->where('scope', $scope);
        $sequence = $sequences->lockForUpdate()->first();
        $number = (int) $sequence->last_number + 1;
        if ($number > 999999999999) {
            throw ValidationException::withMessages(['record' => 'The Reference ID numbering sequence is exhausted.']);
        }
        $sequences->update(['last_number' => $number]);

        $date = Carbon::now(config('app.timezone'));
        $formatted = strtr($settings[$branchNumbering ? 'reference_branch_id_pattern' : 'reference_id_pattern'], [
            '{BRANCH}' => (string) $reference->branch_id,
            '{YYYY}' => $date->format('Y'), '{MM}' => $date->format('m'), '{DD}' => $date->format('d'),
        ]);
        $formatted = preg_replace_callback('/\{SEQ(?::([1-9]|1[0-2]))?\}/', fn (array $match): string => str_pad((string) $number, (int) ($match[1] ?? 6), '0', STR_PAD_LEFT), $formatted);
        if (mb_strlen($formatted) > 40) {
            throw ValidationException::withMessages(['record' => 'The generated Reference ID is too long. Shorten its pattern in Hospital settings.']);
        }

        return $formatted;
    }
}
