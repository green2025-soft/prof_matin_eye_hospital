# Application Architecture

## Contract and current state

[AGENTS.md](./AGENTS.md) and this document define the contract for a reusable Laravel modular boilerplate. Build the foundation before project-specific work. The application uses Blade, Bootstrap 5, HTML forms, and session authentication on the `web` guard. Project instructions take precedence over conflicting examples in reusable skills.

Laravel 13 is installed at the project root on PHP `^8.4.1`, with Vite, Bootstrap 5, Popper, Font Awesome Free 7.3.1, and an admin dashboard. `nwidart/laravel-modules` 13.0.0, `spatie/laravel-permission` 8.3.2, and `spatie/laravel-multitenancy` 4.2.2 are installed with module Composer merge integration. Exactly two modules, Core and TenantCore, are registered with functional providers. Laravel Boost, Pint, Pail, Collision, and Tinker support development.

Core's Admin and TenantCore's User inherit shared Account behavior and Spatie `HasRoles` on guard `web`. Admin pins the central connection; User uses Spatie's tenant connection trait. Shared account/session/permission migrations serve both database types. Login/logout, a protected home page, tenant/domain resolution, separate tenant databases, explicit provisioning/adoption, migration commands, tenant/account/role management forms, and a separate permission-assignment workflow are implemented. Preserve existing application keys, credentials, and database data. Project-specific business workflows remain unspecified.

The starter uses file sessions/cache and a synchronous queue. The health route requires no database; tenant host resolution needs the installed central registry. `APP_TIMEZONE=Asia/Dhaka` controls application time. Installation/provisioning/deployment commands explicitly create or migrate the intended database; requests and provider boot never do this work.

## Modules and ownership

Keep exactly two modules at the project root, alongside Laravel's `app/`:

| Module | Owns | Boundary |
| --- | --- | --- |
| `Modules/Core` | Platform administrators, tenant/domain registry, shared account schema, native tenancy integration, provisioning/migration operations, shared auth/views/helpers | Independent of concrete TenantCore classes |
| `Modules/TenantCore` | Tenant User model, tenant account operations, and tenant-only schema | Implements Core's TenantAccounts boundary |

Keep business structure unspecified until project requirements exist; do not invent additional feature modules or model hierarchies. Keep contracts with the module that owns the capability; Core receives only contracts that are genuinely shared infrastructure. Use explicit public operations across boundaries, avoid circular dependencies, and do not write directly to another module's tables.

The selected scope is full SaaS. Core owns platform administrator identities and the tenant/domain registry in a central database. Each tenant has its own database; TenantCore owns its users and permission data. The existing `admin@example.com` belongs to the first tenant; the platform administrator is a separate account. Credentials belong in operator-managed secrets, not source or seeders.

Spatie's automatic finder is disabled. `ResolveApplication` resolves trusted central hosts or active registered tenant domains after boot and invokes native tenant switching; unknown/inactive tenant domains return 404. The native database task runs before the application settings task, which scopes cache/permissions/storage and selects the default connection. Resolve before cookies/sessions; verify session scope immediately after StartSession and before authentication. Keep the context until middleware termination so exception rendering, streamed responses and response collectors see the correct identity; then restore central state and reset web managers.

The public guard remains `web`. A small factory uses Laravel's unchanged SessionGuard with a context-qualified constructor name and the resolved Admin/User provider. Session cookies are host-only and scope-specific; session context and Spatie's tenant-session checks reject cross-context reuse. Remember-cookie names and authentication session keys also include the scope. Resolve the HTTP actor and authorize before entering `Tenant::execute()`; pass explicit values into that callback and never run the HTTP actor's gates or reuse another tenant's loaded models inside it. Native `Tenant::execute()` restores previous context even if its callback throws.

Native tenant-aware queues retain tenant identity; a tenant job must also enforce lifecycle eligibility. Central jobs explicitly implement Spatie `NotTenantAware`: the installed package rejects default-aware jobs without a tenant ID. Database session storage and queue transport/batches/failures are pinned centrally, while cache and uploaded-file locations use the active context. File sessions/cache and sync queues remain the configured development defaults.

## Installation and schema changes

`platform:install` explicitly creates/claims the central database, applies central/shared migrations and creates the platform owner. `tenant:create` registers a tenant, allocates a server-generated immutable database name/token, claims that database, applies the shared/tenant schema and creates its owner. `tenant:adopt` explicitly attaches an existing database and preserves its owner/password. A failed setup retains its registration; `tenant:retry SLUG` resumes it with the original ownership token (`--adopt` for adoption). Active/suspended tenants cannot be reprovisioned. Core invokes tenant account operations through the owner-defined contract rather than writing tenant users directly.

