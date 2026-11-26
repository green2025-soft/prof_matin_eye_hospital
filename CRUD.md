# Normal CRUD তৈরি করার নিয়ম

এই গাইডটি approved Laravel Module-এর সাধারণ CRUD-এর জন্য। Controller, Model, Request, Blade View এবং Route-এর জন্য [ARCHITECTURE.md](./ARCHITECTURE.md)-এর shared BaseController/BaseRequest flow ব্যবহার করতে হবে।

এই নিয়মটি `Investigation`, `Operation`, `Patient`, `Department`, `PaymentType` ইত্যাদির মতো ordinary CRUD-এর জন্য। Complex invoice, stock, payment, numbering, authentication বা multi-table lifecycle Service/Policy সহ যথাযথ ব্যবসায়িক pattern অনুসরণ করবে।

## ১. আগে ঠিক করুন

নিচের বিষয়গুলো নির্ধারণ করুন:

- Module নাম
- Resource singular class নাম
- URL এবং route name
- Table নাম ও fields
- Tenant-wide নাকি branch-scoped
- Separate create/edit page নাকি inline form/list
- View/Create/Update/Delete permission name
- Delete করতে referenced record block করা লাগবে কি না
- Foreign key, unique key এবং index কী দরকার

উদাহরণ:

```text
Module: Hospital
Resource: RefundCause
URL: /hospital/refund-causes
Route name: hospital.refund-causes
Table: refund_causes
Layout: Inline left form + right list
Permissions: refund-causes.view/create/update/delete
```

## ২. `php artisan module:make` নয়

Module আগে থেকেই approved ও installed থাকলে নতুন Module তৈরি করা যাবে না। নতুন CRUD-এর জন্য বর্তমানে থাকা approved Module ব্যবহার করুন।

পূর্ণ নতুন Module তৈরি করতে হলেও আগে approved name লাগবে এবং native generator চালাতে হবে:

```powershell
php artisan module:make ModuleName
php artisan module:list
```

Existing Module পুনরায় generate/force overwrite করা যাবে না।

## ৩. নতুন migration যোগ করুন

Migration শুধু existing migration path-এ যোগ করতে হবে:

```text
Modules/{Module}/database/migrations/
```

Migration-এ table, primary key, nullable fields, unique constraints, foreign keys এবং index দিন।

Tenant table হলে সাধারণত:

```php
Schema::connection('tenant')->create('refund_causes', function (Blueprint $table): void {
    $table->id();
    $table->string('name');
    $table->unsignedSmallInteger('ordering')->default(0);
    $table->timestamps();

    $table->unique('name');
    $table->index(['ordering', 'id']);
});
```

Already-applied migration edit করবেন না। নতুন column/constraint বা behavior-change হলে নতুন forward migration যোগ করুন।

## ৪. Model তৈরি করুন

Path:

```text
Modules/{Module}/app/Models/{Resource}.php
```

Model ordinary fields, casts, relations, `$crudPermission`, search/filter/sort contract এবং intrinsic deletion fact রাখবে।

Minimum structure:

```php
<?php

namespace Modules\Hospital\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'ordering'])]
class RefundCause extends BaseModel
{
    protected string $crudPermission = 'refund-causes';

    protected array $exposed = [
        'id',
        'name',
        'ordering',
        'created_at',
    ];

    protected array $searchable = ['name'];

    protected array $sortable = ['ordering', 'name', 'id'];

    protected string $defaultSort = 'ordering';

    protected function casts(): array
    {
        return [
            'ordering' => 'integer',
        ];
    }

    public function hasReferences(): bool
    {
        return $this->refunds()->exists();
    }
}
```

Model-এর `$exposed` খালি বা secrets/content ছাড়া exposure contract leak করবে না।

### Branch-scoped model

যে resource tenant-এর data রাখলেও selected branch-এর মধ্যে কাজ করবে, owning module-এর BaseModel থেকে explicit opt-in:

```php
protected bool $branchScoped = true;
```

Tenant-wide catalog-এর জন্য এটি ব্যবহার করবেন না।

### Referenced delete

Delete হলে referenced record থাকলে reject করতে:

```php
public function hasReferences(): bool
{
    return $this->relation()->exists();
}
```

Foreign key-ও restrictive বা appropriate constraint থাকতে হবে।

## ৫. Form Request তৈরি করুন

Path:

```text
Modules/{Module}/app/Http/Requests/{Resource}Request.php
```

HTTP field validation ও normalization শুধু Request-এ থাকবে।

