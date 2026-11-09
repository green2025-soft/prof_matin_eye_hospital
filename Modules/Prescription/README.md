# Prescription Module

এই module-এ **General Prescription** ও **Glass Prescription** দুটি আলাদা CRUD আছে।
দুটি একই module-এর menu, tenant context ও print layout ব্যবহার করে; তাদের form এবং সংরক্ষণের কাজ আলাদা।
পুরো project-এর নিয়মের জন্য [AGENTS.md](../../AGENTS.md) ও [ARCHITECTURE.md](../../ARCHITECTURE.md) দেখুন।
Medicine entry-এর function ও event ধরে বিস্তারিত প্রবাহ: [Medicine JS Lifecycle](MEDICINE-JS-LIFECYCLE.md)।

## কোন URL-এ কী আছে

| URL | কাজ |
| --- | --- |
| `/prescription` | আজকের সংখ্যা, সর্বশেষ ছয়টি General/Glass prescription ও অনুমতিভিত্তিক New বাটন |
| `/prescription/prescriptions` | General Prescription তালিকা ও CRUD |
| `/prescription/glass-prescriptions` | Glass Prescription তালিকা ও CRUD |
| `/prescription/options` | Clinical preset, medicine unit ও instruction-এর inline CRUD |
| `/prescription/settings` | Feature, optional section, Rx number ও print settings |

সব route [routes/web.php](routes/web.php)-তে আছে। সাধারণ middleware ওই group-এ দেওয়া আছে।
Dashboard এবং দুই prescription CRUD-এ selected branch প্রয়োজন; preset ও settings tenant-wide।

## CRUD-এর মূল প্রবাহ

```text
Route → Resource Request → Resource Controller → Core BaseController → Model
General aggregate save → PrescriptionService → Prescription + PrescriptionMedicine
Glass save            → BaseController → GlassPrescription
```

Controller-এর `index()` shared `listData()` এবং `store()` shared `saveData()` ব্যবহার করে।
Core [BaseController](../Core/app/Http/Controllers/BaseController.php) সাধারণ create/edit/destroy, authorization ও redirect সামলায়।
Resource Request-গুলো Core [BaseRequest](../Core/app/Http/Requests/BaseRequest.php) অনুসরণ করে।
General-এর header ও medicine একসঙ্গে সংরক্ষণ করতে service আছে; Glass-এর আলাদা aggregate service নেই।

Show/print-এ Patient ও Doctor সরাসরি Eloquent model এবং medicine rows Collection হিসেবে Blade-এ যায়।
Blade `->` দিয়ে field পড়ে; `only()`/`toArray()` করে অনুলিপি তৈরি করা হয় না। প্রয়োজনীয় relations আগে eager load হয়।
Medicine-এর Generic সরাসরি `PrescriptionMedicine::generic()` relation-এ পাওয়া যায়।
Show action clinical JSON-কে native object cast-এ পড়ে; তার print formatting থাকে `prints`-এর Blade partial-এ।
`Setting::values()`-এর config তার স্বাভাবিক array-ই থাকে।
Section enable/disable অনুযায়ী data Controller-এ প্রস্তুত হয়; Blade শুধু উপস্থিত data দেখায়।

## গুরুত্বপূর্ণ PHP ও Blade ফাইল

| ফাইল | দায়িত্ব |
| --- | --- |
| [PrescriptionController](app/Http/Controllers/PrescriptionController.php) | General form/list/show-এর data প্রস্তুত এবং shared CRUD-এ delegation |
| [PrescriptionRequest](app/Http/Requests/PrescriptionRequest.php) | General header, clinical details ও medicine payload validation |
| [Prescription](app/Models/Prescription.php) | Header fields, casts, Patient/Doctor/Medicine relations ও query metadata |
| [PrescriptionMedicine](app/Models/PrescriptionMedicine.php) | প্রতিটি Rx medicine-এর তথ্য, Product relation ও dosage group |
| [PrescriptionService](app/Services/PrescriptionService.php) | Transaction, fresh permission check, branch checks এবং header/medicine persistence |
| [GlassPrescriptionController](app/Http/Controllers/GlassPrescriptionController.php) | Glass CRUD এবং form/show data |
| [GlassPrescriptionRequest](app/Http/Requests/GlassPrescriptionRequest.php) | Glass values, Patient/Doctor ও optional field validation |
| [GlassPrescription](app/Models/GlassPrescription.php) | Right/Left DV/NV values, PD, lens options ও relations |
| [PrescriptionPolicy](app/Policies/PrescriptionPolicy.php) | Shared CRUD authority-এর সঙ্গে Doctor update ownership check |
| [PrescriptionServiceProvider](app/Providers/PrescriptionServiceProvider.php) | Routes/views, composers, Rx pattern validation ও created event registration |
| [General views](resources/views/prescriptions) | `index`, shared `form`, `show` ও section/Rx modal partials |
| [Glass views](resources/views/glass-prescriptions) | Glass-এর নিজস্ব `index`, shared `form` ও `show` |

