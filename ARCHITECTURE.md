# Application Architecture

## Contract and current state

[AGENTS.md](./AGENTS.md) and this document define the contract for a reusable Laravel modular boilerplate. Build the foundation before project-specific work. The application uses Blade, Bootstrap 5, HTML forms, and session authentication on the `web` guard. Project instructions take precedence over conflicting examples in reusable skills.

Laravel 13 is installed at the project root on PHP `^8.4.1`, with Vite, Bootstrap 5, Popper, Font Awesome Free 7.3.1, and an admin dashboard. `nwidart/laravel-modules` 13.0.0, `spatie/laravel-permission` 8.3.2, and `spatie/laravel-multitenancy` 4.2.2 are installed with module Composer merge integration. Exactly two modules, Core and TenantCore, are registered with functional providers. Laravel Boost, Pint, Pail, Collision, and Tinker support development.

Core's Admin and TenantCore's User inherit shared Account behavior and Spatie `HasRoles` on guard `web`. Admin pins the central connection; User uses Spatie's tenant connection trait. Shared account/session/permission migrations serve both database types. The foundation includes authenticated dashboards, password recovery, isolated tenant databases, explicit setup/adoption/migrations, tenant/account/access management, plans/subscriptions, invoicing/payments, checkout, and grouped settings. Preserve application keys, credentials, and database data. Project-specific business workflows remain unspecified; source implementation does not establish a successful live merchant payment.

The starter uses file sessions/cache and a synchronous queue. The health route requires no database; tenant host resolution needs the installed central registry. `APP_TIMEZONE=Asia/Dhaka` controls application time. Installation/provisioning/deployment commands explicitly create or migrate the intended database; requests and provider boot never do this work.

## Modules and ownership

Keep exactly two modules at the project root, alongside Laravel's `app/`:

| Module | Owns | Boundary |
| --- | --- | --- |
| `Modules/Core` | Platform administrators, tenant/domain registry, shared account schema, tenancy/setup, plans/subscriptions/billing, settings, shared auth/views/helpers | Independent of concrete TenantCore classes |
| `Modules/TenantCore` | Tenant User model, tenant account operations, and tenant-only schema | Implements Core's TenantAccounts boundary |

Keep business structure unspecified until project requirements exist; do not invent additional feature modules or model hierarchies. Keep contracts with the module that owns the capability; Core receives only contracts that are genuinely shared infrastructure. Use explicit public operations across boundaries, avoid circular dependencies, and do not write directly to another module's tables.

The selected scope is full SaaS. Core owns platform administrator identities, the tenant/domain registry, and financial records in a central database. Each tenant has its own database; TenantCore owns its users and permission data. Preserve the existing first tenant's account; the platform administrator is a separate identity. Credentials belong in operator-managed secrets, not source or seeders.

Spatie's automatic finder is disabled. `ResolveApplication` resolves trusted central hosts or active registered tenant domains after boot and invokes native tenant switching; unknown/inactive tenant domains return 404. The native database task runs before the application settings task, which scopes cache/permissions/storage and selects the default connection. Resolve before cookies/sessions; verify session scope immediately after StartSession and before authentication. Keep the context until middleware termination so exception rendering, streamed responses and response collectors see the correct identity; then restore central state and reset web managers.

The public guard remains `web`. A small factory uses Laravel's unchanged SessionGuard with a context-qualified constructor name and the resolved Admin/User provider. Session cookies are host-only and scope-specific; session context and Spatie's tenant-session checks reject cross-context reuse. Remember-cookie names and authentication session keys also include the scope. Authorized platform entry to a tenant dashboard uses a short-lived, single-use handoff bound to the tenant/domain; it does not share the platform session. Resolve the HTTP actor and authorize before entering `Tenant::execute()`; pass explicit values into that callback and never run the HTTP actor's gates or reuse another tenant's loaded models inside it. Native `Tenant::execute()` restores previous context even if its callback throws.

Native tenant-aware queues retain tenant identity; a tenant job must also enforce lifecycle eligibility. Central jobs explicitly implement Spatie `NotTenantAware`: the installed package rejects default-aware jobs without a tenant ID. Database session storage and queue transport/batches/failures are pinned centrally, while cache and uploaded-file locations use the active context. File sessions/cache and sync queues remain the configured development defaults.

## Installation and schema changes

`platform:install` explicitly creates/claims the central database, applies central/shared migrations and creates the platform owner. `tenant:create` registers a tenant, allocates a server-generated immutable database name/token, claims that database, applies the shared/tenant schema and creates its owner. `tenant:adopt` explicitly attaches an existing database and preserves its owner/password. A failed setup retains its registration; `tenant:retry SLUG` resumes it with the original ownership token (`--adopt` for adoption). Active/suspended tenants cannot be reprovisioned. Core invokes tenant account operations through the owner-defined contract rather than writing tenant users directly.

