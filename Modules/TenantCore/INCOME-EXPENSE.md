# Income ও Expense

TenantCore-এর চারটি আলাদা CRUD:

| Menu | URL | Table |
| --- | --- | --- |
| Income → Income | `/incomes` | `incomes`, `income_items` |
| Income → Income Heads | `/income-heads` | `income_heads` |
| Expenses → Expenses | `/expenses` | `expenses`, `expense_items` |
| Expenses → Expense Heads | `/expense-heads` | `expense_heads` |

Head-এ শুধু Name ও Ordering আছে। Income Head ও Expense Head-এর আলাদা Controller, Model, Request, form ও list আছে; Type নির্বাচন করতে হয় না। একই নাম দুই catalog-এ আলাদাভাবে রাখা যায়। ব্যবহৃত Head delete করা যায় না, অন্য branch-এ ব্যবহৃত হলেও।

একটি voucher-এ Date, Payment Type এবং optional Note থাকে। নিচে **Add head** দিয়ে একাধিক Head/Amount সারি যোগ বা সরানো যায়। UI মোট দেখায়; server প্রতিটি Amount থেকে exact মোট হিসাব করে। অন্তত একটি সারি থাকতে হবে। Payment Type-এ Cash থাকলে default সেটি নির্বাচিত থাকে। একাধিক debit/credit ledger নির্বাচন করতে হয় না। Normal list থেকে একই voucher edit/delete করা যায়; Head filter যেকোনো item-এর Head মিলিয়ে voucher দেখায়। Date-এর জন্য project-এর shared picker ব্যবহৃত হয়।

Head tenant-এর সব branch-এর জন্য একই। Entry, list, edit ও delete বর্তমান branch অনুযায়ী। Branch বন্ধ থাকলে foundation-এর hidden default branch স্বয়ংক্রিয়ভাবে ব্যবহার হয়; form-এ branch বাছতে হয় না।

## Accounting থাকলে

Tenant-এর usable plan-এ `accounting.view` থাকলে নতুন Entry-এর সঙ্গে একই transaction-এ voucher হয়। Entry করা user-এর Accounting screen দেখার permission লাগবে না; Income/Expense permission যথেষ্ট। Accounting না থাকলে বা plan-এ সেটি না নিলে শুধু TenantCore entry save হয়।

- Income: Cash/Bank debit, default Other Income credit।
- Expense: default General Expense debit, Cash/Bank credit।

Head-এর নাম voucher reference-এ থাকে। Accounting → Account Modules-এ TenantCore-এর Income/Expense default ledger বদলানো যায়। Payment Type-এর Cash/Bank ledger Payment Accounts-এ থাকে; Cash-এর default mapping existing seeder তৈরি করে। একটি Head-এর জন্য আলাদা Accounting ledger বানানো বাধ্যতামূলক নয়।

Edit-এ একই voucher/number update হয়; delete-এ তার হিসাব reverse হয়। Accounting plan সরালেও আগে থেকে linked voucher-এর edit/delete মিলতে থাকে। Accounting নেওয়ার আগের unlinked Entry edit করলে পুরোনো হিসাব নতুন করে Accounting-এ ঢোকে না। আগে posting থাকা application থেকে Accounting module পুরোপুরি disable/remove করা এই plan-change flow নয়; সে অবস্থায় linked হিসাব বদলাবেন না।

## Code-এর দায়িত্ব

IncomeService ও ExpenseService তাদের নিজস্ব header/head model এবং Accounting feature ঘোষণা করে। IncomeExpenseService একই tenant transaction-এ header, item rows এবং optional Accounting journal save করে; edit-এ header/journal ID রেখে item set বদলায়। Income বা Expense দেখে কোনো conditional dispatcher নেই। Accounting sides ও ledger definitions TenantCore-এর নিজের [config/accounting.php](config/accounting.php)-তে থাকে।

Request-এর related Head IDs এক query-তে যাচাই হয়। List/form-এ `items.head` eager load হয়। Cash report সরাসরি item থেকে native parent/head relation eager load করে; প্রতিটি row-এর জন্য আলাদা query চলে না। একই voucher reference-এ প্রতিটি Head-এর allocation আলাদা দেখা যায়।

Service validated item amounts আবার format করে না; exact server total-এর জন্য একবার minor units যোগ করে। Journal posting প্রয়োজন হলেই শুধু Head names query হয়। Report source native `eachById(..., 200)` দিয়ে একেকটি item নেয়, তাই service-এ chunk callback-এর মধ্যে আলাদা nested loop নেই; native chunking ও eager loading বজায় থাকে।

দুই form-এর একই row/table Blade partial-এ আছে। [income-expense.js](../../resources/js/income-expense.js) jQuery ESM দিয়ে Add/Remove, stable field indices এবং BigInt total preview চালায়; picker-এর জন্য existing `window.initAjaxSelects()` ব্যবহার করে।