Database ownership uses a metadata marker bound to the application key and central/tenant token. Existing foreign markers are never overwritten. An unmarked populated database requires explicit adoption. Keep the application key stable; key rotation requires an explicit marker rotation/recovery procedure. An interrupted claim with an empty metadata table requires operator repair rather than automatic takeover. A MySQL advisory lock serializes schema work for each database. Database deletion is not provided.

Migration paths are declared once in `config/multitenancy.php`: root infrastructure plus Core `database/migrations/shared` and `central` for the platform; shared schema plus TenantCore `database/migrations` for tenants. The optional tenant-only directory is included when present. Existing migration filenames remain unchanged so adoption preserves applied history.

Add each table/column change as a new native Artisan migration in the owning path; never edit a migration already applied to a real database. `php artisan tenants:migrate` lazily visits every registered tenant, including suspended/failed records, verifies ownership and applies pending migrations on its own connection. Each database has its own `migrations` table. New tenants use those same paths and receive the latest schema. Use `--tenant=ID_OR_SLUG` for selected tenants, `--pretend` for a preview and `--force` in production. Digit-only selectors mean IDs. A failure is reported with its tenant ID, other tenants continue and the command exits unsuccessfully; fix and rerun to apply the remaining migrations. There is no transaction spanning separate databases and no automatic rollback. Use forward fixes and staged nullable/backfill/constraint migrations for populated tables. Never run `migrate:fresh` against real tenant databases.

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

The configured generator supplies a lean skeleton: `module.json`, Composer namespace mapping to `app/`, a functional provider, and lowercase `routes/web.php`. Its empty route group declares `web` middleware, the kebab-case module URL prefix, and the lowercase module route-name prefix ending in a dot. Add controller groups inside it when actual routes share a controller. It does not generate fake controllers/views/models/resources, a separate frontend, APIs, tests, or factories. Register views and schema when their actual files are added, and reuse the Core layout. This skeleton does not implement a complete authorized CRUD; resource work still follows the shared recipe below.

TenantCore's User lives at `Modules/TenantCore/app/Models/User.php`, with namespace `Modules\TenantCore\Models`. The tenant authentication provider selects it; the central provider selects Core's Admin. Both use guard `web`. Do not duplicate either identity model under root `app/` or another module.

Reuse Core's `BaseController`, `BaseRequest`, model query traits, and value helpers across the actual tenant/account/role consumers. Keep the Hrm-style controller/model flow without a repository layer. Add further classes only for real behavior, reuse, or an ownership boundary; do not create a separate `Base/` directory or empty placeholders.

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

Keep business rules and queries outside Blade. Services receive explicit values and trusted context, return useful results, and do not return HTTP responses. Avoid helpers or abstractions that merely forward a call without adding a boundary or behavior.

Use descriptive singular/plural names, small focused methods, and explicit inputs/results. A reader should be able to follow validation, authorization, query construction, persistence, and response without discovering a hidden dispatch convention. Prefer clear branches and named operations over clever one-liners, application-level reflection/name guessing, or generic forwarding pipelines. Comments explain non-obvious constraints rather than narrating obvious code.

Prepare the display/query data needed by a page outside the template. Use explicit safe fields for forms, listings, and exports; do not expose secrets through model serialization or old input. Allowlist client-controlled search, sort, filter, and related-record selections before constructing queries.

The structure follows the Hrm reference's thin-resource/shared-parent flow, adapted to this application's Blade/session contract: resource controller → `BaseController` → model, and resource request → `BaseRequest`. `BaseController` declares shared `index()`, `create()`, `edit()`, `destroy()`, form preparation, redirects, and `saveData()`. Resource leaves set protected `$model`, `$title`, `$service`, `$views`, `$parameter`, plus public `$routePrefix`. Their typed `store(ResourceRequest $request)` delegates to `saveData()` so Laravel resolves the native Form Request. Admin and User reuse the shared AccountController and account views. Do not repeat inherited action bodies or add an `update()` wrapper.

`BaseRequest` shares action authorization, trusted bound-record/update resolution, and uniqueness support. Resource requests declare protected `$rules`, overriding `rules()` only when the resource requires additional input rules; Laravel still performs normal Form Request validation. `ListRequest` obtains common query rules directly from the selected model. Services own business mutations through explicit `save(actor, record, values)`, `delete(actor, record)` where supported, and `formData(actor, record)` operations. They return models or prepared data rather than responses.

