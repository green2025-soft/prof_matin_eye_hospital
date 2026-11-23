# `config/permissions.php` কোথায় ব্যবহৃত হয়

এই ফাইলটি named route-এর permission mapping এবং permission catalog-এর UI label সংজ্ঞায়িত করে। এটি নিজে কোনো account-কে permission দেয় না; runtime-এ route middleware authorization করে, আর permission grants ও Plan access বর্তমান database/Plan থেকে আসে।

## সরাসরি ব্যবহার

| কোড | কীভাবে config ব্যবহার করে |
| --- | --- |
| [`EnsureModulePermission`](../Modules/Core/app/Http/Middleware/EnsureModulePermission.php) | বর্তমান route name-এর জন্য `platform_routes` ও `tenant_routes` দেখে মূল permission নেয়; `tenant_route_requirements`-এর সব permission অতিরিক্তভাবে চায়; `tenant_route_alternatives`-এর যেকোনো একটি থাকলে গ্রহণ করে; কোনো mapping বা alternative না থাকলে route-টি `authenticated_routes`-এ থাকতে হয়, নইলে `403` দেয়। |
| [`PermissionCatalog`](../Modules/Core/app/Services/PermissionCatalog.php) | `tenantPermissions()` দিয়ে `tenant_routes`-এর permission name ও `tenant_permissions` একত্র করে। `areas()` role/plan permission matrix সাজাতে `groups` ও `actions`-এর label পড়ে। |

`EnsureModulePermission`-এর middleware alias [`bootstrap/app.php`](../bootstrap/app.php) এবং owning module provider-গুলোতে নিবন্ধিত হয়। Core, TenantCore, Hospital, Pharmacy, Accounting ও Prescription-এর route group-গুলো নিজ নিজ `*.permission` alias ব্যবহার করে; সেসব request-এ middleware named route অনুযায়ী উপরের mapping প্রয়োগ করে।

## কোন অংশ কোন কাজে লাগে

- **`authenticated_routes`** — আলাদা permission mapping নেই এমন authenticated route-কে middleware-এর permission-map check পার হতে দেয়। Alternative mapping থাকলেও route-টি ওই alternatives-এর অন্তত একটি permission চাইবে। এটি `auth`, tenant, subscription বা branch middleware-কে বাদ দেয় না।
- **`platform_routes`** — central/platform named route থেকে প্রয়োজনীয় platform permission-এর mapping।
- **`tenant_routes`** — tenant named route থেকে প্রয়োজনীয় permission-এর mapping। একই catalog Role ও Plan Permissions পেজেও ব্যবহৃত হয়।
- **`tenant_route_requirements`** — route-এর মূল permission-এর পাশাপাশি আবশ্যিক অতিরিক্ত permission।
- **`tenant_route_alternatives`** — তালিকার অন্তত একটি permission থাকলে route অনুমোদিত।
- **`tenant_permissions`** — route-নির্ভর নয় এমন tenant capability, যেমন `pharmacy-payments.discount`; এটিও tenant permission catalog-এ থাকে।
- **`groups`** ও **`actions`** — Role/Plan/Doctor permission matrix-এ resource group এবং action-এর প্রদর্শিত নাম। `group_key` দিয়ে একাধিক resource এক group-এ নেওয়া যায়, আর `permission_label` দিয়ে পৃথক permission-এর নাম নির্দিষ্ট করা যায়। এগুলো grant বা authorization তৈরি করে না।

`tenant_routes`-এ mapping যোগ করলে সেই permission tenant catalog-এও আসে। বিপরীতে `platform_routes`-এর মানগুলো central permission enum থেকে আসে; সেগুলো `tenant_routes` catalog-এ মেশে না।

## Report permission group

Report permissions-এর grant code আলাদা থাকে, কিন্তু permission matrix-এ সেগুলো একই **Area: Hospital → Group: Report**-এ দেখানো হয়। Group-এর permission labels হলো **Monthly Cash Report**, **Cash and Due Balance**, এবং **Patient Due**। `hospital-patient-due.view` code-টি অপরিবর্তিত রাখা হয়েছে; এটি route authorization, role grants ও Plan permission-এ ব্যবহৃত identifier।

`PermissionCatalog::areas()` `groups` config-এর `group_key` এক হলে সেই resource-গুলোর permission একই matrix group-এ একত্র করে। `permission_label` দিয়ে group-এর প্রতিটি permission-এর আলাদা নাম দেখানো হয়। নতুন Hospital report যোগ করতে:

