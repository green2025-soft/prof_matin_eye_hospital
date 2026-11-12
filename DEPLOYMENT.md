# Production migration

নতুন column-এর migration তৈরি ও সব tenant-এ প্রয়োগের নিয়ম: [DATABASE-CHANGES.md](DATABASE-CHANGES.md)।

## Browser থেকে Server Update

Terminal বা cron ছাড়াই update করতে পারবেন:

1. Local-এ `npm run build` করুন। Updated application/Modules files এবং নতুন `public/build` upload করুন। Composer dependency/autoload বদলালে updated `vendor`-ও upload করুন। Shared hosting-এর public files আলাদা web root-এ থাকলে `build` সেখানেই যাবে। Server-এর `.env`, application key, uploaded images এবং custom `public/index.php` / public path ঠিক রাখুন। Local `.env`, `storage/framework` runtime files, `bootstrap/cache/*.php` ও `public/hot` upload করবেন না।
2. Central ও tenant database backup রাখুন। Update button **pending migration-এর code** চালায়; migration নিজে data পরিবর্তন করলে সেই পরিবর্তনও প্রযোজ্য হবে।
3. Central **Super Admin** দিয়ে login করুন। `https://eyecloudportal.com/core/deploy` খুলুন, অথবা menu থেকে **Server Update** নির্বাচন করুন। অন্য installation-এ নিজের central domain-এর `/core/deploy` ব্যবহার করুন। Tenant-এর Super Admin এই page ব্যবহার করতে পারবেন না।
4. নতুন file upload-এর পরে **Run Update** চাপুন এবং শেষ হওয়া পর্যন্ত page খোলা রাখুন। এটি preparation থেকে নতুন run শুরু করে। একই uploaded version-এর মাঝপথে থেমে যাওয়া কাজের জন্য **Continue Update** ব্যবহার করুন। Browser একটির পরে আরেকটি request পাঠাবে; queue worker বা cron লাগবে না।

প্রথমে generated config/route/event/view caches পরিষ্কার হবে। পরের নতুন request-এ নতুন configuration নিয়ে `platform:migrate --force` চলবে। তারপর প্রতিটি registered tenant-এর জন্য আলাদা request-এ `tenants:migrate --tenant=ID --force` চলবে; `deleting` tenant বাদ যাবে। এই existing commands-ই ownership/lock, pending schema, permissions, settings এবং configured idempotent defaults পরিচালনা করে। শেষে generated caches আবার পরিষ্কার হবে। Sessions, application cache ও images মুছবে না।

একটি tenant ব্যর্থ হলে অন্য tenant-এর কাজ চলবে। Page-এ failed tenant ID দেখা যাবে; কারণ `storage/logs/laravel.log`-এ থাকবে। Central/cache step ব্যর্থ হলে পরের কাজ থামবে। কারণ ঠিক করে **Continue Update / Run Update** দিন। Page বন্ধ হয়ে গেলে বা hosting timeout হলে একই URL থেকে continue করুন। Hosting একটি migration-ই শেষ করার আগেই বারবার connection বন্ধ করলে provider-কে request limit বাড়াতে হবে; browser flow সেই limit অতিক্রম করতে পারে না।

একসঙ্গে দুটি request migration চালাবে না। Retry-তে আগের সফল migration আবার চলে না; আলাদা database-এর কাজ একসঙ্গে rollback হয় না। Updater কোনো `migrate:fresh`, `reset`, database reinstall বা medicine catalog replacement চালায় না। Composer/npm command server-এর web request থেকে চলে না।

### এই feature প্রথমবার upload করলে

Server-এ আগে config বা route cache করা থাকলে পুরোনো cache নতুন URL চিনবে না। **একবার** File Manager থেকে Laravel project folder-এর `bootstrap/cache/config.php` ও `bootstrap/cache/routes-v7.php` মুছুন, যদি থাকে। `.env` বা অন্য project file মুছবেন না। এরপর login করে `/core/deploy` খুলুন। এই updater দিয়ে পরবর্তী update শেষ করলে config/route cache পরিষ্কার থাকবে।

Shared hosting-এর actual public root `index.php` থেকে নির্ধারিত হতে পারে। তাই web updater `config:cache` বা `route:cache` চালিয়ে নতুন application bootstrap করে না; তাতে ভুল public path cache হওয়ার ঝুঁকি এড়ানো হয়।

## Terminal থেকে migration

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

## SMS update on an existing production server

এই update-এ central database-এ `sms_accounts`, `sms_messages`, `sms_recharges` এবং প্রতিটি tenant database-এ `sms_outbox` যোগ হয়। আগের invoice, হিসাব ও settings রেখে pending migration চালাতে হবে। SMS-এর জন্য cron access বা queue worker লাগবে না। নিচের কাজগুলো server operator করবেন; এই নির্দেশনা লেখা বা build করা live SMS পাঠানোর প্রমাণ নয়।

