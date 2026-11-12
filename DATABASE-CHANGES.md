# নতুন database column যোগ করার নিয়ম

এই project-এ central database ও প্রতিটি tenant-এর database আলাদা। নতুন column যোগ করতে একটি **নতুন migration** লিখতে হবে, তারপর সঠিক migration command চালাতে হবে। আগে চালানো create migration বদলালে পুরোনো database update হবে না।

## ১. Migration কোথায় রাখবেন

| পরিবর্তন | Migration folder | চালানোর command |
| --- | --- | --- |
| Accounting-এর tenant table | `Modules/Accounting/database/migrations/` | `php artisan tenants:migrate` |
| Hospital / Pharmacy / Prescription / TenantCore-এর tenant table | সংশ্লিষ্ট module-এর `database/migrations/` | `php artisan tenants:migrate` |
| Core-এর tenant-only table | `Modules/Core/database/migrations/tenant/` | `php artisan tenants:migrate` |
| Central registry / platform table | `Modules/Core/database/migrations/central/` | `php artisan platform:migrate` |
| Central ও tenant—দুই জায়গার shared table | `Modules/Core/database/migrations/shared/` | আগে `platform:migrate`, পরে `tenants:migrate` |

এই folder-গুলো ইতিমধ্যে `config/multitenancy.php`-তে আছে। এখানে নতুন migration যোগ করতে provider বা route বদলাতে হবে না। নতুন কোনো migration path প্রয়োজন হলে সেটি ওই config-এ স্পষ্টভাবে যোগ করতে হবে।

## ২. নতুন migration তৈরি করুন

সব command Laravel application folder থেকে চালাবেন—যেখানে `artisan` আছে।

উদাহরণ: Accounting-এর `acc_account_heads` table-এ optional `external_code` column যোগ করা।

```bash
php artisan make:migration add_external_code_to_account_heads --table=acc_account_heads --path=Modules/Accounting/database/migrations
```

Artisan timestamp-সহ নতুন file তৈরি করবে। সেই file-এ লিখুন:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->table('acc_account_heads', function (Blueprint $table): void {
            $table->string('external_code', 80)->nullable();
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->table('acc_account_heads', function (Blueprint $table): void {
            $table->dropColumn('external_code');
        });
    }
};
```

`nullable()` থাকায় পুরোনো row-গুলো নতুন column-এ `NULL` নিয়ে থাকবে। তাদের অন্য তথ্য বদলাবে না।

Central-only migration-এ `Schema::connection('central')` ব্যবহার করবেন। Shared migration-এ নির্দিষ্ট connection বসাবেন না; `Schema::table(...)` ব্যবহার করুন, যাতে command যে connection নির্বাচন করে সেখানেই একই migration চলে।

Required বা unique column হলে পুরোনো row-এর উপযুক্ত value আগে নির্ধারণ করতে হবে। সাধারণ flow: nullable column যোগ → প্রয়োজনীয় অনুমোদিত data পূরণ → পরের migration-এ constraint যোগ। Business অর্থ ছাড়া পুরোনো invoice বা হিসাবের value অনুমান করে বসাবেন না।

বর্তমান project-এর বাস্তব উদাহরণ: [Accounting report classification migration](Modules/Accounting/database/migrations/2026_11_12_172626_add_report_classification_to_account_heads.php)। এটি `system_key` ও `cash_flow_activity` যোগ করেছে।

## ৩. Form-এ column ব্যবহার করলে

শুধু database column যোগ করলে এই অংশ প্রয়োজন নেই। CRUD থেকে input নেওয়া বা দেখানোর জন্য সংশ্লিষ্ট বিদ্যমান file-গুলো update করুন:

| File | পরিবর্তন |
| --- | --- |
| Model | Write input হলে বিদ্যমান `Fillable` attribute বা `$fillable`-এ field যোগ করুন। Shared CRUD-এ পড়া/প্রকাশের জন্য `$exposed`-এ যোগ করুন। |
| Request | নতুন field-এর validation rule যোগ করুন। |
| Blade form | Input এবং inline error যোগ করুন। |
| Blade list / report | যেখানে দেখাতে হবে সেখানে field দেখান। |
| Explicit select query | Query যদি column আলাদা করে নেয়, প্রয়োজনমতো নতুন field সেই select-এ রাখুন। |
| Seeder | নতুন record-এর business default দরকার হলে বিদ্যমান owning seeder update করুন। |

সাধারণ string column-এর জন্য আলাদা cast দরকার নেই। Boolean, date বা amount হলে ওই resource-এর বিদ্যমান cast এবং money convention অনুসরণ করুন। আগের Fillable/exposed entry-গুলো রেখে নতুনটি যোগ করবেন।

`AccountHeadRequest::rules()`-এর উদাহরণ:

```php
'external_code' => ['nullable', 'string', 'max:80'],
```

`account-heads/form.blade.php`-এর উদাহরণ:

```blade
<div class="col-md-6">
    <label class="form-label" for="external_code">External code</label>
    <input class="{{ inputClass('external_code') }}" id="external_code" name="external_code"
        value="{{ getValue('external_code', $account_head) }}" maxlength="80"
        {{ inputAttrs('external_code') }}>
    @error('external_code')
        <div class="invalid-feedback d-block" id="external_code-error">{{ $message }}</div>
    @enderror