1. Route-টির named route ও middleware নির্ধারণ করুন।
2. `tenant_routes`-এ named route-কে unique permission code-এ map করুন। উদাহরণ:

   ```php
   'hospital.reports.new-report' => 'hospital-new-report.view',
   ```

3. `groups`-এ permission code-এর শেষের `.view` বাদে বাকি অংশকে key হিসেবে যোগ করুন। একই `group_key`, `label` ও `area` ব্যবহার করে শুধু `permission_label`-এ নতুন রিপোর্টের নাম দিন:

   ```php
   'hospital-new-report' => [
       'label' => 'Report',
       'group_key' => 'reports',
       'permission_label' => 'New Report',
       'area' => 'Hospital',
   ],
   ```

4. `tenants:migrate` দিয়ে tenant permission catalog sync করুন। নতুন permission code Role ও Plan Permissions matrix-এ আসবে; এটি নিজে থেকে existing non-owner account-কে grant হবে না।

## পরোক্ষভাবে ব্যবহারের পথ

1. **Permission synchronization:** [`TenantAccounts`](../Modules/TenantCore/app/Services/TenantAccounts.php) owner তৈরি/adopt করার সময় এবং [`MigrateTenantsCommand`](../Modules/Core/app/Console/Commands/MigrateTenantsCommand.php) `tenants:migrate`-এ `PermissionCatalog::sync()` চালায়। এতে Access permissions-এর সঙ্গে `tenant_routes` ও `tenant_permissions` থেকে পাওয়া tenant permissions বর্তমান tenant database-এ রাখা হয়।
2. **Platform synchronization:** [`PlatformInstaller`](../Modules/Core/app/Services/PlatformInstaller.php) platform migrate/install-এ `sync(platform: true)` চালায়। Central catalog তৈরি হয় Access ও Platform permission enum থেকে; এটি tenant catalog sync নয়।
3. **Role Permissions পেজ:** [`RolePermissionService`](../Modules/Core/app/Services/RolePermissionService.php) database-এ থাকা এবং actor-কে দেওয়া permission-গুলো দেখায়; `PermissionCatalog::areas()` সেগুলোকে `groups`/`actions` label দিয়ে সাজায়।
4. **Plan Permissions পেজ:** [`PlanPermissionController`](../Modules/Core/app/Http/Controllers/PlanPermissionController.php) tenant catalog ও label দিয়ে matrix তৈরি করে; [`PlanPermissionRequest`](../Modules/Core/app/Http/Requests/PlanPermissionRequest.php) জমা দেওয়া permission-গুলো tenant catalog-এর মধ্যে আছে কি না যাচাই করে।
5. **Doctor Permissions পেজ:** [`DoctorService`](../Modules/Hospital/app/Services/DoctorService.php) actor-এর অনুমোদিত permission-গুলোকে একই `areas()` label দিয়ে প্রদর্শন করে।

## Permission যোগ বা পরিবর্তন করার সময়

- Route access বদলালে named route-এর permission entry, অতিরিক্ত requirement, alternative, অথবা authenticated exception-কে উপযুক্ত key-তে পরিবর্তন করুন। Route-এ permission middleware থাকলে mapping-ও থাকতে হবে; নইলে middleware `403` দেয়।
- নতুন tenant-only, non-route capability হলে `tenant_permissions`-এ যোগ করুন।
- নতুন permission matrix label দরকার হলে permission name-এর শেষের অংশ অনুযায়ী `groups`-এ resource label/area এবং `actions`-এ action label দিন। Label না থাকলে catalog সাধারণ নাম তৈরি করে।
- Tenant catalog-এর নতুন permission database-এ sync করতে প্রয়োজনীয় provisioning বা `tenants:migrate` চালাতে হবে। শুধু config সম্পাদনা করলেই বিদ্যমান database-এ permission row তৈরি হয় না।
- Grant ও route mapping এক জিনিস নয়: route middleware account-এর বর্তমান grants এবং প্রযোজ্য Plan limit মিলিয়ে দেখে। এই config ফাইলে নাম যোগ করলেই non-owner account স্বয়ংক্রিয়ভাবে সেই permission পায় না।

> [`config/permission.php`](./permission.php) আলাদা ফাইল: সেটি Spatie package-এর permission/role table ও guard configuration; এই নথির `config/permissions.php` হলো application permission catalog ও route mapping।
