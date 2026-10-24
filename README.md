# Laravel Modular Boilerplate

Reusable Laravel 13 foundation with exactly two modules: **Core** for platform/shared infrastructure and **TenantCore** for tenant accounts. It uses Blade, Bootstrap 5, jQuery, locally bundled Font Awesome Free 7.3.1, and Vite. Spatie multitenancy provides separate tenant databases and native context switching; platform and tenant accounts share session authentication with isolated identities. The foundation includes tenant/account/access management, plans/subscriptions, invoices/payments, checkout, and grouped settings. Project-specific business workflows remain unspecified.

## Requirements

- PHP 8.4.1 or later within PHP 8, with the extensions required by Composer.
- Composer 2 and Node.js compatible with Vite 8: Node 20.19.x or Node 22.12 and later.
- MySQL when application database connections and module migrations are configured.

## Local setup

From the project root:

```powershell
composer install
npm ci
```

Only for a fresh checkout without an existing `.env`:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Preserve an existing `.env` and application key. Configure MySQL credentials, `DB_CONNECTION=central`, `CENTRAL_DB_DATABASE`, and `CENTRAL_DOMAINS` in `.env`. The default platform host is `platform.localhost`. The central database must differ from any tenant database. Optional `DB_PROVISION_USERNAME`/`DB_PROVISION_PASSWORD` supply database-creation credentials; otherwise the MySQL credentials are used. Setup commands need permission to create databases and apply schema changes.

The platform tenant form generates a unique slug from the workspace name and suggests its domain. Set `TENANT_BASE_DOMAIN=example.com` to suggest `workspace.example.com`; `platform.localhost` defaults to `workspace.localhost`. Domains remain editable. Tenant profiles support optional address/phone, a PNG/JPG/WebP/GIF logo up to 2 MB, and a branches flag without branch management. Applicable text fields need at least 5 characters; phone numbers require at least 5 digits. Logo uploads need the public storage link (`php artisan storage:link`).

Create the platform and its separate administrator, then the first tenant with its own database:

```powershell
php artisan platform:install
php artisan tenant:create
```

Commands prompt for account details and a hidden password (minimum 5 characters by default, configured by `auth.password_min_length`). For the local tenant, supply `localhost,127.0.0.1` as its domains. To attach an existing tenant database/account, use `php artisan tenant:adopt` instead; its password is preserved. Resume an unfinished setup with `php artisan tenant:retry SLUG` (add `--adopt` for an adoption). No fixed password is seeded. File sessions/cache and synchronous queues remain the development defaults.

```powershell
npm run build
php artisan serve
```

Open `http://platform.localhost:8000/auth/login` for the platform, or `http://127.0.0.1:8000/auth/login` for the registered local tenant. Each uses its own administrator. Unknown/inactive tenant hosts return 404. The root page requires authentication. Login POST uses `throttle:login`, allowing five submissions per scope/email/IP per minute. Build assets again after CSS/JavaScript changes, or use `composer dev` for Laravel and Vite together. Serve only `public/` with Laragon/another web server; production uses built assets and a configured web server.

Forgot password is available at `/auth/forgot-password` on the appropriate platform or tenant host. Reset tokens use Laravel's separate central `admins` and tenant `users` brokers. Mail defaults to `MAIL_MAILER=log`, which records reset messages locally and sends no email. Configure a working SMTP transport (`MAIL_MAILER=smtp`, host, port, credentials, and sender) or another supported mail provider before relying on email delivery.

Authorized platform accounts manage tenants at `/core/tenants` and platform accounts at `/core/admins`; tenant accounts are managed at `/users` on the tenant host. The tenant dashboard action uses an authorized, short-lived, single-use handoff to that tenant's domain. `/core/roles` manages role names only. The separate `/core/permissions` page selects a role and manages its grouped grants in the current database; permission viewing, grant management, and assigning roles to accounts have distinct policy abilities. Policies protect the reserved owner role and prevent granting access the actor does not hold. Tenant management has no database-delete action.

Lists use numbered pagination with 10 rows by default. Choose 5/10/15/20/30/50/100 or All; All shows every filtered, authorized record on page 1. Changing size resets page 1, while filters and sorting persist.

Grouped company/branding/general settings are isolated per database; platform billing and payment settings stay central. The Payments tab has fixed Manual/SSLCommerz/bKash/Nagad/EPS buttons and loads only the selected provider. Manual has enabled/instructions controls; every method has a fixed extra charge, default `0.00`, without percentages. Save/error redirects retain the selected provider, and legacy gateway group URLs remain supported. Metadata comes from `config/settings.php` and input validation from `SettingRequest`. Credentials are encrypted, hidden, never prefilled, and preserved when left blank. Explicit setup/migration preserves saved values. The sidebar shows authorized pages and current-database branding.

## Billing and deployment

Platform screens live under `/core/plans`, `/core/subscriptions`, `/core/invoices`, `/core/payments`, and `/core/settings`. Invoices includes pending manual payment review; there is no separate platform Billing page. Tenant `/billing` shows its subscription/invoices and starts checkout; `/settings` manages its allowed groups. A submitted reference alone never marks an invoice paid. Checkout snapshots the fixed charge, verifies the total received, and credits only the invoice principal; receipts show principal, charge, and total separately.

Setup creates the free Starter plan and enrolls tenants without charging them. `platform:migrate` also enrolls existing active/suspended tenants that lack a subscription. Free plans can upgrade immediately; paid current periods are preserved. Paid renewal produces an invoice rather than an automatic charge. Issued invoices and payment records retain financial history.

For an existing installation, apply the central schema before tenant schema:

```powershell
php artisan platform:migrate
php artisan tenants:migrate
```

