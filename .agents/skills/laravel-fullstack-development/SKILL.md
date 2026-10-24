---
name: "laravel-fullstack-development"
description: "Production-ready Laravel 13, Vue 3, and REST API architecture guidance for scalable multi-tenant SaaS applications built on a 3-layer hierarchy (Root Base → Module Base → Concrete Child). Use whenever the user is building Laravel/PHP backend, Vue.js/Inertia frontend, REST APIs, multi-tenant SaaS with central/tenant planes, base controllers/models/form requests, ModelResource exposure contracts, N+1 prevention, JWT authentication, MySQL native yearly partitioning, vertical (1:1 main/details) table splits, or asks for secure/optimized/production-ready code in this stack."
---

# Laravel Full-Stack SaaS Development

Focus stack: Laravel 13, Vue.js 3, PHP, MySQL 8, REST API, MVC/HMVC, multi-tenant SaaS architecture.

Always produce clean, scalable, secure, production-ready code. Avoid unnecessary complexity — prefer practical, real-world, maintainable solutions. **Every pattern in this skill must be something a human developer can read, extend and maintain later using plain, predictable Eloquent — without AI assistance.**

Priority order when designing a feature: **(1) database shape → (2) hierarchy placement → (3) exposure contract → (4) query/security rules → (5) frontend.**

## 1. Multi-Tenant SaaS & Planes

- Each tenant has its **own separate database** (database-per-tenant). The central/landlord database holds the tenant registry, billing and cross-tenant data.
- Every model belongs to exactly one **plane**: `central` or `tenant`. The plane is pinned once in the **module base model** (`$plane`), and the `BelongsToPlane` trait resolves the connection from it — leaf models never set `$connection`.
- Tenant connections are switched by a tenant resolver/middleware — never hardcode connection config.
- Landlord and tenant migrations are kept separate; tenant migrations run per tenant.
- Never leak data across planes: every query and every transaction runs on the model's own connection.

```php
namespace App\Models\Concerns;

trait BelongsToPlane
{
    public function getConnectionName(): ?string
    {
        return config("database.planes.{$this->plane}"); // ['central' => 'central', 'tenant' => 'tenant']
    }

    /** Transaction on THIS model's plane connection — never the global default. */
    public static function transaction(Closure $callback, int $attempts = 1): mixed
    {
        return (new static)->getConnection()->transaction($callback, $attempts);
    }
}
```

## 2. Defensive 3-Layer Hierarchy — 0% Duplication

Every class sits in exactly one of three layers. Behavior is written **once**, at the highest layer where it is true.

| Layer | Purpose | Examples |
|---|---|---|
| **1. Root Base** (application core) | Global, application-wide behavior: response envelope, error boundary, trusted-payload boundary, exposure & query contract, response transformation. | `BaseModel`, `BaseApiController`, `BaseFormRequest`, `BaseFilter`, `Modules\Core\Http\Resources\ModelResource` |
| **2. Module Base** (plane pins) | Module-wide architectural decisions: `$plane`, `$tablePrefix`, `$repository` auto-binding, default `$resource`, generic `saveData()` / `updateData()` / `destroyData()` handlers. | `BaseModel → CoreModel`, `BaseApiController → ApiController` (per module) |
| **3. Leaf / Child** (execution) | Thin and declarative only: table name, contract properties, relations, validation rules, route actions that delegate to the module base. | `HomeRoute` model, `AdminController`, `StoreHomeRouteRequest` |

Rules:
- A leaf never re-implements something a base already does (no custom JSON envelopes, try/catch blocks, connection names, prefixes, transaction wrappers or handwritten Resources in leaves).
- A leaf never overrides a plane decision (`$plane`, `$connection`, `$tablePrefix`).
- If two modules need the same behavior, it moves **up** to the root; if two leaves in one module need it, it moves up to the module base.
- Frontend follows the same idea: shared Vue 3 composables and components, Pinia stores for cross-page state (Section 7).

### 2.1 Root: `BaseApiController` — envelope, messages, error boundary

