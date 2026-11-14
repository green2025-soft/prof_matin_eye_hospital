# JavaScript edit করার নির্দেশনা

এই project-এ **jQuery + ESM** ব্যবহার হয়। Blade মূল HTML ও form তৈরি করে; JS search, modal, item editing, keyboard এবং live preview চালায়। Permission, database write এবং চূড়ান্ত হিসাব Laravel-এর দায়িত্ব।

Reference: `pirojpur-pharma_optics/application/views/admin/operation_bill/create.php`-এর `<script>`। সেখানকার সহজ **field → named function → event → calculation** flow অনুসরণ করা হয়েছে। Inline script, floating-point টাকার হিসাব অথবা resource দেখে shared code-এর আচরণ বদলানো অনুসরণ করা হয়নি।

## প্রথমে কোন ফাইল পড়বেন

| কাজ | মূল ফাইল / entry point |
| --- | --- |
| Common Ajax Select2, date/time, toast, form submission | [app.js](resources/js/app.js) |
| Hospital Consultation / Investigation / Operation হিসাব | `app.js` → `initializeConsultationForm`, `initializePatientInvestigationForm`, `initializePatientOperationForm` |
| Hospital Refund / Due Collection preview | `app.js` → `initializeRefundForm`, `initializeDueCollectionForm` |
| New Patient modal | `app.js` → `loadPatientModal`, `submitPatientModal` |
| Production web updater | `app.js` → `initializeDeploymentForm` |
| Pharmacy Purchase / Sale / Return / Stock / Payment | [pharmacy.js](resources/js/pharmacy.js) |
| Pharmacy Add/Edit/Cancel item | [pharmacy-trade-editor.js](resources/js/pharmacy-trade-editor.js) |
| Purchase pack size / unit / প্রতি-item VAT | `pharmacy.js` → `initializePurchaseEntry` |
| Accounting voucher / Chart of Accounts | [accounting.js](resources/js/accounting.js) |
| Prescription form coordination | [prescription.js](resources/js/prescription.js) |
| Prescription medicine / dose / duration | [prescription-medicines.js](resources/js/prescription-medicines.js) |
| বাকি Prescription কাজ | নিচের সম্পূর্ণ file map |

Function নাম দিয়ে editor-এর **Go to Symbol** অথবা search ব্যবহার করুন। পুরো ফাইল ওপর থেকে নিচ পর্যন্ত পড়ার প্রয়োজন নেই।

`initializeDeploymentForm` owns the central production updater. Run Update starts fresh for uploaded code; Continue Update resumes the same upload. The first POST sends CSRF and the clicked button's `restart` value; later requests send only CSRF. It sends one request at a time while the server reports `running`, appends new result rows once, and restores Continue Update after a request failure. Loading the page never starts an update.

## JS load হওয়ার flow

```text
Blade layout-এর @vite
  → resources/js/app.js
    → jquery.js: একই jQuery instance
    → Bootstrap / Flatpickr / Select2 / Toastr
    → Pharmacy / Prescription / Accounting-এর ESM imports
    → DOM ready: সংশ্লিষ্ট form-এর initializer
    → event → named operation → preview / form value update
    → native form submission অথবা নির্দিষ্ট AJAX save
```

[vite.config.js](vite.config.js)-এ JS entry একটি: `resources/js/app.js`। Module-গুলোর জন্য দ্বিতীয় Vite build অথবা Blade-এ একই script আবার যোগ করার প্রয়োজন নেই। Import করা file-এ ready callback নিবন্ধিত হয়; DOM ready হওয়ার সময় plugin ও common helper পাওয়া যায়।

Dynamic HTML আসার পরে শুধু নতুন অংশ initialize হয়। যেমন Patient modal-এর fields load হলে `window.initAjaxSelects(content)` ও `window.initDateTimePickers(content)` চলে। সম্পূর্ণ document আবার initialize করবেন না।

## প্রতিটি file-এর একই সহজ pattern

1. Imports।
2. সত্যিকার common constants / small functions।
3. Form বা component-এর initializer।
4. Initializer-এর শুরুতে প্রয়োজনীয় jQuery fields ও state।
5. কাজ অনুযায়ী named functions, প্রতিটির দরকারি ১–২ লাইনের comment।
6. ছোট event bindings; শেষে initial render।