## কোন table-এ কোন data

| Tenant table | তথ্য |
| --- | --- |
| `prescriptions` | General header, Patient/Doctor, clinical text/JSON এবং follow-up |
| `prescription_medicines` | `prescription_id` অনুযায়ী ordered medicine rows |
| `glass_prescriptions` | Glass header ও দুই চোখের DV/NV values |
| `prescription_options` | `section`, `name`, `ordering` অনুযায়ী tenant-wide presets |

General ও Glass branch-scoped; module [BaseModel](app/Models/BaseModel.php) native tenant connection ব্যবহার করে।
Medicine row-এ optional `product_id` এবং `medicine_name` snapshot থাকে। হাতে লেখা medicine-এ Product link থাকে না।
Schema-এর মূল ও পরবর্তী পরিবর্তনগুলো [database/migrations](database/migrations)-এ আছে।
Settings module-এর আলাদা prescription table নেই; Core `Setting`-এর `prescription` group-এ থাকে।

## General Prescription save হলে

1. [form.blade.php](resources/views/prescriptions/form.blade.php) Patient, Doctor ও date দেখায়; section-গুলো modal-এ edit হয়।
2. Controller edit-এর saved medicine এবং সংশ্লিষ্ট Product/Generic/Dosage data প্রস্তুত করে।
3. Browser-এর editor মূল form-এর field value বদলায়; pad preview সেই value থেকে আপডেট হয়।
4. Submit হলে `PrescriptionRequest` বর্তমান settings এবং branch অনুযায়ী payload যাচাই করে।
5. `PrescriptionService::save()` tenant transaction-এ permission এবং নির্বাচিত Patient/Doctor আবার যাচাই করে।
6. Catalog-linked medicine-এর নাম Product থেকে নেয়; manual medicine-এর entered name নেয়।
7. Header save করে medicine rows নতুন submitted ordering অনুযায়ী replace করে; interval থাকলে follow-up date server-এ হিসাব করে।
8. View permission থাকলে save-এর পরে General show/print page-এ যায়; failure-এ input ও field errors ফেরত আসে।

General create-এ `?copy=<id>` থাকলে অনুমোদিত source থেকে শুধু Doctor নেওয়া হয়।
Doctor account দিয়ে login করলে নিজের Doctor selection Request-এও নির্ধারিত হয়।

## Glass Prescription save হলে

Glass-এর [form](resources/views/glass-prescriptions/form.blade.php) General-এর Rx medicine editor ব্যবহার করে না।
`GlassPrescriptionRequest` validated values পাঠায় এবং BaseController সরাসরি `GlassPrescription` save করে।
Right/Left Sphere, Cylinder, Axis, Acuity-এর DV/NV values নিজস্ব table-এ থাকে।
PD, disclaimer ও lens options settings অনুযায়ী form/request-এ নিয়ন্ত্রিত হয়।
Glass show একই [print layout](resources/views/prints/layout.blade.php) ব্যবহার করে, content নিজস্ব view-তে থাকে।

## Form ও modal-এর data কোথায় যায়

মূল form-এর ID হলো `prescription-form`। বাইরে থাকা modal input-এ `form="prescription-form"` থাকায় সেগুলোও ওই form-এর সঙ্গে submit হয়।
Select2 search এবং suggestion button নিজেরা prescription-এর saved payload নয়।

| Field/name | সংরক্ষিত অর্থ |
| --- | --- |
| `patient_id`, `doctor_id`, `prescription_date` | Header identity |
| `chief_complaints`, `history`, `examination`, `diagnosis`, `investigations`, `advice` | নির্বাচিত নাম বা text |
| `section_details[section][index]` | Clinical item-এর `name`, `duration`, `duration_unit`, `notes` |
| `history_details[...]` | Drug, investigation, personal, family ও অন্যান্য history details |
| `examination_details[...]` | BP, pulse, temperature, height এবং অন্যান্য entered measurements |
| `medicines[index][...]` | Product/name, dosage type, dose, frequency, duration ও instructions |
| `follow_up_interval`, `follow_up_interval_unit`, `follow_up_date`, `follow_up_notes` | Follow-up schedule ও notes |

General prescription save সাধারণ HTML form submission। Preset/medicine-instruction তৈরি আলাদা authorized AJAX operation।
Medicine/clinical preview-এর Remove button মূল field/row আপডেট করে; শুধু দৃশ্যমান text মুছে রাখে না।