`platform:install`/`platform:migrate` initialize missing central settings and the free Starter plan, synchronize permissions, and enroll existing active/suspended tenants only when a subscription is absent. Tenant setup and `tenants:migrate` initialize tenant settings; successful provisioning enrolls the free plan before activating the tenant. Existing settings, subscriptions, credentials, and account passwords are preserved. Default enrollment never creates a paid invoice or charges a customer.

Tenant forms generate a unique slug from the workspace name and suggest `{slug}.{TENANT_BASE_DOMAIN}`. The primary local platform uses `localhost:8000`; the default tenant suggestion is `workspace.localhost`, and tenants cannot claim a central host. The base defaults to the central host's parent or the host itself when it has no dot; production can set `TENANT_BASE_DOMAIN=example.com`. Domains remain editable, and edits preserve existing values unless explicitly changed. The profile includes optional address/phone, a strict PNG/JPG/WebP/GIF logo up to 2 MB, and the boolean `has_branches`; no branch CRUD or branch table is implied. Logos use generated central public-disk paths, retain the old image on failure, and clean replaced/unreferenced files.

Database ownership uses a metadata marker bound to the application key and central/tenant token. Existing foreign markers are never overwritten. An unmarked populated database requires explicit adoption. Keep the application key stable; key rotation requires an explicit marker rotation/recovery procedure. An interrupted claim with an empty metadata table requires operator repair rather than automatic takeover. A MySQL advisory lock serializes schema work for each database. Database deletion is not provided.

Migration paths are declared once in `config/multitenancy.php`: root infrastructure plus Core `database/migrations/shared` and `central` for the platform; shared schema plus TenantCore `database/migrations` for tenants. The optional tenant-only directory is included when present. Existing migration filenames remain unchanged so adoption preserves applied history.

Add each table/column change as a new native Artisan migration in the owning path; never edit a migration already applied to a real database. `php artisan tenants:migrate` lazily visits every registered tenant, including suspended/failed records, verifies ownership and applies pending migrations on its own connection. Each database has its own `migrations` table. New tenants use those same paths and receive the latest schema. Use `--tenant=ID_OR_SLUG` for selected tenants, `--pretend` for a preview and `--force` in production. Digit-only selectors mean IDs. A failure is reported with its tenant ID, other tenants continue and the command exits unsuccessfully; fix and rerun to apply the remaining migrations. There is no transaction spanning separate databases and no automatic rollback. Use forward fixes and staged nullable/backfill/constraint migrations for populated tables. Never run `migrate:fresh` against real tenant databases.

## Subscriptions, billing, and settings

All plans, subscriptions, invoices, payments, and checkout attempts are central records. Tenant billing policies restrict access to the current tenant; platform financial actions require their separate abilities. Manual payment entry asks only for Invoice ID and Amount received; its hidden replay key is system-generated. PaymentController prepares form/receipt data; PaymentService handles saving and settlement. Financial records have no generic delete workflow. Money uses integer minor units and explicit decimal conversion, never floating-point calculations.

Plan and Setting use the ordinary Controller/Model/Request/View/Route flow without a resource service. Plan owns exact price conversion; `PlanRequest` keeps the default Starter slug fixed, its price zero, and its active flag enabled. `PlanPolicy` prevents deletion of the default or referenced plans. Manage plan route access separately at `/core/plan-permissions` through its own controller/requests and shared persistence; ordinary plan edits preserve it. Plan `route_permissions` are an additional limit on mapped tenant actions: `null` allows all and `[]` allows none; account roles/policies still authorize each action. Login/password recovery, home, and Billing recovery stay outside that plan limit. A subscription snapshots price, currency, and monthly/yearly interval. A free plan may change immediately; an already usable paid period must finish before a plan change. Trials are not repeatedly granted. Paid periods require settled invoices; free renewals do not create charges. Cancellation can end access now or at period end.

`Subscription::forTenant(Tenant)` loads the plan for eligibility and feature checks. `AccountService` calls the existing `SubscriptionService::createUser(Tenant, Account, Closure)` to hold the central subscription row lock while checking the account limit and finishing the tenant account transaction. Do not replay that cross-database callback through an outer transaction retry. There is no separate subscription-access forwarding service. Substantial subscription lifecycle, provisioning, invoicing, access mutation, and checkout/settlement workflows retain services.