বর্তমান `prescription-settings.js`-এর উদাহরণ:

```js
const $paper = $group.find('.prescription-paper-size');
const $dimensions = $group.find('.prescription-custom-paper');

function updatePaperSize() {
    const custom = $paper.val() === 'custom';
    $dimensions.prop('hidden', !custom).find(':input')
        .prop('required', custom).prop('disabled', !custom);
}

$paper.on('change.prescriptionPaper', updatePaperSize);
updatePaperSize();
```

`$form`, `$row`, `$input` মানে jQuery object। Plain `form`, `input` মানে browser/plugin-এ প্রয়োজনীয় আসল element। DOM পড়া ও পরিবর্তনে `.find()`, `.val()`, `.text()`, `.prop()`, `.on()` ব্যবহার করুন। `requestSubmit()`, `setCustomValidity()`, Fullscreen এবং Flatpickr/Popper-এর element API তাদের নিজস্ব API হিসেবেই ব্যবহৃত হয়।

Event-এ `this` দরকার হলে `function` ব্যবহার করুন। Arrow function দিয়ে `this` বদলে ফেলবেন না। Form-এর বাইরে নতুন helper/state রাখবেন শুধু সত্যিই অন্য caller ব্যবহার করলে।

Field change হলে প্রথমে unit/price/value পরিবর্তন করুন, তার পরে totals ও preview refresh করুন। আলাদা handler-এ থাকলে তাদের binding order-ও দেখতে হবে; পুরোনো value দিয়ে আগে totals render করলে পরের input না দেওয়া পর্যন্ত ভুল preview থাকতে পারে।

## সমস্ত JS file-এর map

| File | দায়িত্ব |
| --- | --- |
| [app.js](resources/js/app.js) | Common plugins, navigation, submit lock, Patient modal, tenant/domain/permission forms, পৃথক Hospital form হিসাব ও common invoice rows |
| [jquery.js](resources/js/jquery.js) | একবার jQuery import; একই `$` / `jQuery` window ও ESM-এ পাওয়া যায় |
| [money.js](resources/js/money.js) | `parseAmount`, `formatAmount`, `roundRatio`: exact integer minor-unit হিসাব |
| [datepicker-year.js](resources/js/datepicker-year.js) | Flatpickr year dropdown তৈরি, range বদলানো ও destroy |
| [patient-age.js](resources/js/patient-age.js) | Age অথবা Date of Birth input mode-এর field state |
| [select2-empty-search.js](resources/js/select2-empty-search.js) | বর্তমান সফল search-এ কোনো match না থাকলেই Create অনুমতি; stale response প্রত্যাখ্যান |
| [pharmacy.js](resources/js/pharmacy.js) | Purchase-এর pack/unit/VAT, Sale-এর price/stock flow, returns, stock rows, product/customer/payment form |
| [pharmacy-trade-editor.js](resources/js/pharmacy-trade-editor.js) | Common item editor, row snapshot/restore, compact list, Add/Update/Cancel ও keyboard/focus operations |
| [accounting.js](resources/js/accounting.js) | Journal line editor, debit/credit preview; account tree search/expand এবং session state |
| [income-expense.js](resources/js/income-expense.js) | Income/Expense voucher-এ Head/Amount row যোগ-সরানো, stable input index এবং exact BigInt total preview |
| [prescription.js](resources/js/prescription.js) | Prescription page startup, History/Follow-up coordination, modal header search ও validation focus |
| [prescription-picker.js](resources/js/prescription-picker.js) | একই ধরনের clinical preset search, selection, grid, inline Create |
| [prescription-clinical-items.js](resources/js/prescription-clinical-items.js) | নির্বাচিত clinical নামের duration/unit/note row ও `section_details` synchronization |
| [prescription-preview.js](resources/js/prescription-preview.js) | Pad-এ History, examination ও Follow-up-এর preview/remove/focus |
| [prescription-examination.js](resources/js/prescription-examination.js) | Measurement দেখানো/লুকানোর control |
| [prescription-medicines.js](resources/js/prescription-medicines.js) | Rx medicine নির্বাচন, dose, schedule, unit, duration, edit/remove ও row preview |
| [prescription-application-dose.js](resources/js/prescription-application-dose.js) | Dosage Type অনুযায়ী Tablet/Drop/Gel ইত্যাদির dose input ও frequency |
| [prescription-keyboard.js](resources/js/prescription-keyboard.js) | Rx search, palette navigation এবং dose → খাবারের সময় → unit → duration focus |
| [prescription-palette.js](resources/js/prescription-palette.js) | Dose/Unit-এর একই floating palette open/close, click/arrow/Escape behavior |
| [prescription-instructions.js](resources/js/prescription-instructions.js) | Medicine instruction নির্বাচন, meal timing ও অনুমোদিত inline Create |
| [prescription-header-dropdown.js](resources/js/prescription-header-dropdown.js) | Header search dropdown input-এর নিচে স্থির রাখা |
| [prescription-print.js](resources/js/prescription-print.js) | Patient barcode ও prescription paper/margin-এর print rules |
| [prescription-settings.js](resources/js/prescription-settings.js) | General/Glass-এর আলাদা paper-size এবং Custom dimension fields |