```php
namespace App\Http\Controllers;

abstract class BaseApiController extends Controller
{
    /** The single response envelope used by every endpoint. */
    protected function present(mixed $data = null, ?string $message = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => $status < 400,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    /** Consistent success messages, e.g. message('created') → "Home route created." */
    protected function message(string $action): string
    {
        return __("messages.{$action}", ['resource' => $this->resourceLabel()]);
    }

    /**
     * Tight error boundary. Framework errors pass through untouched (404, 403, 500…);
     * domain rule failures (RuntimeException) become 422 rule_violation.
     * Order matters: several framework exceptions also extend RuntimeException.
     */
    protected function guard(Closure $action): JsonResponse
    {
        try {
            return $action();
        } catch (ModelNotFoundException | HttpExceptionInterface | QueryException $e) {
            throw $e;                       // 404 / HTTP errors / 500 handled by the framework
        } catch (RuntimeException $e) {
            report($e);

            return response()->json([
                'success' => false,
                'error'   => 'rule_violation',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    protected function resourceLabel(): string
    {
        return Str::headline(class_basename($this->repository ?? 'Record'));
    }
}
```

- Domain code throws `RuntimeException` (or a subclass such as `RuleViolation`) **only with user-safe messages** — the message is returned to the client.
- `QueryException` must never reach the 422 branch: it extends `RuntimeException` and would leak SQL.
- `LogicException` (contract violations, Section 2.5) is **not** a `RuntimeException`, so it is never softened into a 422 — it surfaces as a 500 the developer must fix.

### 2.2 Root: `BaseFormRequest` — untrusted input → trusted payload

```php
namespace App\Http\Requests;

abstract class BaseFormRequest extends FormRequest
{
    /** The ONLY way input reaches a model. Keys without a rule never pass. */
    public function payload(): array
    {
        return $this->mutate($this->validated());
    }

    /** Optional leaf hook for normalization (trim, casing, defaults). */
    protected function mutate(array $data): array
    {
        return $data;
    }
}
```

- **`rules()` IS the mass-assignment allow-list.** Never pass `$request->all()`, `$request->input()` or `$request->except()` to a model.
- Authorization lives in `authorize()` (Policies/Gates), scoped per tenant.

### 2.3 Root: `BaseModel` — exposure & criteria contract, published values

```php
namespace App\Models;

abstract class BaseModel extends Model
{
    use HasTablePrefix;

    protected static string $tablePrefix = '';     // pinned by the module base

    // ---- Exposure contract (read by ModelResource) ----
    protected array $exposed          = [];        // core COLUMNS only — never relation names; empty = LogicException
    protected array $exposedCounts    = [];        // relation names whose withCount() value may be published
    protected array $exposedRelations = [];        // ['relation' => ['columns' => [...], 'as' => 'public_name']]

    // ---- Criteria contract (read by BaseFilter) ----
    protected array  $searchable      = [];
    protected array  $filterable      = [];
    protected array  $sortable        = [];
    protected array  $selector        = ['id', 'label'];
    protected string $defaultSort     = '-created_at';
    protected array  $allowedIncludes = [];

    /** Mass-assignable = exposed columns minus system columns. */
    public function getFillable(): array
    {
        return array_values(array_diff($this->exposed, [
            $this->getKeyName(), static::CREATED_AT, static::UPDATED_AT,
        ]));
    }

    public function contract(string $key): array|string
    {
        return $this->{$key};
    }

    /** The single place output formats are decided, before JSON encoding. */
    public function publishedValue(string $column): mixed
    {
        $value = $this->getAttribute($column);

        return match (true) {
            $value instanceof CarbonInterface => $value->format($this->isDateOnly($column) ? 'Y-m-d' : 'Y-m-d H:i:s'),
            $value instanceof BackedEnum      => $value->value,
            default                           => $value,
        };
    }

    protected function isDateOnly(string $column): bool
    {
        return in_array($this->getCasts()[$column] ?? null, ['date', 'immutable_date'], true);
    }
}

trait HasTablePrefix
{
    /** Runs once in the constructor; hydrated models inherit the table via newInstance(). */
    public function initializeHasTablePrefix(): void
    {
        $this->setTable(static::$tablePrefix . $this->getTable());
    }
}
```

> Keep the prefix logic in the constructor initializer — **never** prefix inside an overridden `getTable()`, because `newInstance()` copies the already-prefixed table and the prefix would be applied twice.

Date-only casts publish as `YYYY-MM-DD`; datetimes as `YYYY-MM-DD HH:MM:SS` in the app timezone; backed enums as their value. Change a format here once — never in a leaf or Resource.

In `AppServiceProvider::boot()` (non-production) turn on strictness so contract drift and N+1 leaks fail loudly:

```php
Model::preventLazyLoading(! app()->isProduction());
Model::preventSilentlyDiscardingAttributes(! app()->isProduction()); // rules() key not in $exposed → exception
```

### 2.4 Root: `BaseFilter` — one query engine driven by the criteria contract

```php
namespace App\Filters;

class BaseFilter
{
    public function apply(Builder $query, Request $request): Builder
    {
        $model = $query->getModel();

        if ($term = $request->string('search')->trim()->value()) {
            $query->where(fn ($q) => collect($model->contract('searchable'))
                ->each(fn ($col) => $q->orWhere($col, 'like', "%{$term}%")));      // bound, never concatenated
        }

        foreach ((array) $request->input('filter', []) as $col => $value) {
            if (in_array($col, $model->contract('filterable'), true)) {
                $query->where($col, $value);
            }
        }

        $sort = $request->string('sort', $model->contract('defaultSort'))->value();
        $col  = ltrim($sort, '-');
        if (in_array($col, $model->contract('sortable'), true) || $sort === $model->contract('defaultSort')) {
            $query->orderBy($col, str_starts_with($sort, '-') ? 'desc' : 'asc');
        }

        // ?include=status,details,visits_count — only tokens listed in $allowedIncludes
        $requested = array_intersect(
            explode(',', $request->string('include')->value()),
            $model->contract('allowedIncludes'),
        );
        $counts    = array_filter($requested, fn ($i) => str_ends_with($i, '_count'));
        $relations = array_diff($requested, $counts);

        return $query
            ->with($relations)                                                  // eager, never lazy
            ->withCount(array_map(fn ($i) => Str::beforeLast($i, '_count'), $counts));
    }

    public function selector(Builder $query): Builder
    {
        return $query->select($query->getModel()->contract('selector')); // [value, label] for dropdowns
    }
}
```

Unknown filter/sort/include keys are silently ignored — they can never reach SQL.

### 2.5 Root: `Modules\Core\Http\Resources\ModelResource` — exposure & N+1 contract

`ModelResource` is the **default and only** Resource for standard CRUD. Handwritten Resource classes are not created for standard endpoints.

```php
namespace Modules\Core\Http\Resources;

class ModelResource extends JsonResource
{
    /** Contracts already verified per class + column list, so checks cost nothing per row. */
    private static array $verified = [];

    public function toArray(Request $request): array
    {
        $row = $this->resource;

        return [
            ...$this->exposedFields($row),
            ...$this->exposedCounts($row),
            ...$this->exposedRelations($row),
        ];
    }

    /** Core columns from $exposed, formatted through publishedValue(). */
    protected function exposedFields(BaseModel $row): array
    {
        return $this->pick($row, $row->contract('exposed'));
    }

    /** "{relation}_count" — published only when the query path ran withCount(). Never computed here. */
    protected function exposedCounts(BaseModel $row): array
    {
        $out = [];

        foreach ($row->contract('exposedCounts') as $relation) {
            $key       = Str::snake($relation) . '_count';
            $out[$key] = $this->when(
                array_key_exists($key, $row->getAttributes()),
                fn () => (int) $row->getAttribute($key),
            );
        }

        return $out;
    }

    /** Relations — published only when already eager-loaded, reduced to their column allow-list. */
    protected function exposedRelations(BaseModel $row): array
    {
        $out = [];

        foreach ($row->contract('exposedRelations') as $relation => $spec) {
            $out[$spec['as'] ?? $relation] = $this->whenLoaded(
                $relation,
                fn () => $this->shape($row->getRelation($relation), $spec['columns']),
            );
        }

        return $out;
    }

    /** Map a loaded relation (model, collection or null) to its column allow-list. */
    protected function shape(mixed $related, array $columns): ?array
    {
        return match (true) {
            $related === null              => null,
            $related instanceof Collection => $related->map(fn (BaseModel $m) => $this->pick($m, $columns))->all(),
            default                        => $this->pick($related, $columns),
        };
    }

    /** Reduce a model to exactly the given columns. Never touches relations. */
    protected function pick(BaseModel $row, array $columns): array
    {
        $this->assertContract($row, $columns);

        $out = [];
        foreach ($columns as $column) {
            $out[$column] = $row->publishedValue($column);
        }

        return $out;
    }

    private function assertContract(BaseModel $row, array $columns): void
    {
        $key = $row::class . '|' . implode(',', $columns);

        if (isset(self::$verified[$key])) {
            return;
        }

        if ($columns === []) {
            throw new LogicException($row::class . ' publishes nothing: declare a non-empty exposure list.');
        }

        foreach ($columns as $column) {
            if ($row->isRelation($column)) {
                throw new LogicException(sprintf(
                    '%s exposes relation "%s" as a column — this lazy-loads once per row (N+1). '
                    . 'Expose the key column (e.g. "%s_id") and declare the relation in $exposedRelations.',
                    $row::class, $column, Str::snake($column),
                ));
            }
        }

        self::$verified[$key] = true;
    }
}
```