Invoice forms offer Draft or Issued; saving Issued requires issue authorization and finalizes the invoice. The due-date field uses bundled Flatpickr in the shared JavaScript/CSS and submits `Y-m-d`; its Request still validates the value. Requests validate and calculate line amounts/totals; issuance preserves financial snapshots. Paid/partially paid states derive from recorded payments. Unnumbered invoices display `Invoice #ID`; issuing a legacy unissued invoice preserves recorded money and derives its payment status. Subscriptions produce one invoice for a billing period, and settlement is idempotent. The platform Invoices page includes pending manual payment review; tenant Billing remains separate, with no platform Billing page. Manual submission records a reference, not a settled payment; approval records settlement and rejection leaves accounting unchanged. Checkout snapshots invoice principal plus a fixed extra charge, verifies that gross amount, and credits only principal to the invoice. Payment records/receipts retain the charge separately, and replay must match it. Historical receipts do not reactivate cancelled or elapsed periods.

SSLCommerz, bKash, Nagad, and EPS use the shared gateway contract. Providers start disabled; operators supply merchant credentials and select the correct environment. Persist encrypted, hidden credential snapshots for outstanding checkout verification. Settle only after server-side verification matches the stored order/reference, amount, and currency; neither a redirect nor a submitted status proves payment. Production rejects sandbox settlement. Live testing requires actual merchant credentials and a publicly reachable HTTPS `PAYMENT_CALLBACK_BASE_URL` pointing to the canonical central application; local build/lint success is not live-payment evidence.

Settings use fixed grouped singleton records in each active database. `config/settings.php` owns metadata/defaults and `SettingRequest` validates/normalizes input; the model persists validated values and encrypted credentials. `Setting::values(group, connection)` merges defaults and saved values; gateway readers explicitly use central. Tenant groups are company/branding/general. Branding accepts strict PNG/JPG/WebP/GIF logos up to 2 MB and PNG/ICO favicons up to 512 KB, stored with generated names on the current scoped public disk. Requests reject supplied paths; the controller preserves old files on failure and deletes replaced files after commit. Central Payments uses fixed Manual/SSLCommerz/bKash/Nagad/EPS subbuttons and loads one selected provider record per request. Individual gateway records/secrets remain separate; manual defaults enabled with instructions, and every provider has a fixed two-decimal `extra_charge` defaulting to `0.00`, never a percentage. Save/errors preserve the provider; legacy group/edit URLs select that tab. Missing rows initialize only during explicit setup/migration. Blank credentials preserve hidden saved values. Read current-plane logo/favicon URLs and branding in `LayoutComposer`, never during boot.

Laravel schedules the native `GenerateDueInvoices` job hourly to maintain due subscriptions and invoices. The Invoices Generate Invoices action dispatches the same workflow; manual maintenance remains `subscriptions:sync --force`. `payments:reconcile --force` runs every five minutes to recheck outstanding gateway attempts. Run Laravel's scheduler each minute in production. These operations never automatically debit a customer.

## Module layout and wiring

Use the standard `nwidart/laravel-modules` layout:

```text
project-root/
|-- app/
|-- Modules/
|   |-- Core/
|   `-- TenantCore/
|-- resources/
|-- routes/
|-- composer.json
`-- package.json
```

Module PHP lives in `Modules/{Module}/app/` with `Modules\{Module}\...` namespaces. Routes, views, migrations, and seeders use lowercase `routes/`, `resources/views/`, and `database/` paths. Root Composer configuration merges `Modules/*/composer.json`; keep module metadata, namespaces, providers, and `modules_statuses.json` consistent. Register routes, views, migrations, policies, and bindings once through their owner.

Use native `php artisan module:make ModuleName` for an explicitly approved module, replacing `ModuleName` with its approved name; the name argument is required. Do not hand-scaffold a module. Core and TenantCore already exist, so do not rerun creation or use `--force` on them; another module requires an explicit scope change. `php artisan module:list` inspects registration.

The configured generator supplies a lean skeleton: `module.json`, Composer namespace mapping to `app/`, a functional provider using native `loadRoutesFrom()`, and lowercase `routes/web.php`. Its empty route group declares `web`/`auth` middleware, the kebab-case module URL prefix, and the lowercase module route-name prefix ending in a dot. Add controller groups inside it when actual routes share a controller. It does not generate fake controllers/views/models/resources, a separate frontend, APIs, tests, or factories. Register views and schema when their actual files are added, and reuse the Core layout. This skeleton does not implement a complete authorized CRUD; resource work still follows the shared recipe below.

TenantCore's User lives at `Modules/TenantCore/app/Models/User.php`, with namespace `Modules\TenantCore\Models`. The tenant authentication provider selects it; the central provider selects Core's Admin. Both use guard `web`. Do not duplicate either identity model under root `app/` or another module.

Reuse Core's `BaseController`, `BaseRequest`, model query traits, and value helpers across the actual tenant/account/role consumers. Keep the Hrm-style controller/model flow without a repository layer. Add further classes only for real behavior, reuse, or an ownership boundary; do not create a separate `Base/` directory or empty placeholders.