Write rules describe creation once. On update, the base changes top-level `required`/`present` rules to `sometimes`; supplied child values still validate normally. `unique()` ignores only the trusted bound record, and `onCreate()` expresses create-only fields or optional edit passwords. Omitted values preserve stored data; checkbox markers distinguish keeping a selection from explicitly clearing it.

Models declare their query contract once through `HasCrudContract::queryContract()`:

- `$queryConstraints` defines server-owned read constraints, such as Role's `web` guard. Native model connections preserve the selected database boundary.
- `$searchable`, `$filterable`, `$sortable`, and `$defaultSort` allowlist query behavior. Tenant's status filter uses the model's single `STATUSES` map.
- `$exposed` is the explicit safe column list; an empty or invalid declaration fails. `$listColumns` defaults to that list and may declare an actual subset. Write fillable/validation fields remain separate.
- `$listWith`, optional `$formWith`, `$allowedIncludes`, `$exposedRelations`, and `$exposedCounts` declare related query/display data. Form eager loading defaults to the list declarations. Avoid model-wide `$with`.

`HasCrudContract` reuses the shared `HandlesModelQueries` trait on the model itself. `queryRules()`, `paginateRecords()`, and `prepareForForm()` supply common list validation, query execution, and form loading without an intermediate repository object. The shared query implementation applies model-owned constraints, explicit fields and hydration/order keys, eager loads/counts, grouped escaped prefix search, and declared filters/sorts/includes. Native numbered pagination defaults to 10 rows and permits only 5, 10, 15, 20, 30, 50, or 100. Validate the page and page size, retain filters/sorting/page size in links, and order deterministically with the primary key as a tie-breaker. Unknown identifiers never become SQL columns or relation names.

Intrinsic rules stay on their owning models: `Tenant::STATUSES`, `isReady()`, and `statusOptions()` describe lifecycle choices; `Role::OWNER_NAME`, `isReserved()`, and `Account::hasOwnerRole()` describe the reserved owner role. Policies and services consume these facts instead of repeating their literals.

## Shared forms and write actions

Use this same recipe for every CRUD resource. Paths are relative to its owning `Modules/{Module}/` directory; `{Resource}` is the singular class name and `{resources}` the plural route/view name:

| File or registration | Owns |
| --- | --- |
| `app/Models/{Resource}.php` | Owned fields, casts, relationships, and record/query rules |
| `app/Http/Requests/{Resource}Request.php` | Resource input rules on the shared `BaseRequest` contract |
| `app/Http/Controllers/{Resource}Controller.php` | Resource declarations and typed `store()` delegation to `BaseController::saveData()` |
| `resources/views/{resources}/index.blade.php` | The prepared, paginated list and its actions |
| `resources/views/{resources}/form.blade.php` | The shared create/edit form |
| `database/migrations/...php` | Required schema and database invariants |
| `routes/web.php` and applicable permission/policy registration | Explicit action names and server-side access rules |

Add `show.blade.php`/`show()` only when a consumer needs a detail page. A write service is justified by actual business rules/orchestration; current resources use it for access or tenant lifecycle invariants. Reuse the model query trait instead of copying queries into controllers or inserting repositories. Do not generate layers, example resources, or empty directories merely to complete a checklist.

Each resource uses one `form.blade.php` for create and edit. Distinct authorized GET routes render that same view. Controllers prepare the mode, complete/default values, action URL, and effective HTTP method.

Register CRUDs with `Route::resource()` beneath the root's one common authenticated module-route group. Home/login/logout retain their explicit contracts; central-only and tenant-only restrictions live within the shared protected group. Module route files declare only their module names and additional scope restrictions. Use `except()` for unsupported actions, `parameters()` for explicit bound names and `names()` when the URL label differs from the route prefix. Core binds a small ResourceRegistrar extension that changes only update's controller action to `store()`; standard route names, verbs, parameters and other resource options remain intact. This avoids per-controller update wrappers and repeated manual write routes.

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

Role CRUD edits role names only; saving a name never changes grants. The separate `/permissions` workflow selects a visible role and displays grouped permissions from the current database. `permissions.view` controls the page, `permissions.manage` controls grant changes, and `roles.assign` independently controls account role assignment. Workflow-specific native Form Requests validate the role selection and explicit checkbox submission; an empty selection clears grants only with its required submission marker. `RolePermissionService` scopes role choices and permissions to the actor's capabilities, reauthorizes a fresh actor under the shared access lock, and invalidates the permission cache after commit. Reserved owner-role grants remain protected. `config/permissions.php` supplies group/area/action labels, not permission records or authority.

Scope lists and lookups to visible records. Permission to reach a route does not grant visibility of every record. Validate related ids against the same visibility and ownership rules, and assign ownership through trusted logic.