1. Central এবং **সব tenant database**, uploaded files এবং server-এর `.env` backup নিন। Public uploads-এর সঙ্গে private recharge proof/storage-ও রাখুন। বিদ্যমান `APP_KEY`, database credentials ও `.env` অপরিবর্তিত রাখুন; নতুন key তৈরি করবেন না।
2. Local project-এ `npm run build` করুন। Upload-এর জন্য পরিবর্তিত `Modules/`, `config/`, `routes/`, `resources/` এবং bootstrap পরিবর্তন প্রস্তুত রাখুন। সম্পূর্ণ built `public/build/` server-এর actual web root-এর `build/`-এ যাবে—`manifest.json` এবং সব `assets/` একসঙ্গে।
3. **Server-এর public path customization রেখে merge করুন।** Deployed `index.php`-এর `../eye_app`/`__DIR__.'/../eye_app'` project path, `$app->usePublicPath(__DIR__)`, এবং `bootstrap/app.php`-এ server-এর public-path line local file দিয়ে overwrite করবেন না। নতুন SMS middleware registration-সহ প্রয়োজনীয় bootstrap পরিবর্তন merge করুন। Public files web root-এ থাকবে; application files আলাদা `eye_app`-এ থাকবে।
4. Terminal থাকলে upload শুরুর আগে project folder থেকে `php artisan down` দিন। ওপরের path customization রেখে প্রস্তুত files upload করার পরে নিচের commands **এই ক্রমে** চালান:

```bash
php artisan optimize:clear --except=cache
php artisan platform:migrate --force
php artisan tenants:migrate --force
```

প্রথম command generated caches পরিষ্কার করে, application cache মুছে না। `platform:migrate` central schema/settings/permission catalog update করে। `tenants:migrate` প্রতিটি registered tenant-এর pending schema এবং permission catalog/settings update করে; `deleting` tenant বাদ যায়। কোনো tenant ব্যর্থ হলে কারণ ঠিক করে একই command আবার চালান; আগের সফল migration আবার চলে না। শুধু `php artisan migrate` চালালে সব tenant update হবে না।

5. Migration সফল হলে চাইলে production caches তৈরি করে maintenance mode তুলুন:

```bash
# Optional: production config/event/route/view caches
php artisan optimize
php artisan up
```

`migrate:fresh`, reset বা `db:seed` চালাবেন না। এই update-এ business data বা catalog reset প্রয়োজন নেই। এই SMS flow-এর জন্য আলাদা scheduler/cron/worker চালু করবেন না; বিদ্যমান subscription/payment scheduler-এর দায়িত্ব আলাদা এবং অপরিবর্তিত।

Shared hosting-এ নিজের terminal না থাকলে cPanel Terminal ব্যবহার করুন অথবা hosting provider-কে application folder থেকে ওপরের commands চালাতে বলুন। Commands চালানোর জন্য public migration URL/route বানাবেন না।

### Migration-এর পরে SMS চালু করা

1. Core Settings-এর SMS-এ নিজের provider-এর API URL, sender ID, method/format ও credentials/parameter mapping দিন। কোনো endpoint অনুমান করে বসানো নেই। Provider-এর response documentation দেখে success field/value এবং নিশ্চিত rejection values configure করুন; timeout বা অজানা result-কে rejection ধরে credit ফেরত/resend করবেন না। Saved secrets blank রাখলে আগের value থাকে।
2. `/core/sms-accounts`-এ সব Tenant দেখা যায়। Configure দিয়ে Tenant বেছে SMS account চালু করুন; পরে Edit থেকে বদলাতে পারবেন। Allowed features-এ যেগুলো দেবেন শুধু সেগুলোই Tenant-এর Hospital/Pharmacy SMS Settings-এ আসবে। Account বন্ধ বা কোনো feature না দিলে Tenant-এর SMS menu, wallet, recharge ও SMS Settings tab থাকবে না; সরাসরি URL-ও বন্ধ থাকবে। **প্রতি part-এর টাকার rate**, এবং প্রয়োজন হলে monthly **part** limit দিন; limit খালি মানে monthly cap নেই। চাইলে ওই tenant-এর জন্য platform-managed private API override দিন; credentials encrypted থাকে। এখানে balance হলো টাকা, message count নয়।
3. Tenant-এর `/sms` থেকে balance/history দেখা ও payment reference/protected proof দিয়ে recharge request করা যায়। Core-এর `/core/sms-recharges`-এ authorized operator approve করলে একবার credit যোগ হয়। শুধু request/reference submit করলে টাকা যোগ হয় না।
4. `/hospital/settings`-এ Consultation, Investigation ও Operation-এর New entry payment আলাদাভাবে Paid/Due করুন। পুরোনো আচরণ রাখতে default Due থাকে; Paid mode নতুন form-এ payable বসায় এবং operator নিজে Paid বদলালে আর overwrite করে না। Edit বা validation-এর পুরোনো input অপরিবর্তিত থাকে। SMS subtab-এ ছয় feature-এর switch ও template আলাদাভাবে চালু করুন। `/pharmacy/settings`-এ Medicine/Optic sale, sales return, customer collection ও supplier payment-এর switch/template আছে; সব notification প্রথমে disabled।
5. Migration commands নতুন permission catalog sync করে; বিদ্যমান non-owner roles-এ প্রয়োজনীয় SMS view/manage/recharge/review grants দিন এবং প্রযোজ্য tenant Plan Permissions পরীক্ষা করুন। Wallet-এর feature entitlement ও module-এর notification switch—দুটিই প্রয়োজন।
6. Operator নিজের অনুমোদিত নম্বরে **একটি** নতুন entry/action করে history, charge এবং provider acceptance যাচাই করুন। SMS Submitted মানে provider/transport গ্রহণ করেছে; handset-এ Delivered হওয়ার প্রমাণ নয়। এখানে live verification করা হয়নি।