### Required placement rules

The agreed architecture determines code placement. Do not create a folder or layer and then expand these documents to justify it. Ordinary CRUD uses the five-part recipe below; Core's existing SaaS infrastructure is not a folder template to copy into every module.

| Path relative to the owning module | Allowed responsibility |
| --- | --- |
| `app/Http/Controllers/`, `app/Http/Requests/`, `app/Models/` | The existing thin controller, validated request, and model pattern |
| `resources/views/{resources}/`, `routes/web.php` | Index/shared form and grouped native resource routes |
| `app/Policies/`, `app/Http/Middleware/` | Native authorization and request context; retain server-side record/tenant checks |
| `app/Helpers/` | Shared utility logic; `helper.php` owns the existing global functions, `Money.php` owns exact amount conversion |
| `app/Services/` | Substantial workflows; no service generated for ordinary CRUD |
| `app/Contracts/` | Real boundaries only: the existing tenant-account and payment-gateway interfaces |
| `app/Gateways/` | Provider adapters and their typed results, including `GatewayCheckout` and `GatewayVerification` |
| `app/TenantFinder/`, `app/Tasks/` | The existing Spatie domain resolver and tenant-switch task |
| `app/Providers/`, `app/Console/Commands/`, `app/Enums/` | Registration, explicit operational commands, and the existing permission definitions |
| `app/Routing/`, `app/Validation/`, `app/View/Composers/`, `app/Models/Concerns/` | The existing resource registrar, tenant rules, layout preparation, and shared model behavior |
| `database/migrations/` | Schema changes in the already defined central/shared/tenant paths |

Keep these roles distinct without adding forwarding classes. Do not introduce `ValueObjects/`, `DTOs/`, `Data/`, `Repositories/`, `Actions/`, or `Support/` as additional architectural layers under the current scope. Gateway result DTOs stay beside their adapters; a static conversion utility is not a value object. Another architectural folder/layer requires a specific need, ownership, and an explicit change to the agreed structure before implementation. Changes within the established paths follow the current recipe.

## Responsibilities and request flow

| Layer | Responsibility |
| --- | --- |
| Routes and middleware | Web/session behavior, authentication, and consistent access requirements |
| `BaseController` and thin leaves | Shared HTTP actions, prepared view data, redirects; leaf model and resource declarations |
| `BaseRequest`, resource requests, `ListRequest` | Shared target/operation authorization and validation; resource-specific write rules and declared list-query rules |
| Service, where needed | Owning module's business rules, orchestration, and transactions |
| Model with `HasCrudContract` | Owned data/casts/relationships, write allowlist, intrinsic facts, safe fields, and query declarations |
| Model's `HandlesModelQueries` trait | Shared execution of constraints, search, filters, sorting, includes, selection, eager loading, and numbered pagination |
| Blade and shared partials | Direct escaped HTML fields using prepared values and common heading/table/action markup |

Keep business rules and queries outside Blade. Ordinary CRUD uses the shared controller and model directly, without a service. Reserve services for substantial multi-record, access, provisioning, subscription, invoice, or payment workflows; they receive explicit values/context and return results rather than HTTP responses. Avoid forwarding layers.

Keep models limited to fields, casts, relations, safe query/display metadata, and intrinsic facts. Aim for roughly 40 lines per resource model where practical. Remove unused or duplicate responsibilities; do not compress code or create traits/services just to meet a line count. Controllers use the protected `formData(Account, Model)` hook for form choices/defaults; models do not prepare page/form data.

HTTP field validation and normalization belong only to Requests. Services consume validated payloads; CLI commands use native prompt validation. Do not duplicate `Validator` calls or field-format rules in models/services. Transactional state/accounting invariants, authorization, and authoritative gateway verification remain necessary workflow behavior. Existing substantial workflows may supply prepared values through the controller's form hook; this is not a reason to add a CRUD service.

Use descriptive singular/plural names, small focused methods, and explicit inputs/results. A reader should be able to follow validation, authorization, query construction, persistence, and response without discovering a hidden dispatch convention. Prefer clear branches and named operations over clever one-liners, application-level reflection/name guessing, or generic forwarding pipelines. Comments explain non-obvious constraints rather than narrating obvious code.

Prepare the display/query data needed by a page outside the template. Use explicit safe fields for forms, listings, and exports; do not expose secrets through model serialization or old input. Allowlist client-controlled search, sort, filter, and related-record selections before constructing queries.

