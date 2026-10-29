<?php

namespace Modules\Core\Validation;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Modules\Core\Models\Domain;
use Modules\Core\Models\Tenant;

class TenantRules
{
    public static function availableSlug(string $name): string
    {
        $base = static::slugFromName($name);
        $slug = $base;
        $suffix = 1;

        while (Tenant::query()->where('slug', $slug)->exists()
            || Domain::query()->where('domain', static::defaultDomain($slug))->exists()
            || in_array(static::defaultDomain($slug), config('multitenancy.central_domains', []), true)) {
            $ending = '-'.++$suffix;
            $slug = rtrim(substr($base, 0, 63 - strlen($ending)), '-').$ending;
        }

        return $slug;
    }

    public static function slugFromName(string $name): string
    {
        $slug = Str::slug($name);
        $slug = $slug === '' ? 'tenant' : $slug;

        return rtrim(substr($slug, 0, 63), '-');
    }

    public static function defaultDomain(string $slug): string
    {
        return $slug.'.'.config('multitenancy.tenant_base_domain');
    }

    public static function normalize(array $data): array
    {
        foreach (['name', 'slug', 'owner_name', 'address', 'phone'] as $field) {
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
            'address' => ['nullable', 'string', 'max:2000'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/\A[0-9]+\z/'],
            'has_branches' => ['sometimes', 'boolean'],
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
            'password' => ['required', 'string', 'min:'.config('auth.password_min_length', 5), 'max:4096'],
        ];
    }

    public static function logo(): array
    {
        return ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'mimetypes:image/jpeg,image/png,image/webp,image/gif', 'max:2048'];
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