Contract rules:
- **Publish by allow-list.** Only `$exposed` columns, `$exposedCounts` and `$exposedRelations` ever reach JSON. Hidden attributes, accessors and unlisted columns never leak.
- **Zero exposure is fatal.** An empty `$exposed` (or an empty `columns` list in a relation spec) throws `LogicException` — there is no "dump all attributes" fallback.
- **Relation names are refused in core fields.** Listing `'status'` (a relation) instead of `'status_id'` throws `LogicException`, because reading it would lazy-load once per row.
- **Relations publish only via `whenLoaded()`**: the resource never triggers a query. Loading is the query path's job (`?include=` or explicit `with()`).
- **Every key in `$exposedRelations` must also be in `$allowedIncludes`** (and every `$exposedCounts` relation as `{relation}_count`), otherwise it can never be loaded.
- **Aliases** (`'as' => 'state'`) rename relations to public API vocabulary without renaming PHP methods.
- Relations are flattened to their column list; they are never recursively transformed, so nested N+1 is impossible.

### 2.6 Module base: plane pins & generic handlers

```php
namespace Modules\Core\Models;

abstract class CoreModel extends BaseModel
{
    use BelongsToPlane;

    protected string $plane = 'central';
    protected static string $tablePrefix = 'pf_';
}
```

```php
namespace Modules\Core\Http\Controllers;

abstract class ApiController extends BaseApiController
{
    /** Leaf declares the model class; the base resolves everything else from it. */
    protected string $repository;

    /** Default transformer for every leaf — override only for a genuinely non-standard response. */
    protected string $resource = ModelResource::class;

    public function __construct(protected BaseFilter $filter) {}

    protected function query(): Builder
    {
        return $this->repository::query();
    }

    protected function transaction(Closure $callback): mixed
    {
        return $this->repository::transaction($callback);   // model's plane connection
    }

    protected function listData(Request $request): JsonResponse
    {
        return $this->guard(fn () => $this->present(
            $this->resource::collection($this->filter->apply($this->query(), $request)->paginate())
                ->response()->getData(true),
        ));
    }

    protected function showData(Request $request, int|string $id): JsonResponse
    {
        return $this->guard(fn () => $this->present(
            $this->resource::make($this->filter->apply($this->query(), $request)->findOrFail($id)),
        ));
    }

    protected function saveData(BaseFormRequest $request): JsonResponse
    {
        return $this->guard(fn () => $this->present(
            $this->resource::make($this->transaction(fn () => $this->repository::create($request->payload()))),
            $this->message('created'),
            201,
        ));
    }

    protected function updateData(BaseFormRequest $request, int|string $id): JsonResponse
    {
        return $this->guard(fn () => $this->present(
            $this->resource::make($this->transaction(function () use ($request, $id) {
                $model = $this->query()->findOrFail($id);
                $model->update($request->payload());

                return $model;
            })),
            $this->message('updated'),
        ));
    }

    protected function destroyData(int|string $id): JsonResponse
    {
        return $this->guard(function () use ($id) {
            $this->transaction(fn () => $this->query()->findOrFail($id)->delete());

            return $this->present(null, $this->message('deleted'));
        });
    }
}
```

`$repository` here is the **model class auto-bound to the controller**, not a separate Repository layer (Section 3).

### 2.7 Leaf: thin and declarative