The structure follows the Hrm reference's thin-resource/shared-parent flow, adapted to Blade/session authentication: resource controller → `BaseController` → model, and resource request → `BaseRequest`. Leaves declare model/resource metadata and provide typed `index()` → `listData()` and `store()` → `saveData()` delegations so Laravel resolves the native Form Requests. The base shares create/edit/destroy, form preparation, and redirects. Core's concrete `AccountController` serves both `/core/admins` and `/users`, resolving the model through the trusted authentication provider; there are no AdminController/UserController wrappers. Define route-prefix derivation once in `BaseController::routePrefix()`: the trusted matched name `core.accounts.edit` yields `core.accounts`. Leaves do not declare duplicate prefix properties or inherited action bodies, and updates need no wrapper.

`BaseRequest` shares action authorization, trusted bound-record/update resolution, and uniqueness support. Resource requests declare protected `$rules`, overriding `rules()` only for actual differences; `payload()` maps validated input when needed. `ListRequest` consumes the selected model's declarative query rules. Ordinary `BaseController` persistence calls `writeTransaction(actor, operation)`, locks/reloads the target, authorizes with the callback actor, fills the safe payload, and saves. The shared model trait defaults to its own database transaction. Role explicitly selects `AccessManager::transaction()` to lock the owner role and refresh the actor before target locking; its CRUD needs no RoleService. Plan/Role policies refuse protected or referenced deletions, with authorization rechecked against the fresh locked target. List counts avoid per-row relation queries. Role retains permission-cache invalidation after commit. Plan and Role controllers prepare their form values through `formData()`; models do not supply form data.

Write rules describe creation once. On ordinary partial updates, the base changes top-level `required`/`present` rules to `sometimes`; supplied child values still validate normally. Requests for complete submissions set `partialUpdates=false` to retain those requirements. `unique()` ignores only the trusted bound record, and `onCreate()` expresses create-only fields or optional edit passwords. Omitted partial-update values preserve stored data; checkbox markers distinguish keeping a selection from explicitly clearing it.

Models declare their query contract once through `HasCrudContract::queryContract()`:

- `$queryConstraints` defines server-owned read constraints, such as Role's `web` guard. Native model connections preserve the selected database boundary.
- `$searchable`, `$filterable`, `$sortable`, and `$defaultSort` allowlist query behavior. Tenant's status filter uses the model's single `STATUSES` map.
- `$exposed` is the explicit safe column list; an empty or invalid declaration fails. `$listColumns` defaults to that list and may declare an actual subset. Write fillable/validation fields remain separate.
- `$listWith`, optional `$formWith`, `$allowedIncludes`, `$exposedRelations`, and `$exposedCounts` declare related query/display data. Form eager loading defaults to the list declarations. Avoid model-wide `$with`.

`HasCrudContract` reuses `HandlesModelQueries` on the model itself. `queryRules()` supplies query metadata to `ListRequest`; `paginateRecords()` and `prepareForForm()` execute queries and load form relations without a repository. Validation runs in the Request. Queries apply model-owned constraints, safe fields and hydration/order keys, eager loads/counts, grouped escaped prefix search, and declared filters/sorts/includes. Native numbered pagination defaults to 10 rows and allows 5/10/15/20/30/50/100 plus `all` (displayed as All). All returns every filtered, authorized record on page 1 through the same query; changing size also resets page 1. Validate page/size, retain filters/sorting/size in links, and order deterministically with a primary-key tie-breaker. Unknown identifiers never become SQL columns or relation names.

Intrinsic rules stay on their owning models: `Tenant::STATUSES`, `isReady()`, and `statusOptions()` describe lifecycle choices; `Role::OWNER_NAME`, `isReserved()`, and `Account::hasOwnerRole()` describe the reserved owner role. Policies and services consume these facts instead of repeating their literals.

## Shared forms and write actions

Use this same recipe for every CRUD resource. Paths are relative to its owning `Modules/{Module}/` directory; `{Resource}` is the singular class name and `{resources}` the plural route/view name:

| File or registration | Owns |
| --- | --- |
| `app/Models/{Resource}.php` | Owned fields, casts, relationships, and record/query rules |
| `app/Http/Requests/{Resource}Request.php` | Resource input rules on the shared `BaseRequest` contract |
| `app/Http/Controllers/{Resource}Controller.php` | Resource declarations; typed `index()`/`store()` delegate to `listData()`/`saveData()` |
| `resources/views/{resources}/index.blade.php` | The prepared, paginated list and its actions |
| `resources/views/{resources}/form.blade.php` | The shared create/edit form |
| `database/migrations/...php` | Required schema and database invariants |
| `routes/web.php` and applicable permission/policy registration | Explicit action names and server-side access rules |

