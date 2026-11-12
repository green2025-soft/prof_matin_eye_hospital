<?php

namespace Modules\Core\Models;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use Modules\Core\Policies\SettingPolicy;

#[Fillable(['data', 'credentials'])]
#[Hidden(['secrets', 'credentials'])]
#[UsePolicy(SettingPolicy::class)]
class Setting extends BaseModel
{
    protected array $exposed = ['id', 'group', 'data', 'updated_at'];

    protected array $searchable = ['group'];

    protected array $sortable = ['group', 'id'];

    protected string $defaultSort = 'group';

    protected function casts(): array
    {
        return ['data' => 'array', 'secrets' => 'encrypted:array'];
    }

    protected static function booted(): void
    {
        static::addGlobalScope('setting_groups', function (Builder $query): void {
            $query->whereIn($query->qualifyColumn('group'), static::allowedGroups($query->getModel()->getConnectionName()));
        });

        $clearBranding = static function (self $setting): void {
            if ($setting->group !== 'branding') {
                return;
            }

            $connection = $setting->getConnection();
            $cache = Cache::store('branding');
            $key = static::brandingCacheKey($connection);
            $connection->afterCommit(fn () => $cache->forget($key));
        };

        static::saved($clearBranding);
        static::deleted($clearBranding);
    }

    public static function initialize(?string $connection = null): void
    {
        static::unguarded(function () use ($connection): void {
            foreach (static::allowedGroups($connection) as $group) {
                static::on($connection)->firstOrCreate(['group' => $group], ['data' => static::defaults($group)]);
            }
        });
    }

    public function setDataAttribute(array $values): void
    {
        $values = Arr::only($values, array_keys(static::defaults($this->group)));
        $this->attributes['data'] = $this->castAttributeAsJson('data', array_replace($this->publicValues(), $values));
    }

    public function setCredentialsAttribute(array $values): void
    {
        $fields = array_keys(config('settings.credentials.'.$this->group, []));
        $values = array_filter(Arr::only($values, $fields), fn ($value): bool => is_string($value) && filled($value));

        if ($values !== []) {
            $this->secrets = array_replace($this->secrets ?? [], $values);
        }
    }

    public static function allowedGroups(?string $connection = null): array
    {
        return ($connection ?? config('database.default')) === 'central'
            ? array_values(array_diff(array_keys(config('settings.groups')), config('settings.tenant_only_groups', [])))
            : config('settings.tenant_groups');
    }

    public static function defaults(string $group): array
    {
        return config('settings.defaults.'.$group) ?? throw new InvalidArgumentException('Unknown settings group.');
    }

    public static function values(string $group, ?string $connection = null): array
    {
        if (! in_array($group, static::allowedGroups($connection), true)) {
            throw new InvalidArgumentException('This settings group is not available in the selected application.');
        }

        if ($group === 'branding') {
            return static::brandingValues($connection);
        }

        $record = static::on($connection)->where('group', $group)->first(['group', 'data', 'secrets'])
            ?? (new static)->forceFill(['group' => $group]);

        return array_replace($record->publicValues(), $record->credentialValues());
    }

    public function publicValues(): array
    {
        $values = static::defaults($this->group);
        $storedValues = $this->data ?? [];

        foreach ($values as $field => $default) {
            $stored = $storedValues[$field] ?? null;

            if ((is_bool($default) && is_bool($stored)) || (is_string($default) && is_string($stored))
                || (is_array($default) && is_array($stored))) {
                $values[$field] = $stored;
            }
        }

        return $values;
    }

    public function configuredCredentials(): array
    {
        return array_map(fn (string $value): bool => filled($value), $this->credentialValues());
    }

    private function credentialValues(): array
    {
        $fields = array_keys(config('settings.credentials.'.$this->group, []));
        $defaults = array_fill_keys($fields, '');
        $stored = Arr::only($this->secrets ?? [], $fields);
        $strings = array_filter($stored, 'is_string');

        return array_replace($defaults, $strings);
    }

    private static function brandingValues(?string $connection): array
    {
        $database = (new static)->setConnection($connection)->getConnection();
        $read = static fn (): array => static::on($database->getName())->where('group', 'branding')
            ->first(['group', 'data'])?->publicValues() ?? static::defaults('branding');

        // Transactional reads must never publish uncommitted settings to the shared cache.
        if ($database->transactionLevel() > 0) {
            return $read();
        }

        return Cache::store('branding')->remember(static::brandingCacheKey($database), 300, $read);
    }

    private static function brandingCacheKey(Connection $connection): string
    {
        return 'settings.branding.'.hash('sha256', $connection->getName().'|'.$connection->getDatabaseName());
    }
}