```php
<?php

namespace Modules\Hospital\Http\Requests;

use Modules\Core\Http\Requests\BaseRequest;

class RefundCauseRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', $this->unique('name')],
            'ordering' => ['required', 'integer', 'between:0,65535'],
        ];
    }
}
```

Update-এর required fields partial update চলতে দিলে BaseRequest নিজে সব `required` field কে update context অনুযায়ী `sometimes` করতে পারে। Full aggregate update হলে resource অনুযায়ী `partialUpdates = false` নির্ধারণ করা যাবে।

Rule-এর list দিয়ে class-এর fillable fields mass assignment allow-list পালন করবেন।

## ৬. Controller তৈরি করুন

Path:

```text
Modules/{Module}/app/Http/Controllers/{Resource}Controller.php
```

Ordinary resource Controller-এ শুধু model/view/title/parameter declarations থাকবে।

```php
<?php

namespace Modules\Hospital\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Hospital\Http\Requests\RefundCauseRequest;
use Modules\Hospital\Models\RefundCause;

class RefundCauseController extends BaseController
{
    protected string $model = RefundCause::class;

    protected string $title = 'Refund Cause';

    protected string $views = 'hospital::refund-causes';

    protected string $parameter = 'refund_cause';

    protected bool $inlineForm = true;

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(RefundCauseRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
```

- Normal separate create/edit page-এর জন্য `$inlineForm = false`
- Left inline form/right list-এর জন্য `$inlineForm = true`
- Complex resource-এর জন্য নিজের `formData()` ব্যবহার করুন
- Ordinary resource-এর জন্য Service যোগ করবেন না
- Simple resource-এর জন্য dedicated Policy যোগ করবেন না

## ৭. Blade Views তৈরি করুন

List ও form সাধারণত:

```text
Modules/{Module}/resources/views/{resources}/index.blade.php
Modules/{Module}/resources/views/{resources}/form.blade.php
```

View-এ native Blade form, label, input, select, error, CSRF এবং method spoofing ব্যবহার করতে হবে।

একটি সাধারণ form শুরু:

```blade
<form action="{{ $formAction }}" method="POST">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <div class="mb-3">
        <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
        <input
            class="{{ inputClass('name') }}"
            id="name"
            name="name"
            type="text"
            value="{{ getValue('name', $refundCause) }}"
            maxlength="255"
            required
            {{ inputAttrs('name') }}
        >
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    @include('core::partials.form-actions', [
        'model' => $refundCause,
        'routePrefix' => $routePrefix,
        'submitLabel' => $submitLabel,
    ])
</form>
```

### Normal list/create/edit

- `index.blade.php`: heading, search/filter, list, pagination
- `form.blade.php`: create/edit form
- `create()` ও `edit()` BaseController handle করে
- Route resource `except('show')` হলে শুধু separate create/edit/list থাকে

### Inline form/list

`index.blade.php`-তে generic `$form` এবং `$records`:

```blade
@if ($form !== null)
    @include('hospital::refund-causes.form', $form)
@endif

@if ($records !== null)
    {{-- paginated list --}}
@endif
```

- Create forbidden হলে `$form = null`
- View forbidden হলে `$records = null`
- Edit authorized হলে populated form দেখাতে হবে

## ৮. Route যোগ করুন

Module-এর existing `routes/web.php`-এ native `Route::resource()` যোগ করুন।

```php
Route::middleware(['subscribed', 'branch:required', 'hospital.permission'])
    ->group(function (): void {
        Route::resource(
            'refund-causes',
            RefundCauseController::class,
        )->parameters([
            'refund-causes' => 'refund_cause',
        ])->except('show');
    });
```

Standard resource routes:

| HTTP | URL | Route name | Action |
|---|---|---|---|
| GET | `/refund-causes` | `{module}.refund-causes.index` | `index` |
| GET | `/refund-causes/create` | `{module}.refund-causes.create` | `create` |
| POST | `/refund-causes` | `{module}.refund-causes.store` | `store` |
| GET | `/refund-causes/{refund_cause}/edit` | `{module}.refund-causes.edit` | `edit` |
| PUT/PATCH | `/refund-causes/{refund_cause}` | `{module}.refund-causes.update` | `store` |
| DELETE | `/refund-causes/{refund_cause}` | `{module}.refund-causes.destroy` | `destroy` |

Store ও update route দুটিই controller-এর `store()` dispatch করবে। Route model binding নিজে করতে হবে না।

## ৯. Permission mapping যোগ করুন

`config/permissions.php`-এর route mapping-এ create/update/view/delete actions যোগ করুন।