These files implement the requested Controller, Model, Request, View, and Route pattern. The View part has an index and a shared create/edit form; schema and policy registration remain required wiring. Add `show.blade.php`/`show()` only for a real detail consumer. Settings, plans, and role names use direct shared CRUD; account privilege changes and larger lifecycle/billing workflows retain their substantial orchestration. Reuse the model query trait, and do not generate forwarding layers or example resources.

Each resource uses one `form.blade.php` for create and edit. Distinct authorized GET routes render that same view. Controllers prepare the mode, complete/default values, action URL, and effective HTTP method.

Each existing module provider registers its own `routes/web.php` through native `loadRoutesFrom(module_path(...))`. Module route files group `web`/`auth` middleware, a dotted module name prefix, and central/tenant restrictions; root `routes/web.php` owns home and the shared login/logout/password-recovery routes under `auth`, without manually including module files. Core declares `prefix('core')` once, giving `/core/admins`, `/core/roles`, `/core/permissions`, and `/core/tenants`; TenantCore retains `/users`. No additional RouteServiceProvider is needed. Register CRUDs with `Route::resource()`, using `except()` for unsupported actions, `parameters()` for bound names, and `names()` when the URL label differs from the resource name. Core's small ResourceRegistrar extension maps only update's action to `store()`; native names, verbs, parameters, and other options remain intact.

Keep the same route shape and dotted module/resource/action names, with a trailing dot on every name prefix. The table is a pattern, not a declaration of implemented routes; both write routes dispatch to `store()`:

| HTTP route | Controller action | Permission/policy responsibility |
| --- | --- | --- |
| GET `/{resources}`, named `*.index` | `index()` | Visible list/read access |
| GET `/{resources}/create`, named `*.create` | `create()` | The create/store grant; render the shared form |
| POST `/{resources}`, named `*.store` | `store()` | Validate and authorize creation |
| GET `/{resources}/{resource}/edit`, named `*.edit` | `edit()` | The update grant and scoped record; render the shared form |
| PUT/PATCH `/{resources}/{resource}`, named `*.update` | `store()` | Validate and authorize the scoped record's update |
| DELETE `/{resources}/{resource}`, named `*.destroy` | `destroy()` | Scoped deletion authorization and domain refusal rules |
| GET `/{resources}/{resource}`, named `*.show`, when needed | `show()` | Scoped detail/read access |

Create forms submit POST. Edit forms submit POST with `@method('PUT')` or `@method('PATCH')`. Native resource registration supplies path order and write routes; the shared registrar maps updates to `store()`. Register explicit model bindings once in the owning provider when the shared method signature has no model parameter, so both update and edit bind correctly with cached routes. Keep create/edit access tied to the corresponding write authority and give independent workflow actions explicit permissions.

Determine the operation from the matched named route, effective HTTP method, and authorized record context. A body `id` or mode flag must not select the operation or target. A missing/invisible update target fails; it must not become a create. The Form Request distinguishes the operations through route/context, since both call `store()`. Keep operation-specific business rules in the owning service where orchestration is needed.

Render Blade for reads and redirect after successful writes with flash messages. Use CSRF protection, escaped output, native validation errors, and permitted old input. GET requests must not change state. Load the complete values and children edited by a form; omitted fields must not silently clear data the form does not manage.

## Authentication and authorization

Use Laravel session authentication on the `web` guard and `spatie/laravel-permission` with that same guard. Core's `SessionController` handles login/logout HTTP responses; `LoginRequest` validates and normalizes credentials and attempts authentication through the configured guard. It does not reference a concrete TenantCore model. The `role`, `permission`, and `role_or_permission` middleware aliases are wired. Enforce policies/gates server-side for every action; Blade checks provide presentation feedback in addition to those checks.

`PasswordController` and `PasswordRequest` use Laravel's native password brokers and one forgot/reset form under the root `auth` group. Broker `admins` uses the central provider/token table; `users` uses the resolved tenant provider/token table. Host context is resolved before broker selection, submissions are rate-limited, and normal reset-link responses use generic account-existence wording. Applicable text fields require at least 5 characters; password requests/forms share `auth.password_min_length`, currently 5. Mail defaults to `log`; production reset delivery requires configured SMTP or another working mail transport.

Role CRUD edits names only; saving a name never changes grants. The separate `/core/permissions` workflow selects a visible role and displays grouped permissions from the current database. `PermissionController` reuses `BaseController`: its typed index passes service-prepared `pageData()` into `listData()`, its store delegates to `saveData()`, and its redirect hook retains the selected role. Its requests extend `BaseRequest`; `RolePermissionRequest` sets `partialUpdates=false` so `permissions_present=1` and the complete permissions array remain required on PUT. An intentional empty array clears grants; filtering hides options without removing or disabling their submitted checkbox values. `permissions.view`, `permissions.manage`, and `roles.assign` remain separate abilities. `RolePermissionService` scopes choices, reauthorizes a fresh actor under the access lock, performs the grant mutation, and invalidates permission cache after commit. Reserved owner grants stay protected. `config/permissions.php` supplies display labels, never records or authority.