Source transaction commit হলে outbox থেকে response-এর পরে কাজ হয়; পরবর্তী সফল authenticated tenant write pending messages আবার পরীক্ষা করে। অপর্যাপ্ত balance, disabled gateway বা নিশ্চিত failed submission original event-এর **২৪ ঘণ্টার মধ্যে** পরবর্তী action-এ retry হতে পারে; এরপর Expired হয়। নিশ্চিত failed attempt-এর reserved টাকা ফেরত আসে। Timeout/unknown submission Needs review থাকে, টাকা reserved থাকে এবং auto-resend হয় না। `/core/sms-history` থেকে authorized operator provider যাচাই করে submitted confirm বা rejected/refund করতে পারেন। Saved invoice edit করলে নতুন notification হয় না; investigation Delivered event-ও unmark/remark করে duplicate হয় না।

### Organization details in templates

Organization Name-এর জন্য `{organization}`, Phone-এর জন্য `{organization_phone}` এবং Address-এর জন্য `{organization_address}` ব্যবহার করুন। এগুলো Core-এর Tenant profile থেকে আসবে; template-এ যেটি রাখবেন শুধু সেটিই SMS-এ যাবে। খালি Phone/Address খালি থাকবে। বিদ্যমান template পরিবর্তন হবে না; চাইলে Hospital/Pharmacy SMS Settings থেকে যোগ করুন। উদাহরণ:

```text
{organization}: Invoice {invoice}, due Tk {due}. Phone: {organization_phone}. Address: {organization_address}.
```

এই তিনটি variable সব notification-এ চলে; নতুন column বা migration দরকার নেই। নিচের patient variables শুধু patient/customer SMS-এর জন্য।

### Optional patient details in templates

Patient-এর তথ্য চাইলে message template-এ `{patient_name}`, `{patient_mobile}`, `{patient_address}`, `{patient_id}`, `{patient_age}` এবং `{father_husband_name}` বসাতে পারেন। `{patient_id}` হলো দেখানো Patient ID/code; Age আগের age হিসাব থেকেই আসে। যে token রাখবেন শুধু সেই তথ্য message-এ আসবে; আলাদা field switch লাগবে না। খালি optional তথ্য খালিই থাকবে এবং আগের saved template স্বয়ংক্রিয়ভাবে বদলাবে না। Patient-backed Hospital ও Pharmacy messages-এর জন্য উদাহরণ:

```text
{organization}: Patient {patient_id} - {patient_name}. Father/Husband: {father_husband_name}; Age: {patient_age}; Mobile: {patient_mobile}; Address: {patient_address}. Invoice {invoice}, due Tk {due}.
```

প্রয়োজন না হলে Father/Husband, Age বা Address-সহ ওই অংশের label এবং token মুছে দিন। নতুন table/column, migration বা patient data পুনরায় entry করার প্রয়োজন নেই।

SMS part অনুযায়ী খরচ হয়: GSM-7-এ single part 160 units, multi-part-এ প্রতি part 153; বাংলা/Unicode-এ 70 এবং 67 UTF-16 units। Extension characters ও emoji-তে একাধিক unit লাগতে পারে। [SMS encoding ও segment limits-এর official reference](https://www.twilio.com/docs/glossary/what-sms-character-limit) দেখুন; এই link reference মাত্র, project-এর provider Twilio ধরে নেওয়া হয়নি।

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