```php
'hospital.refund-causes.index' => 'refund-causes.view',
'hospital.refund-causes.create' => 'refund-causes.create',
'hospital.refund-causes.store' => 'refund-causes.create',
'hospital.refund-causes.edit' => 'refund-causes.update',
'hospital.refund-causes.update' => 'refund-causes.update',
'hospital.refund-causes.destroy' => 'refund-causes.delete',
```

Permission catalog-এ চারটি grant রাখুন:

```text
refund-causes.view
refund-causes.create
refund-causes.update
refund-causes.delete
```

Permission name route mapping-এর মতোই `$crudPermission = 'refund-causes'`-এ থাকতে হবে।

## ۱۰. Menu যোগ করুন

Menu fixed Blade partial বা existing menu group-এ native `route()` এবং `request()->routeIs()` ব্যবহার করুন।

Blade-এ permission recalculation, model query বা database access রাখা যাবে না। `$moduleMenu['permissions']` scalar map থেকেই link দেখাতে হবে।

## ১১. Query contract ও list behavior

Model-এর contract থেকে shared list:

- `searchable`
- `sortable`
- `filterable`
- `$listWith`
- `$exposed`
- `$exposedRelations`
- `$exposedCounts`
- `defaultSort`

List-এর order deterministic রাখতে হবে। সাধারণত:

```php
protected array $sortable = ['ordering', 'name', 'id'];
protected string $defaultSort = 'ordering';
```

List page paginator, filter এবং size সরাসরি Blade-এ পাঠাবে। Blade-এ query বা list mapping loop করে row array বানানো যাবে না।

Allowed page size:

```text
5 / 10 / 15 / 20 / 30 / 50 / 100 / all
```

Default list size সাধারণত `10`।

## ১২. Database invariants

Ordinary CRUD-এর জন্য:

- `unique`
- `foreign key`
- `restrictOnDelete` বা প্রয়োজন অনুযায়ী `cascadeOnDelete`
- search/filter/order column-এর index
- tenant/branch ownership
- transaction
- `lockForUpdate` update/delete

Shared BaseController update/delete নিজেই fresh target lock এবং authorization recheck করে।

## ১৩. Validation feedback

Field validation সাধারণ Blade:

```blade
@error('name')
    <div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
```

Record-level delete/reference error:

```php
'record' => 'This record is in use and cannot be deleted.'
```

Delete 1451 query failure shared BaseController থেকে friendly validation feedback-এ convert হবে।

## ১৪. Create/edit/save flow

`store()`-এর shared flow:

1. BaseRequest authorize এবং validate
2. BaseController record resolve করে
3. Actor ও tenant/branch context verify
4. Fresh target lock
5. Create/update authorization recheck
6. Validated payload fill/save
7. Redirect এবং status flash

Write code-এ `$request->all()` বা `$request->input()` ব্যবহার করবেন না।

## ۱۵. Verification checklist

CRUD জমা দেওয়ার আগে verify করুন:

- Migration tenant/new tenant দুটোতে ঠিকভাবে apply হচ্ছে
- Permission granted actor create/edit/delete করতে পারছে
- Permission denied actor 403 পাচ্ছে
- List/Detail অন্য tenant বা branch থেকে leak হচ্ছে না
- Create ও update একই shared form ব্যবহার করছে
- `BaseRequest::unique()` create/update উভয় context-এ ঠিক কাজ করছে
- Foreign parent selection validated হচ্ছে
- Referenced delete block হচ্ছে
- Search, pagination, sorting এবং page size ঠিক আছে
- No unauthorized field mass assignment হচ্ছে
- Flash, inline validation এবং toast behavior ঠিক আছে
- Submitted file upload হলে rollback/commit cleanup ঠিক আছে
- Update target missing হলে 404 হচ্ছে

## ১৬. নতুন Resource শুরু করার file list

Minimum ordinary CRUD:

```text
Modules/{Module}/app/Http/Controllers/{Resource}Controller.php
Modules/{Module}/app/Http/Requests/{Resource}Request.php
Modules/{Module}/app/Models/{Resource}.php
Modules/{Module}/database/migrations/{timestamp}_create_{resources}_table.php
Modules/{Module}/resources/views/{resources}/index.blade.php
Modules/{Module}/resources/views/{resources}/form.blade.php
Modules/{Module}/routes/web.php
config/permissions.php
menu partial
```

একটি real detail page বা business-specific Policy প্রয়োজন হলে তবেই অতিরিক্ত show/details/Policy file যোগ হবে।