Scope lists and lookups to visible records. Permission to reach a route does not grant visibility of every record. Validate related ids against the same visibility and ownership rules, and assign ownership through trusted logic.

Laravel's session guard regenerates the session and CSRF token on successful login; do not duplicate that operation in the controller. Regenerate after privilege changes; invalidate the session on logout and regenerate the CSRF token. Use safe authentication failures and password hashing, and exclude secrets from flashed input, logs, and errors.

Apply rate limits at routes through middleware aliases. Core registers the named `login` limiter once and the login POST uses Laravel's built-in `throttle:login` alias: five submissions per minute per trusted scope/normalized email/IP, including successful and validation-failed submissions. Excess requests receive HTTP 429. Keep this logic out of Form Requests/controllers; create custom middleware only when native middleware cannot express the required behavior.

## Database integrity and performance

Use foreign keys, uniqueness constraints, indexes justified by actual queries, and transactions for related writes. Use row locks or atomic updates where concurrent actions could violate an invariant. Use explicit mass-assignment rules and persist validated/mapped fields; ownership, calculated values, and workflow status come from trusted logic.

Use parameterized queries. Billing uses integer minor units with two-decimal input/display conversion through `Money`; reject excess precision instead of silently rounding. Eager-load relationships consumed by pages and paginate unbounded lists. Batch or chunk large operations as appropriate; avoid global eager-loading or table splitting without a concrete read pattern that justifies it.

Every list follows the same query contract:

- Start with the authorized visibility/ownership scope, then apply grouped search and allowlisted filter/sort identifiers. User input never becomes an unchecked SQL column or expression.
- Filter, order, count, and paginate in the database. Do not call `all()`/unbounded `get()` and then filter or paginate in PHP. Select needed display columns plus primary/foreign keys required to hydrate relationships; eager-load relationships/counts used by the page.
- Use the shared numbered-pagination contract: default 10 rows, allowlisted sizes 5/10/15/20/30/50/100 or All, validated pages, and deterministic ordering with a unique tie-breaker. All intentionally returns the full filtered, authorized result on page 1. Retain validated filters and size in links; measure deep-page, total-count, and full-result costs on representative data.
- Keep query counts from growing per displayed row. Check small and full pages, and inspect timings/query plans at representative table sizes and common filters. Choose indexes around those predicates/orderings, including composite order and write costs, rather than indexing every column.
- Cache only when a real repeated read benefits and each writer has a clear invalidation responsibility. Bulk work uses bounded chunks, `lazyById()`/`chunkById()` where changing rows require stable traversal, or a job when duration/retry requirements justify it; do not queue ordinary small requests automatically.

Numeric page sizes bound the response; All intentionally loads the full authorized result. Record representative query counts/timings and address observed bottlenecks before promising an improvement.

Keep the boot path lean: providers register required behavior without database queries, network I/O, or custom directory scans beyond package discovery. Composer's optimized autoloader is enabled. Use production config/event/route/view caches only after environment setup and keep ordinary development uncached. Measure comparable repeated requests through the HTTP kernel before reporting a boot-time or response-time improvement.

Write services use the connection that owns the affected central or tenant data. Database, filesystem, queue, and external-service operations do not share one atomic transaction; tenant provisioning uses recoverable lifecycle operations across these boundaries.

Keep failures observable and user-safe. Distinguish validation/domain refusals from unexpected infrastructure failures; do not display SQL, class names, credentials, or stack traces in production error pages. Preserve intentional deletion and retention behavior through explicit requirements rather than importing another project's lifecycle rules.

Calendar dates round-trip as `YYYY-MM-DD`; datetimes use a consistent timezone convention. Prepare formatting through shared helpers/components when reuse warrants them, and keep PHP/database date boundaries consistent with the application timezone.

## Blade, Bootstrap, and assets

Use Bootstrap 5, Popper, and locally bundled Font Awesome Free core/solid styles through the single root Vite pipeline in `resources/css/app.css` and `resources/js/app.js`. Core owns the module-namespaced dashboard/layout views; share common HTML through native Blade partials. Keep icon imports limited to the styles actually used and avoid a separate asset build or CDN dependency for each module. Progressive JavaScript can support tabs, confirmations, and form behavior without a separate frontend application.