Add `--force` in production. These commands initialize missing settings/permissions and preserve saved values. Configure `php artisan schedule:run` every minute; the native `GenerateDueInvoices` job maintains due subscriptions/invoices hourly, and payment reconciliation runs every five minutes. The Invoices page's Generate Invoices action uses that same workflow. Manual commands remain `php artisan payments:reconcile --force` and `php artisan subscriptions:sync --force`; none automatically charges customers.

Enable a gateway only after entering its merchant credentials in platform Settings and choosing the proper sandbox/live environment. Set `PAYMENT_CALLBACK_BASE_URL` to the public HTTPS canonical central URL so providers can reach callbacks. The adapters verify the order/reference, amount, and currency before settlement; production rejects sandbox payments. Live collection still needs merchant-specific verification. No successful live payment is claimed by the source, lint, or asset build alone.

## Tenant table and column changes

Create a new migration for each schema change; keep already-applied files unchanged:

```powershell
php artisan make:migration add_phone_to_users_table --table=users --path=Modules/TenantCore/database/migrations
```

Define the column in that migration, then run:

```powershell
php artisan tenants:migrate
```

This updates **every registered tenant database**, including suspended tenants. Each database tracks its own applied migrations; reruns skip completed files, and newly created tenants receive the latest schema. Shared account/permission changes belong in `Modules/Core/database/migrations/shared`; tenant-only changes belong in TenantCore. Central registry changes belong in Core's `database/migrations/central`.

Use `php artisan tenants:migrate --pretend` to preview SQL or `--tenant=ID_OR_SLUG` to select tenants (repeat for multiple). Digit-only selectors mean IDs. Production requires `--force`. Failed tenants are listed, other tenants continue and the command returns a nonzero exit code; fix the cause and rerun. There is no automatic rollback across databases. Apply populated-table changes in safe stages; never use `migrate:fresh` on real tenants. Schema work runs explicitly under a per-database lock after ownership verification, never during provider boot or an ordinary page request.

For production, configure the environment, run `composer install --no-dev --optimize-autoloader`, build assets, then run `composer optimize` for config/event/route/view caches. `composer optimize-clear` clears generated caches while preserving application-cache entries; keep ordinary development uncached. Providers and the shared asset pipeline stay lean; performance claims require repeated measurements through the HTTP runtime.

## Checks

```powershell
composer lint
npm run build
```

Use `composer format` to apply PHP formatting. Verify browser and database behavior manually as features are added; automated test scaffolding is outside the current project scope.

## Architecture and AI tools

[AGENTS.md](./AGENTS.md) and [ARCHITECTURE.md](./ARCHITECTURE.md) define module ownership, database/session boundaries, and the Hrm-style Blade flow: resource controller → `BaseController` → model; resource request → `BaseRequest`. Models declare safe query/display fields and shared query behavior. Typed leaf `index()`/`store()` methods delegate to `listData()`/`saveData()`; the base shares create/edit/destroy, forms, and redirects. One concrete Core `AccountController` serves both `/core/admins` and `/users`. Route prefixes derive from trusted matched resource names instead of leaf properties. One Blade form serves create/edit, and native resource updates map to `store()`. Fields remain direct HTML/Blade, shared markup uses ordinary partials, and helpers contain only reusable value/selection/validation logic.

`PermissionController` uses the same base controller/request flow with service-prepared matrix data and a redirect retaining the selected role. Full grant updates require the submission marker and complete checkbox array; `partialUpdates=false` keeps those requirements, including intentional empty selections.

Ordinary CRUD follows five parts: Controller, Model, Request, View, and Route. Views contain the index and shared create/edit form; schema and policy registration remain required wiring. Requests own input validation and normalization. Models keep fields, casts, relations, query metadata, and intrinsic facts; controllers prepare form values through protected `formData(Account, Model)`. Plan, Role, and Setting use shared persistence without resource services. Role selects the shared access transaction before target locking. Existing `SubscriptionService::createUser()` coordinates account quotas for `AccountService`; services remain for substantial access, provisioning, subscription, invoice, and settlement workflows.

`nwidart/laravel-modules`, its Composer merge integration, `spatie/laravel-permission`, and `spatie/laravel-multitenancy` are installed. Module providers register their own routes with native `loadRoutesFrom(module_path(...))`; each module groups `web`/`auth`, its name prefix, and central/tenant restrictions. Core declares the `core` URL prefix once; tenant account URLs remain `/users`. Root routes own home and shared login/logout/password recovery under `auth`. Tenant-aware jobs enforce lifecycle eligibility; central jobs implement Spatie `NotTenantAware`. Authorize actions before temporarily switching tenant context.

Inspect the existing modules with `php artisan module:list`. Module creation uses native `php artisan module:make ModuleName` with an explicitly approved name; `php artisan module:make` alone is incomplete. The command creates a lean skeleton, not a complete CRUD feature. Core and TenantCore already exist: do not regenerate or `--force` them, and obtain an explicit scope change before adding another module.

Laravel Boost, Pint, Pail, Collision, and Tinker are retained development tools. Portable Boost MCP configuration lives in [.codex/config.toml](./.codex/config.toml); project-local skills include [Laravel fullstack guidance](./.agents/skills/laravel-fullstack-development/SKILL.md), [Laravel best practices](./.agents/skills/laravel-best-practices/SKILL.md), and [Infer conventions](./.agents/skills/infer-conventions/SKILL.md). Run Infer conventions only when explicitly requested. Keep these files when cloning or moving the project. Project instructions take precedence over generic skill examples; Boost-generated guidelines and testing/Tailwind skills are disabled.

The admin UI uses a shared responsive sidebar, account menu, dashboard, and compact CRUD forms/tables. Debugbar is available for development with `DEBUGBAR_ENABLED=true`; it is disabled by default so its toolbar does not cover the admin interface.