Medicine-এর বিস্তারিত flow: [MEDICINE-JS-LIFECYCLE.md](Modules/Prescription/MEDICINE-JS-LIFECYCLE.md)। Module-এর বাকি কাজ: [Prescription README](Modules/Prescription/README.md)।

## Common এবং resource-এর নিজস্ব কাজ

Common function একই inputs-এর অর্থে একই operation করবে। যেমন decimal conversion, palette বন্ধ করা, keyboard choice navigation এবং edit snapshot restore।

Purchase-এর unit/expiry/VAT এবং Sale-এর available stock/price selection নিজের implementation-এ থাকে। Shared editor Add/Edit/Cancel-এর একই কাজ করে; owner product selection-এর নিয়ম নিজেই চালায়। Shared function-এ `type === 'purchase'`, `type === 'sale'` দিয়ে ভিন্ন কাজ ঢোকাবেন না। একই dispatch configuration map অথবা অনুমান করা field নামের মধ্যেও রাখবেন না।

Pharmacy editor-এর `pharmacy:itemCreated` ও `pharmacy:itemRestored` event-এ Purchase তার নিজস্ব VAT preference/unit label বসায়। Unit keyboard event-এ owner তার নিজের unit control নির্বাচন করে; common editor focus ও native chooser-এর একই operation চালায়। Pack modal, pack save এবং Alt+U handler `initializePurchaseEntry`-তেই আছে।

Hospital-এও তিনটি initializer আলাদা। Consultation doctor-এর visit fee পড়ে; Operation single/both-eye rate পড়ে; Investigation selected tests ও প্রতিটি test-এর eye/rate রাখে। শুধু discount conversion, Paid default এবং Due display একই operation হিসেবে common।

একই selector/result প্রয়োজন হলে initializer-এ একবার রাখুন। শুধু iteration-এর syntax বদলে `.map()`-কে `.each()` করা optimization নয়। একবার সংগ্রহ করা result আবার DOM থেকে খুঁজবেন না।

## সাধারণ পরিবর্তন কীভাবে করবেন

### একটি field বা item column পরিবর্তন

1. Owning Blade-এর field/template ও তার `name` খুঁজুন। `rg -n "field_name" Modules resources/js` কাজে আসবে।
2. উপরের map থেকে owning initializer খুলুন। Header field হলে `$form`, item field হলে সংশ্লিষ্ট `$row` ব্যবহার করুন।
3. Add, Edit এবং Cancel—তিন অবস্থাতেই field দেখুন। Pharmacy Cancel পুরোনো value, option, validity ও datepicker state ফেরায়।
4. Submit হওয়া field-এর `name` এবং backend Request-এর নাম এক রাখুন। নতুন database column-এর জন্য [DATABASE-CHANGES.md](DATABASE-CHANGES.md) অনুসরণ করুন।

### Amount / Discount / VAT পরিবর্তন