The shared admin layout uses a dark 260px sidebar, an 80px collapsed state, a white topbar/account menu, full-width content, and responsive Bootstrap offcanvas navigation. Core's `LayoutComposer`, registered only for `core::layouts.app`, supplies named menu visibility/URL/active values and scalar site/account/workspace data in `$layout`; use fixed menu HTML with Blade conditionals, keep models and authorization outside Blade, and use no menu loops in either the composer or layout. Keep resource screens consistent with this shell instead of adding separate headers or page themes. The dashboard prepares authorized counts and at most five recent records in `DashboardController`; display actual data rather than sample revenue, activity, or trends. Sidebar, fullscreen, and password visibility controls live in the shared jQuery entry point. Login uses the same design tokens and shared validation feedback.

Keep all application styles in `resources/css/app.css`; define colors once as CSS custom properties and reuse them through `var()`. Do not add per-page stylesheets or inline styles. jQuery is bundled through `resources/js/app.js` and available as `$`/`jQuery` for interactive behavior.

All CRUD screens use the same Core layout. Write labels, inputs, selects, checkbox loops, field errors, `@csrf`, and `@method` directly in each resource's shared create/edit form. Do not introduce functions or field components that generate HTML. Repeated headings, actions, feedback, tags, empty states, and pagination use ordinary `core::partials.*` Blade includes. Business decisions and queries remain outside Blade.

`app/Helpers/helper.php` contains logic only. `getValue($key, $objData = null, $default = null)` prefers allowed old input over the model/default and preserves zero/empty values while rejecting malformed field shapes. `getSelected($optionValue, $key, $objData = null, $default = null)` returns `selected` or an empty string; `getChecked(...)` reuses it for `checked` and supports prepared checkbox arrays. Use escaped Blade expressions in HTML attributes; password inputs never receive a value or old input. Keep `normalizeEmail()` for shared normalization. Helpers do not generate HTML elements or return view objects.

Validation classes come from the short camelCase Core helper `inputClass('email')`, used as `class="{{ inputClass('email') }}"`. It appends `is-invalid` when the session error bag contains that field. Its optional second argument supplies a base class such as `form-select` or `form-check-input`. Register `app/Helpers/helper.php` once through Core's `module.json` files list; do not repeat `@class` error expressions in each view.

Use `{{ inputAttrs('name') }}` for conditional `aria-invalid` and `aria-describedby` attributes. The helper returns Laravel's native escaped attribute bag, referring to `name-error` only when the field has an error. Optional input/help IDs support cases such as `inputAttrs('slug', 'tenant-slug', 'tenant-slug-help')`; help text remains described when the field is valid. Keep the actual label/input and `@error` message markup directly in Blade.

Views are registered under explicit module namespaces. `config/view.php` intentionally has an empty `paths` list; use names such as `core::home` rather than relying on a root view-directory scan.

Do not scaffold Vue, Inertia, Tailwind, a SPA, application APIs, API route files/Resources, JWT/Sanctum/token authentication, or root/module `tests/` directories. Domain workflows and schema require explicit requirements beyond the boilerplate.

Uploads require authorization, content/MIME and size validation, safe storage, and protected downloads where appropriate. Validate external responses and keep integration secrets out of presentation and logs when an integration is actually introduced.

## AI tools and verification

The native project skills include [Laravel fullstack development](./.agents/skills/laravel-fullstack-development/SKILL.md), [Laravel best practices](./.agents/skills/laravel-best-practices/SKILL.md), and [Infer conventions](./.agents/skills/infer-conventions/SKILL.md). Run Infer conventions only when explicitly requested. Keep `.agents/skills/` and the portable [.codex/config.toml](./.codex/config.toml) Boost MCP configuration with the project. Generic Vue/API/JWT and mandatory hierarchy examples do not override this contract.

[boost.json](./boost.json) and [config/boost.php](./config/boost.php) disable generated guidelines and exclude testing/Tailwind skills so refreshes preserve the project decisions. Do not depend on machine-specific plugin paths or global installation for project-local skill discovery.

Use the commands configured in the current manifests and [README.md](./README.md):

- `composer lint` checks PHP formatting; `composer format` applies it.
- `npm run build` builds production assets; `npm run dev` starts Vite.
- `composer dev` starts development processes. `php artisan serve` serves the Laravel application when a standalone local server is needed.
- `composer optimize` prepares production config/event/route/view caches after environment configuration. `composer optimize-clear` clears generated caches with `optimize:clear --except=cache`, preserving application-cache entries; use it before normal development or cached-configuration changes.

Use focused manual browser/database checks for implemented create/read/update/delete flows, validation, denied access, sessions, applicable business rules, module wiring, and important database invariants. Exercise the shared form and single `store()` action in both modes, including missing update targets. Do not add automated test scaffolding under the current scope.

Inspect actual stored/runtime results and report checks that could not run. Lint/build success does not establish runtime behavior or a successful live gateway payment. Update this document and AGENTS.md when an architectural decision changes.