## Prescription-এর ১৩টি JS ফাইল

Root [app.js](../../resources/js/app.js) থেকে [prescription.js](../../resources/js/prescription.js) load হয়।
সব ফাইল ESM এবং একই [jquery.js](../../resources/js/jquery.js) instance ব্যবহার করে।

| JS ফাইল | দায়িত্ব |
| --- | --- |
| [prescription.js](../../resources/js/prescription.js) | Page initialization, history catalogs, follow-up sync, modal tab search ও server error focus |
| [prescription-medicines.js](../../resources/js/prescription-medicines.js) | Rx search, add/remove, saved rows, schedule/unit/duration এবং medicine pad preview |
| [prescription-application-dose.js](../../resources/js/prescription-application-dose.js) | Medicine অনুযায়ী dosage group, Drop/Gel controls ও existing frequency text |
| [prescription-keyboard.js](../../resources/js/prescription-keyboard.js) | F2/F4/F8, Enter navigation এবং palette keyboard movement |
| [prescription-instructions.js](../../resources/js/prescription-instructions.js) | Food instruction, instruction search/create, badge ও pending-save state |
| [prescription-picker.js](../../resources/js/prescription-picker.js) | Clinical preset ও Investigation picker-এর একই selection/search operation |
| [prescription-clinical-items.js](../../resources/js/prescription-clinical-items.js) | নির্বাচিত clinical item-এর duration/unit/notes row রাখা ও পুনর্বিন্যাস |
| [prescription-preview.js](../../resources/js/prescription-preview.js) | Clinical/history/examination/follow-up pad preview ও linked removal |
| [prescription-examination.js](../../resources/js/prescription-examination.js) | Optional examination measurement row expand/collapse |
| [prescription-header-dropdown.js](../../resources/js/prescription-header-dropdown.js) | Modal header-এর Select2 results search-এর নিচে স্থির রাখা |
| [prescription-palette.js](../../resources/js/prescription-palette.js) | Popper দিয়ে dose/unit palette-এর অবস্থান এবং open/close lifecycle |
| [prescription-print.js](../../resources/js/prescription-print.js) | Patient ID barcode এবং print paper/margin CSS rules প্রয়োগ |
| [prescription-settings.js](../../resources/js/prescription-settings.js) | General/Glass Custom paper dimensions দেখানো এবং input enable/disable |

### General form-এর initialization order

`prescription.js` form উপস্থিত থাকলে নিচের ক্রমে কাজ করে:

1. `initializePrescriptionPickers()` — clinical ও investigation selection প্রস্তুত করে।
2. `initializePrescriptionMedicines()` — saved Rx rows, search, previews ও keyboard handlers প্রস্তুত করে।
3. `initializeHeaderSearches()` — modal search-এর placeholder ঠিক রাখে।
4. `initializePrescriptionClinicalItems()` — clinical duration/notes rows পুনরুদ্ধার করে।
5. `initializePrescriptionExamination()` — optional measurement controls bind করে।
6. `initializePrescriptionPreviews()` — prepared fields থেকে pad preview বানায়।
7. History catalogs এবং follow-up date/interval synchronization bind হয়।
8. History/Examination tab অনুযায়ী header search visibility bind হয়।
9. Dynamic modal datepicker ও modal focus প্রস্তুত হয়; server error থাকলে সংশ্লিষ্ট modal খোলে।

Print ও Settings scripts নিজের document-ready handler-এ তাদের page-এর controls খুঁজে কাজ করে।
Medicine-এর search result থেকে row এবং submitted fields তৈরির বিস্তারিত [Medicine JS Lifecycle](MEDICINE-JS-LIFECYCLE.md)-এ আছে।

### JS পরিবর্তনের নিয়ম

- Existing ESM ও jQuery pattern ব্যবহার করুন; field-এর কাজ তার নিজস্ব handler-এ রাখুন।
- প্রতিটি named function-এর আগে ১–২ লাইনে তার উদ্দেশ্য লিখুন; function ও variable-এর নাম কাজ বোঝাবে।
- একই কাজের handler এক জায়গায় রাখুন; ভিন্ন resource-এর কাজ condition দিয়ে জোর করে share করবেন না।
- Field validation-এর মূল দায়িত্ব Request-এর; browser-এ অপ্রয়োজনীয় দ্বিতীয় validation যোগ করবেন না।
- Current search, duplicate selection ও pending save-এর state guards বজায় রাখুন—এগুলো editor-এর lifecycle সামলায়।

## Catalog, Patient ও Doctor-এর সীমানা