</div>
```

সাধারণ field-এর জন্য নতুন Controller, Service বা Route তৈরি করতে হবে না; existing validated payload ও shared CRUD flow ব্যবহার করুন। Seed update শুধু নতুন record তৈরি করে থাকলে তা পুরোনো row পূরণ করবে না—পুরোনো data পূরণের কাজ আলাদাভাবে স্পষ্ট করতে হবে।

## ৪. Local-এ migration চালান

সব registered tenant-এর pending migration আগে দেখুন, তারপর প্রয়োগ করুন:

```bash
php artisan tenants:migrate --pretend
php artisan tenants:migrate
```

একটি tenant বেছে করতে চাইলে:

```bash
php artisan tenants:migrate --tenant=almas-eye-hospital --pretend
php artisan tenants:migrate --tenant=almas-eye-hospital
```

একাধিক tenant-এর জন্য `--tenant` বারবার দেওয়া যায়। শুধু সংখ্যা দিলে সেটি tenant ID; অন্যটি slug হিসেবে ধরা হয়। কোনো selector না দিলে সব registered tenant নেয়, `deleting` status-এর tenant বাদ দেয়।

Central migration-এর জন্য:

```bash
php artisan platform:migrate --pretend
php artisan platform:migrate
```

`--pretend` database-এ connect করে pending SQL দেখায়; সঠিক credentials ও database ownership প্রয়োজন। এটি schema change, permissions/settings sync বা default seeding চালায় না।

প্রয়োগের পরে সংশ্লিষ্ট form/list খুলে দেখুন এবং প্রয়োজন হলে নতুন field-সহ একটি record save করুন।

## ৫. Production server update

আগে central ও tenant database এবং application/uploads backup নিন। Server-এর `.env`, `APP_KEY`, tenant credentials ও public-path customization রাখুন।

Application folder থেকে maintenance চালু করুন:

```bash
php artisan down
```

নতুন migration ও পরিবর্তিত application file upload করুন। Shared hosting-এ command চালানোর folder হবে application folder, যেমন `eye_app/`; public web root নয়।

তারপর cache পরিষ্কার করে preview দেখুন:

```bash
php artisan optimize:clear --except=cache
php artisan platform:migrate --pretend --force
php artisan tenants:migrate --pretend --force
```

Preview-তে প্রত্যাশিত pending migration আছে কি না দেখুন। এই repository-তে পুরোনো Pharmacy transaction-reset migration-ও রয়েছে; live server-এ সেটি pending দেখালে আগে migration history মিলিয়ে নিন। শুধু একটি column যোগ করতে গিয়ে transaction reset প্রয়োগ করা যাবে না।

Preview ঠিক থাকলে প্রয়োগ করুন:

```bash
php artisan platform:migrate --force
php artisan tenants:migrate --force
php artisan optimize
php artisan up
```

Tenant-only column-এর জন্য `tenants:migrate` প্রয়োজন; central বা shared migration থাকলে `platform:migrate` আগে চালাতে হবে। `--force` production confirmation বাদ দেয়, migration সফল হওয়ার নিশ্চয়তা দেয় না।

JS/CSS বদলালে local-এ `npm run build` করে সম্পূর্ণ `public/build/` actual web root-এর `build/`-এ upload করুন। Local generated config cache server-এ upload করবেন না।

Command চালাতে terminal না থাকলে hosting provider-কে application folder থেকে চালাতে বলুন। Migration চালানোর public URL বানাবেন না। বিস্তারিত hosting নির্দেশনা: [DEPLOYMENT.md](DEPLOYMENT.md)।

## ৬. সব tenant ও নতুন tenant কীভাবে update হবে

- `tenants:migrate` প্রতিটি tenant-এর নিজের database-এ pending migration চালায়। একই migration file সব tenant ব্যবহার করে।
- আগে সফল হওয়া migration আবার চলে না।
- Actual migration command permissions/settings sync এবং configured idempotent defaults-ও চালায়। Column যোগের জন্য আলাদা full `db:seed` প্রয়োজন নেই।
- পরে তৈরি হওয়া tenant provisioning-এর সময় সর্বশেষ migration-গুলো পাবে।
- `.env`-এর database name বারবার বদলে tenant migrate করতে হবে না; registry-এর saved credentials দিয়েই tenant switching হয়।

## ৭. মাঝখানে error হলে

Failed tenant ID ও application log দেখে কারণ ঠিক করুন। তারপর একই command আবার চালান, অথবা শুধু failed tenant নির্বাচন করুন:

```bash
php artisan tenants:migrate --tenant=TENANT_ID_OR_SLUG --force
```

এক tenant ব্যর্থ হলেও command অন্য tenant-গুলো চালিয়ে শেষে failure status ও failed IDs জানায়। সব database এক transaction-এ rollback হয় না। MySQL/MariaDB schema change-ও সাধারণ data transaction-এর মতো সম্পূর্ণ rollback হওয়ার নিশ্চয়তা নেই; retry করার আগে failed database-এর actual schema দেখুন।

Production-এ correction-এর জন্য নতুন forward migration লিখুন। `down()`-এ column drop করলে সেই column-এর data হারাবে। `migrate:fresh`, `migrate:refresh`, database reset বা full seeding দিয়ে column update করবেন না।