Models native Eloquent relation, eager loading এবং branch scope ব্যবহার করে। Amount source table-এ decimal string হিসেবে থাকে; Accounting boundary-তে shared Money helper exact integer minor units-এ রূপান্তর করে। Float দিয়ে financial calculation হয় না।

## Production update

মূল migration: `2026_11_12_190235_create_income_expense_tables.php`। Multi-head migration `2026_11_14_121500_add_items_to_income_and_expense_entries.php` নতুন item tables তৈরি করে, পুরোনো প্রতিটি header-এর Head/Amount একটি child row-এ নেয়, তারপর header-এর পুরোনো `head_id` সরায়। Header ID, মোট, তারিখ ও journal link বদলায় না। কোনো table reset হয় না; নতুন tenant-এও একই migrations চলে। একাধিক Head-এর তথ্য single-head schema-তে হারানো ছাড়া ফেরানো যায় না বলে migration rollback বন্ধ; পরবর্তী পরিবর্তনে forward migration ব্যবহার করুন।

Files manual upload করার পরে central Super Admin-এর `/core/deploy` থেকে **Run Update** দিন। অথবা project folder থেকে `php artisan tenants:migrate --force` চালান। Roles ও Plan Permissions-এ Income Heads, Income, Expense Heads ও Expenses-এর প্রয়োজনীয় view/create/update/delete grants দিন। Non-owner role-এ নতুন grants স্বয়ংক্রিয়ভাবে দেওয়া হয় না।

নতুন form script-এর জন্য local `npm run build` করে generated `public/build`-ও upload করুন। Public files আলাদা web root-এ থাকলে তার `build` folder-এ এই files থাকবে। Migration শেষ হওয়ার পরে নতুন form ব্যবহার করুন।

পূর্ণ deploy নির্দেশনা: [DEPLOYMENT.md](../../DEPLOYMENT.md)।

## Cash and Due reports

`/reports/daily` shows received income and paid expenses in two separate tables. All manual Income entries combine into one General Income row; all manual Expense entries combine into one General Expense row. Hospital, Medicine and Optic keep their own transaction heads. Every supported head remains visible with `0.00` when there are no entries, followed by Total Income, Total Expense and Balance (Income minus Expense). Individual transactions are not listed. Each owning cash-report source declares its labels and received/paid side through `heads()`; emitted event labels use those same names. From date and To date default to the current month through today. Module and selected-branch filters apply to both tables. Unpaid invoices remain due, so they do not increase this report's cash totals.

`/reports/cash` groups dated receipts/payments into separate tables for each module and head, such as Consultation, Investigation and General Expense. Each table shows Date, Reference, Details and Amount, followed by that head's exact subtotal from the shared report totals. Only heads with actual in-period entries appear; a real zero-paid invoice still counts as an entry. Medicine and Optic remain separate. Pharmacy parties without native name relations retain their customer/supplier IDs. Overall Total Income, Total Expense and Balance (Income minus Expense) match `/reports/daily` for the same date range, branch and module; earlier cash and unpaid dues are excluded from this period balance. Both controllers use the same `CashReportService` totals and `net`; only Cash opts into the individual `entries` list. Print uses the displayed page, repeats each table's head and column headings, and needs no separate print route.

`/reports/monthly-cash` selects Year, Month and Module and shows every calendar date, including zero-activity dates. Columns are Date, Income, Expense and that day's Balance (Income minus Expense). The footer and shared period-totals partial show monthly Income, Expense and net, matching Daily and Cash for the full selected month and same branch/module. Month boundaries include leap years. Opening cash and unpaid dues are not part of these period totals.

Daily, Cash and Monthly explicitly use `includeOpening: false`; each source limits its SQL query to the selected dates. Hospital/Pharmacy cash-balance reports retain historical opening calculations. Relations are eager-loaded in bounded chunks. Repeated patient batches across separate transaction families are not per-row lookups; no cross-request financial-result cache is introduced.

`CashReportService` returns raw `headCatalog`, keyed `headTotals`, raw optional `entries`, `openingSnapshot` and sparse `days` keyed by recorded date. It performs exact financial aggregation and cumulative cash/due calculations only. Blade renders zero heads and calendar gaps, groups/sorts transaction rows and formats minor units with `moneyDecimal()`. Empty dates carry the previous financial snapshot; Blade does not add/subtract money. The service does not prebuild display rows or format each transaction.

TenantCore: `/reports/daily`, `/reports/cash`, `/reports/monthly-cash`. Hospital: `/hospital/reports/cash-balance`. Pharmacy: `/pharmacy/reports/cash-balance`. Reports use the selected branch and print the displayed page. Module cash-balance reports retain opening/closing cash and due balances; their Combined balance is closing cash plus patient/customer receivables minus supplier payables. Module filtering works without Accounting. Original invoice receipts, dated collections and returns are counted separately. Opening cash comes from earlier recorded movements, not an independently entered cashbook opening balance. Historical edited/deleted records and cleared commission-payment states cannot be reconstructed; reports use retained corrected source data.