- Medicine ও Generic/Dosage metadata আসে Pharmacy-এর existing Product relations থেকে; Prescription stock বা sales post করে না।
- Investigation আসে Hospital catalog থেকে; prescription selection কোনো Patient Investigation invoice তৈরি করে না।
- বাকি clinical নাম, medicine unit ও instruction থাকে `prescription_options`-এ; [Option Controller](app/Http/Controllers/PrescriptionOptionController.php) সাধারণ inline CRUD ব্যবহার করে।
- মিল থাকা search result থাকলে নতুন নাম তৈরির সুযোগ দেওয়া হয় না; সফল empty search-এর পরে manual entry/create পাওয়া যায়।
- Patient ও Doctor Hospital-এর records; নির্বাচিত branch এবং permission checks বজায় থাকে।
- New Patient modal Hospital-এর existing route/service ব্যবহার করে; Prescription নিজের service থেকে Hospital table লেখে না।

## Settings, Rx number ও print

[SettingController](app/Http/Controllers/SettingController.php), [SettingRequest](app/Http/Requests/SettingRequest.php) ও [settings view](resources/views/settings/index.blade.php) settings page পরিচালনা করে।
Defaults ও validation-এর সংজ্ঞা [config/settings.php](../../config/settings.php)-তে আছে।

- General/Glass আলাদাভাবে enable/disable করা যায়; model-এর `isCrudEnabled()` shared authorization pipeline-এ ব্যবহৃত হয়।
- General clinical sections এবং Glass PD/disclaimer/lens options-এর visibility controls আলাদা।
- দুইটির A4/A5/Custom paper, header এবং top/right/bottom/left margin আলাদা।
- Default pattern General-এর `RX-{SEQ:6}` এবং Glass-এর `GL-{SEQ:6}`।
- [RxNumberService](app/Services/RxNumberService.php) created event-এ নতুন record-এর primary ID থেকে sequence অংশ বসায়; পুরোনো Rx number edit-এ পাল্টায় না।
- [RxNumberPattern](app/Validation/RxNumberPattern.php) supported sequence/date/branch tokens যাচাই করে।
- [PrintComposer](app/View/Composers/PrintComposer.php) print geometry প্রস্তুত করে; barcode-এর value Patient ID, নিচে ID caption দেখায় না।

Print markup-এর জন্য [prints/layout.blade.php](resources/views/prints/layout.blade.php) দেখুন।
General content [prescriptions/show.blade.php](resources/views/prescriptions/show.blade.php)-তে এবং Glass content নিজস্ব show view-তে আছে।
Clinical name/duration-এর একই rendering থাকে [clinical-lines](resources/views/prints/clinical-lines.blade.php)-এ;
[History](resources/views/prints/history.blade.php), [Examination](resources/views/prints/examination.blade.php) ও
[Follow-up](resources/views/prints/follow-up.blade.php) তাদের নিজস্ব display সামলায়।
সব style root [app.css](../../resources/css/app.css)-তে; Bootstrap দিয়ে একই কাজ হলে দ্বিতীয় custom style যোগ করবেন না।

## পরিবর্তন করার আগে কোথা থেকে শুরু করবেন

| প্রয়োজন | প্রথমে দেখুন |
| --- | --- |
| Rx medicine add/edit/dose flow | `prescription-medicines.js`, `medicine-row.blade.php`, lifecycle guide |
| Clinical preset selection | `prescription-picker.js`, `picker.blade.php`, `PrescriptionOption` |
| Clinical duration/notes বা preview | `prescription-clinical-items.js`, `prescription-preview.js` |
| Save/validation বা missing persisted value | সংশ্লিষ্ট Request, General হলে `PrescriptionService` |
| Header/barcode/signature বা page size | `prints/layout.blade.php`, `PrintComposer`, `prescription-print.js` |
| Feature visibility/default | `SettingRequest`, `config/settings.php`, সংশ্লিষ্ট form/request |
| Dashboard/menu | `DashboardController`, `MenuComposer`, [menu partial](resources/views/partials/menu.blade.php) |

নতুন field হলে তার Blade `name`, Request rule, model fillable/cast এবং persistence path একসঙ্গে মিলিয়ে দেখুন।
Modal field-এ form association, existing record values এবং disabled/hidden input-এর submission আচরণ বজায় রাখুন।
একই operation সত্যিই একই হলে share করুন; resource/type দেখে আলাদা কাজ বেছে নেওয়া generic dispatcher বানাবেন না।
Database migration browser request-এ চালাবেন না; deployment-এর tenant migration command [DEPLOYMENT.md](../../DEPLOYMENT.md)-তে আছে।
