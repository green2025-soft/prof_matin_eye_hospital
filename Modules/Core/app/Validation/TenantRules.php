<?php

namespace Modules\Core\Validation;

use Illuminate\Validation\Rule;
use Modules\Core\Models\Tenant;

class TenantRules
{
    public static function normalize(array $data): array
    {
        foreach (['name', 'slug', 'owner_name'] as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = is_string($data[$field]) ? trim($data[$field]) : null;
            }
        }

        if (isset($data['slug'])) {
            $data['slug'] = strtolower($data['slug']);
        }

        if (array_key_exists('owner_email', $data)) {
            $data['owner_email'] = normalizeEmail($data['owner_email']);
        }

        if (array_key_exists('domains', $data)) {
            $data['domains'] = is_array($data['domains'])
                ? array_values(array_map(static fn (mixed $domain): ?string => is_string($domain) ? strtolower(rtrim(trim($domain), '.')) : null, $data['domains']))
                : null;
        }

        return $data;
    }

    public static function metadata(?Tenant $tenant = null): array
    {
        $domainUnique = Rule::unique('central.domains', 'domain');

        if ($tenant) {
            $domainUnique->whereNot('tenant_id', $tenant->getKey());
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:80', 'regex:/\A[a-z0-9]+(?:-[a-z0-9]+)*\z/', Rule::unique('central.tenants', 'slug')->ignore($tenant?->getKey())],
            'domains' => ['required', 'array', 'min:1', 'max:20'],
            'domains.*' => [
                'required', 'string', 'max:253', 'distinct',
                'regex:/\A[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?(?:\.[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?)*\z/',
                Rule::notIn(config('multitenancy.central_domains', [])), $domainUnique,
            ],
        ];
    }

    public static function owner(): array
    {
        return [
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:12', 'max:4096'],
        ];
    }

    public static function database(): array
    {
        return [
            'required', 'string', 'max:64', 'regex:/\A[a-zA-Z0-9_]+\z/',
            Rule::notIn([config('database.connections.central.database')]),
            Rule::unique('central.tenants', 'database'),
        ];
    }
}