Laravel's session guard regenerates the session and CSRF token on successful login; do not duplicate that operation in the controller. Regenerate after privilege changes; invalidate the session on logout and regenerate the CSRF token. Use safe authentication failures and password hashing, and exclude secrets from flashed input, logs, and errors.

Apply rate limits at routes through middleware aliases. Core registers the named `login` limiter once and the login POST uses Laravel's built-in `throttle:login` alias: five submissions per minute per normalized email/IP, including successful and validation-failed submissions. Excess requests receive HTTP 429. Keep this logic out of Form Requests/controllers; create custom middleware only when native middleware cannot express the required behavior.

## Database integrity and performance

Use foreign keys, uniqueness constraints, indexes justified by actual queries, and transactions for related writes. Use row locks or atomic updates where concurrent actions could violate an invariant. Use explicit mass-assignment rules and persist validated/mapped fields; ownership, calculated values, and workflow status come from trusted logic.

Use parameterized queries. Store monetary values in exact decimals or integer minor units and define rounding when money requirements arise. Eager-load relationships consumed by pages and paginate unbounded lists. Batch or chunk large operations as appropriate; avoid global eager-loading or table splitting without a concrete read pattern that justifies it.

Every list follows the same query contract:

- Start with the authorized visibility/ownership scope, then apply grouped search and allowlisted filter/sort identifiers. User input never becomes an unchecked SQL column or expression.
- Filter, order, count, and paginate in the database. Do not call `all()`/unbounded `get()` and then filter or paginate in PHP. Select needed display columns plus primary/foreign keys required to hydrate relationships; eager-load relationships/counts used by the page.
- Use the shared numbered-pagination contract: default 10 rows, allowlisted sizes 5/10/15/20/30/50/100, validated page numbers, and deterministic ordering with a unique tie-breaker. Retain validated filters and page size in links; measure deep-page and total-count costs on representative data.
- Keep query counts from growing per displayed row. Check small and full pages, and inspect timings/query plans at representative table sizes and common filters. Choose indexes around those predicates/orderings, including composite order and write costs, rather than indexing every column.
- Cache only when a real repeated read benefits and each writer has a clear invalidation responsibility. Bulk work uses bounded chunks, `lazyById()`/`chunkById()` where changing rows require stable traversal, or a job when duration/retry requirements justify it; do not queue ordinary small requests automatically.

Pagination bounds response size and memory; it does not prove that every growing dataset will remain fast. Record representative query counts/timings and address the observed bottleneck before promising an improvement.

Keep the boot path lean: providers register required behavior without database queries, network I/O, or custom directory scans beyond package discovery. Composer's optimized autoloader is enabled. Use production config/event/route/view caches only after environment setup and keep ordinary development uncached. Measure comparable repeated requests through the HTTP kernel before reporting a boot-time or response-time improvement.

Write services use the connection that owns the affected central or tenant data. Database, filesystem, queue, and external-service operations do not share one atomic transaction; tenant provisioning uses recoverable lifecycle operations across these boundaries.

Keep failures observable and user-safe. Distinguish validation/domain refusals from unexpected infrastructure failures; do not display SQL, class names, credentials, or stack traces in production error pages. Preserve intentional deletion and retention behavior through explicit requirements rather than importing another project's lifecycle rules.

Calendar dates round-trip as `YYYY-MM-DD`; datetimes use a consistent timezone convention. Prepare formatting through shared helpers/components when reuse warrants them, and keep PHP/database date boundaries consistent with the application timezone.

## Blade, Bootstrap, and assets

Use Bootstrap 5, Popper, and locally bundled Font Awesome Free core/solid styles through the single root Vite pipeline in `resources/css/app.css` and `resources/js/app.js`. Core owns the module-namespaced dashboard/layout views; share common HTML through native Blade partials. Keep icon imports limited to the styles actually used and avoid a separate asset build or CDN dependency for each module. Progressive JavaScript can support tabs, confirmations, and form behavior without a separate frontend application.

The shared admin layout uses a dark 260px sidebar, an 80px collapsed state, a white topbar/account menu, full-width content, and responsive Bootstrap offcanvas navigation. Keep resource screens consistent with this shell instead of adding separate headers or page themes. The dashboard prepares authorized counts and at most five recent records in `DashboardController`; display actual data rather than sample revenue, activity, or trends. Sidebar, fullscreen, and password visibility controls live in the shared jQuery entry point. Login uses the same design tokens and shared validation feedback.

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

Inspect actual stored/runtime results and report checks that could not run. Lint/build success does not establish that unimplemented authentication, tenancy, or business behavior works. Update this document and AGENTS.md when an architectural decision changes.