```php
final class HomeRoute extends CoreModel
{
    protected $table = 'home_routes';                    // → pf_home_routes

    // Exposure contract
    protected array $exposed          = ['id', 'name', 'path', 'status_id', 'published_on', 'created_at'];
    protected array $exposedCounts    = ['visits'];
    protected array $exposedRelations = [
        'status'  => ['columns' => ['id', 'name'], 'as' => 'state'],
        'details' => ['columns' => ['notes', 'settings']],
    ];

    // Criteria contract
    protected array  $searchable      = ['name', 'path'];
    protected array  $filterable      = ['status_id'];
    protected array  $sortable        = ['name', 'published_on', 'created_at'];
    protected array  $selector        = ['id', 'name'];
    protected string $defaultSort     = '-created_at';
    protected array  $allowedIncludes = ['status', 'details', 'visits_count'];

    protected $casts = ['published_on' => 'date'];

    public function status(): BelongsTo  { return $this->belongsTo(RouteStatus::class); }
    public function details(): HasOne    { return $this->hasOne(HomeRouteDetail::class); }
    public function visits(): HasMany    { return $this->hasMany(RouteVisit::class); }
}

final class AdminController extends ApiController
{
    protected string $repository = HomeRoute::class;     // $resource defaults to ModelResource

    public function index(Request $r)                          { return $this->listData($r); }
    public function show(Request $r, int $id)                  { return $this->showData($r, $id); }
    public function store(StoreHomeRouteRequest $r)            { return $this->saveData($r); }
    public function update(UpdateHomeRouteRequest $r, int $id) { return $this->updateData($r, $id); }
    public function destroy(int $id)                           { return $this->destroyData($id); }
}

final class StoreHomeRouteRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:120'],
            'path'         => ['required', 'string', 'max:255', 'unique:central.pf_home_routes,path'],
            'status_id'    => ['required', 'integer', 'exists:central.pf_route_statuses,id'],
            'published_on' => ['nullable', 'date'],
        ];
    }
}
```

`GET /home-routes?include=status,visits_count` returns `{ id, name, path, status_id, published_on: "2026-10-23", created_at, visits_count, state: { id, name } }` — two queries for the whole page, no matter how many rows.

## 3. Layering — Anti-Overengineering Rule

- **Simple CRUD** uses the module base handlers: `Leaf Controller → BaseFormRequest::payload() → Module base handler → Model → ModelResource`. **No Service, no Repository class, no handwritten Resource.**
- **Introduce a Service ONLY when** the resource needs complex business validation (cross-entity checks, state machines, quotas) or a multi-table transaction lifecycle (payroll run; order + stock + payment + ledger). The controller then calls the Service inside `guard()`, and the Service uses `Model::transaction()`.
- Add an interface + container binding only for a real second implementation or a mocked test.
- Third-party integrations (payment, SMS, Google API) each get their own adapter class.

## 4. Database Architecture & Table Shapes (First Priority)

### 4.1 Vertical Partitioning — High-Column Protection

Never create high-column tables. Split massive resources into **two tables with a 1:1 relationship**:

| Table | Holds |
|---|---|
| **Main** (e.g. `pf_home_routes`) | Core, frequently queried/filtered/sorted fields. All indexed columns live here. |
| **Details** (e.g. `pf_home_route_details`) | Infrequent data and large payloads: notes, JSON settings, `TEXT`/`JSON` blobs. |

- The Details table uses the Main key as both PK and FK (`home_route_id` primary + `foreignId()->constrained()->cascadeOnDelete()`).
- **Never declare `protected $with`** on any model. Details are loaded selectively on the query paths that need them — via `?include=details` (checked against `$allowedIncludes`) or an explicit `with('details')` / `load('details')` in the controller or Service — and published through `$exposedRelations`.
- The Details model is a normal leaf with its own non-empty `$exposed`.
- Write both rows inside the model's plane transaction:

```php
public static function createWithDetails(array $main, array $details): static
{
    return static::transaction(function () use ($main, $details) {
        $model = static::create($main);
        $model->details()->create($details);

        return $model->load('details');
    });
}
```

- Don't split small tables "just in case".

### 4.2 Selective Horizontal Partitioning

- **ONLY** high-volume, append-only time-series tables (logs, attendance, audit trails) are partitioned.
- System and business tables — `users`, `roles`, `permissions`, `settings`, `invoices`, master data — remain normal tables.
- The list of partitioned (physical, prefixed) table names lives only in `config/partitions.php`.

```php
// config/partitions.php
return ['tables' => ['tn_logs', 'tn_attendances']];
```

### 4.3 Human-Maintainable Year Routing — Native MySQL Partitioning

One physical table per resource, partitioned with `PARTITION BY RANGE (UNIX_TIMESTAMP(created_at))`. **Never** create per-year tables such as `tn_logs_2026`. Standard Eloquent works unchanged:

```php
TenantLog::create([...]);                         // MySQL routes the row to the right partition
TenantLog::where('user_id', 5)->latest()->get();
TenantLog::forYear(2025)->where(...)->get();      // bounded range → partition pruning
```

MySQL constraints to design for:
- The partition column must be in **every unique key** → composite PK `(id, created_at)`.
- `UNIX_TIMESTAMP()` partitioning needs a **`TIMESTAMP NOT NULL`** column (2038-01-19 limit — note it in the migration).
- **No foreign keys** on partitioned InnoDB tables, in either direction. Enforce integrity with FormRequest `exists:` rules.
- Always keep a **`pmax`** catch-all partition so inserts never fail.
- Boundaries use the MySQL session time zone → set the connection `timezone` in `config/database.php` to match `config('app.timezone')`.

Shared helper (used by migrations and the command; always on an explicit connection):

```php
namespace App\Support\Database;

final class YearlyPartition
{
    public static function apply(Connection $db, string $table, int $firstYear): void
    {
        $db->statement("ALTER TABLE `{$table}` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
        $db->statement(sprintf(
            'ALTER TABLE `%s` PARTITION BY RANGE (UNIX_TIMESTAMP(`created_at`)) (%s, PARTITION pmax VALUES LESS THAN MAXVALUE)',
            $table, self::definition($firstYear),
        ));
    }

    /** Split next year out of pmax. Idempotent. */
    public static function provision(Connection $db, string $table, int $year): bool
    {
        $exists = $db->table('information_schema.PARTITIONS')
            ->where('TABLE_SCHEMA', $db->getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('PARTITION_NAME', "p{$year}")
            ->exists();

        if ($exists) {
            return false;
        }

        $db->statement(sprintf(
            'ALTER TABLE `%s` REORGANIZE PARTITION pmax INTO (%s, PARTITION pmax VALUES LESS THAN MAXVALUE)',
            $table, self::definition($year),
        ));

        return true;
    }

    private static function definition(int $year): string
    {
        return sprintf("PARTITION p%d VALUES LESS THAN (UNIX_TIMESTAMP('%d-01-01 00:00:00'))", $year, $year + 1);
    }
}
```

Migration (tenant plane):

```php
return new class extends Migration
{
    protected $connection = 'tenant';

    public function up(): void
    {
        Schema::connection($this->connection)->create('tn_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('user_id');            // no FK on partitioned tables
            $table->string('action', 100);
            $table->json('payload')->nullable();
            $table->timestamp('created_at')->useCurrent();    // TIMESTAMP NOT NULL
            $table->timestamp('updated_at')->nullable();

            $table->primary(['id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });

        YearlyPartition::apply(DB::connection($this->connection), 'tn_logs', (int) now()->year);
    }
};
```

Model — standard leaf plus one shared scope trait:

```php
trait PartitionedByYear
{
    public function scopeForYear(Builder $query, int $year): Builder
    {
        return $query->whereBetween($this->qualifyColumn('created_at'), [
            Carbon::create($year)->startOfYear(), Carbon::create($year)->endOfYear(),
        ]);
    }
}

final class TenantLog extends TenantModel        // module base pins plane = 'tenant', prefix = 'tn_'
{
    use PartitionedByYear;

    protected $table = 'logs';
    protected array $exposed     = ['id', 'user_id', 'action', 'payload', 'created_at'];
    protected array $filterable  = ['user_id', 'action'];
    protected array $sortable    = ['created_at'];
    protected $casts             = ['payload' => 'array'];   // untyped, as declared on Model
}
```

Monthly provisioning command:

```php
class ProvisionPartitions extends Command
{
    protected $signature = 'partitions:provision {--year= : Defaults to next year}';

    public function handle(TenantManager $tenants): int
    {
        $year = (int) ($this->option('year') ?: now()->addYear()->year);

        $tenants->each(function ($tenant) use ($tenants, $year) {
            $tenants->connect($tenant);
            $db = DB::connection('tenant');

            foreach (config('partitions.tables') as $table) {
                if (YearlyPartition::provision($db, $table, $year)) {
                    $this->info("[{$tenant->id}] {$table}: added p{$year}");
                }
            }
        });

        return self::SUCCESS;
    }
}

// routes/console.php
Schedule::command('partitions:provision')->monthlyOn(1, '02:00')->withoutOverlapping()->onOneServer();
```