`money.js`-এর `parseAmount('12.50')` ফল `1250n`; `formatAmount(1250n)` ফল `'12.50'`। Invalid input-এ parser `null` দেয়। Existing owner তার উপযুক্ত fallback রাখে। `roundRatio` ব্যবহার করলে ভগ্নাংশের টাকা একই নিয়মে round হয়।

টাকার জন্য `parseFloat`, `Number` অথবা `toFixed` দিয়ে নতুন হিসাব করবেন না। Percentage, unit factor, inclusive VAT এবং quantity হিসাব নিজ নিজ owner-এ থাকে। Catalog-এর decimal price ও submitted display value-কে stored minor-unit number ভেবে সরাসরি যোগ করবেন না।

Hospital-এ saved invoice-এর source না বদলালে পুরোনো amount থাকে। Patient type ও eye type radio-এর **`:checked`** value পড়তে হবে। `$('input[name="eye_type"]').val()` প্রথম radio-এর value দেয়।

### AJAX search বা Create পরিবর্তন

Common catalog select `window.initAjaxSelects(root)` ব্যবহার করে। `data-value-field` ও `data-label-field` response-এর কোন key Select2-এর `id` / `text` হবে তা বলে; অন্য returned metadata selected item-এ থাকে। Request parameters এবং response keys owning selector-এর contract অনুযায়ী রাখুন।

`createEmptySearchGuard`-এর `begin` → request → `complete` flow বাদ দেবেন না। User অন্য নাম লিখলে, request fail করলে অথবা matches/later pages থাকলে নতুন item create হয় না। শুধু বর্তমান প্রথম page-এর সফল empty response Create খুলবে।

একটি request pending থাকা অবস্থায় নতুন selection হলে আগের response পরে আসতে পারে। Request identity, abort এবং current-selection guard এই জন্য আছে। `.done()`-এ বর্তমান input-এর সঙ্গে সম্পর্ক যাচাই রেখে UI update করুন।

### Keyboard বা modal পরিবর্তন

Rx keyboard-এর owning file `prescription-keyboard.js`; Dose/Unit choice list-এর common open/close `prescription-palette.js`। Medicine type-এর input `prescription-application-dose.js`। Binding copy করে medicines file-এ আরেকবার রাখবেন না।

Delegated `.on('event.namespace', selector, handler)` dynamic rows-এর জন্য ব্যবহার করুন। একই modal খোলার সময় একই handler আবার bind করবেন না। `.off()` প্রয়োজন হলে নিজের namespace-এই সীমাবদ্ধ রাখুন।

Close/Cancel-এর পরে আগের input-এ focus ফেরানো, pending save-এর সময় দ্বিতীয় save বন্ধ রাখা এবং existing keyboard sequence অক্ষত রাখুন।

### Notification এবং validation

```js
window.showToast('Saved successfully.', 'success');
window.showToast('This product has no available stock.', 'warning');
```

Success/error/warning/info feedback Toastr-এ যায়। Field error inline থাকে। Loading state এবং delete confirmation নিজ নিজ জায়গায় থাকে। Server থেকে আসা label/message DOM-এ বসাতে `.text()` ব্যবহার করুন; HTML string-এর মধ্যে user text জুড়বেন না।

## পরিবর্তনের পরে build ও server update

Project root-এ:

```powershell
node --check resources/js/app.js
npm run build
```

`node --check`-এ পরিবর্তিত JS file-এর path দিন। Build import/export ও asset graph যাচাই করে। Browser-এ সংশ্লিষ্ট Add/Edit/Cancel, keyboard, search এবং amount preview যাচাই করুন।

Production-এ source পরিবর্তনের সঙ্গে নতুন `public/build` upload করুন। শুধু `.js` source upload করলে Vite-bundled page বদলাবে না। Shared hosting-এ public files অন্য root-এ থাকলে সেই **actual public root**-এর `build` folder ও `manifest.json` একসঙ্গে বদলাতে হবে। পুরোনো `public/hot` production-এ রাখবেন না। এই JS পরিবর্তনে tenant migration লাগবে না।

Deployment-এর পূর্ণ নির্দেশনা: [DEPLOYMENT.md](DEPLOYMENT.md)।
