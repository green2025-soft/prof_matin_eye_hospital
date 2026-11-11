# Production migration

Laravel project-এর folder থেকে command দুটি এই ক্রমে চালান:

```bash
php artisan platform:migrate --force
php artisan tenants:migrate --force
```

- `platform:migrate`: central database-এর pending migration চালায়।
- `tenants:migrate`: সব registered tenant-এর pending migration চালায়; `deleting` tenant বাদ যায়।
- আগে চালানো migration আবার চলে না। কোনো tenant ব্যর্থ হলে কারণ ঠিক করে command পুনরায় চালানো যাবে।

শুধু `php artisan migrate` চালালে সব tenant migrate হবে না।

Shared hosting-এর folder, public path ও image upload setup-এর জন্য [README-এর Shared hosting অংশ](README.md#shared-hosting) দেখুন।

## Hospital ও Pharmacy settings deploy

Hospital Settings এখন `/hospital/settings` এবং Pharmacy Settings `/pharmacy/settings`-এ। Controller, Request ও Blade নিজ নিজ module-এ থাকে। একই neutral Setting model, `hospital`/`pharmacy` group, row ID, JSON key ও saved value ব্যবহার হয়; `settings.view`/`settings.update`-এর সঙ্গে সংশ্লিষ্ট module ও subscription access লাগে, branch নির্বাচন লাগে না। Global `/settings`-এ এই দুই module-এর tab নেই। পুরোনো `?group=hospital`, `?group=pharmacy` ও `?group=patients` link সঠিক module page-এ redirect হয় এবং section বজায় রাখে।

পরিবর্তিত PHP, Blade views, config ও routes upload করার পরে project folder থেকে চালান:

```bash
php artisan optimize:clear --except=cache
```

শুধু এই settings স্থানান্তরের জন্য schema বা data migration লাগে না। Production database reset করবেন না এবং `db:seed` চালাবেন না; আগের settings ও business data অপরিবর্তিত থাকবে। অন্য pending schema change থাকলে উপরের নিয়ম অনুযায়ী migration command চালান।

## আগে database তৈরি করে Tenant setup

নতুন Tenant setup-এর একই transaction-এ `Cash` নামে Payment Type তৈরি হয়। Retry করলে একই নামের entry আবার তৈরি হয় না।

প্রতিটি নতুন tenant-এর জন্য hosting control panel-এ আগে একটি **খালি database** ও database user তৈরি করুন। User-টিকে ওই database-এর সঙ্গে যুক্ত করে প্রয়োজনীয় permissions দিন। তারপর `/core/tenants/create` অথবা `php artisan tenant:create` থেকে নিচের তথ্য দিন:

- `DB_DATABASE`: control panel-এ দেখানো সম্পূর্ণ database name, hosting prefix-সহ।
- `DB_USERNAME`: ওই database-এ access পাওয়া user।
- `DB_PASSWORD`: ওই user-এর password; local database user-এর password না থাকলে খালি রাখা যায়।

এগুলো Tenant form-এর তথ্য; প্রতিটি tenant-এর জন্য project-এর `.env` পরিবর্তন করবেন না। Database host/port application-এর configured MySQL server থেকেই আসে। Database name অনন্য এবং সর্বোচ্চ 64 অক্ষরের হবে; Tenant Create দেওয়া নামটি ব্যবহার করে, slug থেকে আরেকটি database name তৈরি করে না।

Submit করলে application connection ও খালি database যাচাই করে, ownership marker বসায়, সব tenant migration চালায় এবং owner account, permissions, default settings, default catalog ও default subscription তৈরি করে। অন্য tenant-এর Patient, invoice বা transaction কপি করে না। Tenant setup ও retry `CREATE DATABASE` চালায় না; তাই নতুন tenant তৈরির জন্য global database-creation permission লাগবে না।

Tenant database user-এর permissions নির্বাচিত database-এর ওপর দিন:

```text
CREATE, ALTER, DROP, INDEX, REFERENCES,
SELECT, INSERT, UPDATE, DELETE
```

এখানে `CREATE` ওই database-এর ভেতরে table তৈরির জন্য। Independent provisioning connection-এ application-এর `GET_LOCK()` ও `RELEASE_LOCK()` চলতে হবে। `.env`-এর optional `DB_PROVISION_USERNAME`/`DB_PROVISION_PASSWORD` এই infrastructure connection-এর জন্য; tenant-এর নিজস্ব login credentials-এর বিকল্প নয়। Platform installation আলাদা workflow; এই পরিবর্তন central database-এর setup বদলায় না।

নিজস্ব tenant credentials central registry-তে encrypted থাকে এবং model serialization-এ hidden থাকে। অনুমতিপ্রাপ্ত central Tenant form-এ database name, username ও password দেখা যায়; edit-এ এই fields পরিবর্তন করা যায় না। Tenant বদলালে database connection-এর credentials-ও বদলায় এবং tenant context ছাড়লে আগের default configuration ফিরে আসে। নতুন nullable credential columns-এর central migration আগে চালাতে হবে:

```bash
php artisan platform:migrate --force
php artisan tenants:migrate --force
```

আগের tenant-এর credentials খালি থাকলে আগের `.env` database user/password-ই ব্যবহৃত হবে; existing database ও account password পরিবর্তন হবে না। Application key অপরিবর্তিত রাখুন।

Setup মাঝপথে ব্যর্থ হলে একই registration, database name, ownership token ও stored credentials দিয়ে চালু করুন:

```bash
php artisan tenant:retry TENANT_SLUG --force
```

Command-এ tenant administrator-এর name ও password দিন। `tenant:adopt` আগে থেকেই থাকা populated tenant database ও owner account গ্রহণ করার জন্য; সেটি নতুন empty database setup-এর flow নয়, owner password বদলায় না এবং default catalog seed করে না।