- Monthly runs keep `pmax` empty, so `REORGANIZE PARTITION pmax` stays near-instant; provision years in order only.
- Tenant onboarding: run tenant migrations, then `partitions:provision` for next year.
- Always filter partitioned list/report screens on `created_at` for pruning; composite indexes end with `created_at`; cross-year reports use `GROUP BY YEAR(created_at)` (no UNIONs); retention uses `DROP PARTITION`.
- Table names come only from migrations and `config/partitions.php`; years are integers — never user input.

## 5. Query Performance & Security

- **Eager-load explicitly** inside controllers/services (`with()`, `load()`, `withCount()`, or allowed `?include=`). No global `$with`. The resource only reads what is already loaded (`whenLoaded()`), refuses relation names in `$exposed`, and `preventLazyLoading()` makes any remaining N+1 leak throw in development.
- **Zero tolerance for SQL Injection**: Eloquent/Query Builder bindings or bound raw queries only. Filter, sort and include keys must pass through the model's contract lists before touching a query.
- **Transactions**: on tenant/core paths use `$this->transaction()` (controllers) or `Model::transaction()` / `BelongsToPlane::transaction()` — **never the global `DB::transaction()`**, which runs on the default connection and silently skips the plane.
- Input reaches models only through `BaseFormRequest::payload()`; never `$request->all()`. Output leaves only through `ModelResource`'s allow-lists.
- Authorization via Policies/Gates on every action, scoped per tenant.
- Indexes justified by real query patterns (FKs, `WHERE`, `ORDER BY`, `JOIN`, composite filters); indexed columns belong on the Main table, not Details.
- Paginate, select needed columns, and chunk/cursor large exports.
- Rate-limit auth/OTP/SMS endpoints; escape output; CSRF on state-changing web routes; never log secrets, tokens or tenant credentials.

## 6. JWT Authentication

- **Access Token**: short-lived (15–60 min) for API requests; expired tokens get a clear 401.
- **Refresh Token**: longer-lived, httpOnly cookie or hashed in DB; used only to mint access tokens and **rotated** on every use (old one invalidated).
- Store hashed active refresh tokens per user/tenant so they can be revoked on logout or security events.
- Include the tenant identifier in JWT claims so a token can't be replayed across tenant databases.

## 7. Frontend

- Vue.js 3 **Composition API** + Inertia.js, Bootstrap for styling.
- Composables mirror the backend contract: `useCrud` (talks to list/show/save/update/destroy endpoints and reads the `present()` envelope), `useFilters` (`search`, `filter[...]`, `sort`, `include`), `usePagination`, `useSelector` (dropdowns from the selector endpoint), `useYearFilter` (partitioned data).
- The frontend relies on the published shape: aliased relation keys (`state`), `{relation}_count` fields, and `YYYY-MM-DD` dates — no client-side reformatting of server values.
- **Pinia** stores only for state shared across pages (auth user, current tenant, lookups); page-local state stays in composables.
- Generic CRUD components shared across modules; module screens stay thin, like leaf classes.

## 8. Tools & Integrations

- Composer, Git, Docker.
- Payment gateway, SMS API, Google API — each behind its own adapter class so it can be swapped or mocked.

## Response Approach

When the user asks for code in this stack:
1. **Database shape first**: wide resource → Main + Details 1:1; massive append-only time-series → single natively partitioned table (composite PK, `TIMESTAMP created_at`, no FKs, `config/partitions.php`); everything else → a normal table.
2. **Place every class in the hierarchy**: what already exists in the Root Base, what belongs in the Module Base (plane, prefix, handlers, default resource), and keep the leaf declarative (contract properties, relations, rules, one-line actions).
3. **Declare the full contract on every leaf model**: `$exposed` (columns only, FK ids not relation names), `$exposedCounts`, `$exposedRelations` (with column lists and aliases), and the criteria properties. Make sure each exposed relation/count is in `$allowedIncludes`.
4. Simple CRUD → module base handlers + `ModelResource`, no Service. Complex rules or multi-table lifecycles → a Service called inside `guard()` using `Model::transaction()`.
5. Briefly call out eager loading, indexes, partition pruning, plane-safe transactions and security measures applied.
6. Never add a Service, Repository, interface, handwritten Resource or store the situation doesn't require.