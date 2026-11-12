# Income ও Expense

TenantCore-এর চারটি আলাদা CRUD:

| Menu | URL | Table |
| --- | --- | --- |
| Income → Income | `/incomes` | `incomes` |
| Income → Income Heads | `/income-heads` | `income_heads` |
| Expenses → Expenses | `/expenses` | `expenses` |
| Expenses → Expense Heads | `/expense-heads` | `expense_heads` |

Head-এ শুধু Name ও Ordering আছে। Income Head ও Expense Head-এর আলাদা Controller, Model, Request, form ও list আছে; Type নির্বাচন করতে হয় না। একই নাম দুই catalog-এ আলাদাভাবে রাখা যায়। ব্যবহৃত Head delete করা যায় না, অন্য branch-এ ব্যবহৃত হলেও।

Entry-তে একটি Head, Amount, Date, Payment Type এবং optional Note থাকে। Payment Type-এ Cash থাকলে default সেটি নির্বাচিত থাকে। একাধিক debit/credit line অথবা ledger নির্বাচন করতে হয় না। Normal list থেকে edit/delete করা যায়; Head দিয়ে filter করা যায়। Date-এর জন্য project-এর shared picker ব্যবহৃত হয়।

Head tenant-এর সব branch-এর জন্য একই। Entry, list, edit ও delete বর্তমান branch অনুযায়ী। Branch বন্ধ থাকলে foundation-এর hidden default branch স্বয়ংক্রিয়ভাবে ব্যবহার হয়; form-এ branch বাছতে হয় না।

## Accounting থাকলে

Tenant-এর usable plan-এ `accounting.view` থাকলে নতুন Entry-এর সঙ্গে একই transaction-এ voucher হয়। Entry করা user-এর Accounting screen দেখার permission লাগবে না; Income/Expense permission যথেষ্ট। Accounting না থাকলে বা plan-এ সেটি না নিলে শুধু TenantCore entry save হয়।

- Income: Cash/Bank debit, default Other Income credit।
- Expense: default General Expense debit, Cash/Bank credit।

Head-এর নাম voucher reference-এ থাকে। Accounting → Account Modules-এ TenantCore-এর Income/Expense default ledger বদলানো যায়। Payment Type-এর Cash/Bank ledger Payment Accounts-এ থাকে; Cash-এর default mapping existing seeder তৈরি করে। একটি Head-এর জন্য আলাদা Accounting ledger বানানো বাধ্যতামূলক নয়।

Edit-এ একই voucher/number update হয়; delete-এ তার হিসাব reverse হয়। Accounting plan সরালেও আগে থেকে linked voucher-এর edit/delete মিলতে থাকে। Accounting নেওয়ার আগের unlinked Entry edit করলে পুরোনো হিসাব নতুন করে Accounting-এ ঢোকে না। আগে posting থাকা application থেকে Accounting module পুরোপুরি disable/remove করা এই plan-change flow নয়; সে অবস্থায় linked হিসাব বদলাবেন না।

## Code-এর দায়িত্ব

IncomeService ও ExpenseService তাদের নিজস্ব model এবং Accounting feature ঘোষণা করে। IncomeExpenseService শুধু দুই caller-এর একই transactional save/delete কাজ একবার রাখে; Income বা Expense দেখে কোনো conditional dispatcher নেই। Accounting sides ও ledger definitions TenantCore-এর নিজের [config/accounting.php](config/accounting.php)-তে থাকে।

Models native Eloquent relation, eager loading এবং branch scope ব্যবহার করে। Amount source table-এ decimal string হিসেবে থাকে; Accounting boundary-তে shared Money helper exact integer minor units-এ রূপান্তর করে। Float দিয়ে financial calculation হয় না।

## Production update

এই feature-এর additive migration: `2026_11_12_190235_create_income_expense_tables.php`। পুরোনো কোনো table reset বা transaction import করে না। নতুন tenant-এ একই migration চলে। Existing tenant-এ নতুন tables ও idempotent Accounting defaults আসবে।

Files manual upload করার পরে central Super Admin-এর `/core/deploy` থেকে **Run Update** দিন। অথবা project folder থেকে `php artisan tenants:migrate --force` চালান। Roles ও Plan Permissions-এ Income Heads, Income, Expense Heads ও Expenses-এর প্রয়োজনীয় view/create/update/delete grants দিন। Non-owner role-এ নতুন grants স্বয়ংক্রিয়ভাবে দেওয়া হয় না।

পূর্ণ deploy নির্দেশনা: [DEPLOYMENT.md](../../DEPLOYMENT.md)।

## Cash and Due reports

TenantCore: `/reports/daily`, `/reports/cash`, `/reports/monthly-cash`. Hospital: `/hospital/reports/cash-balance`. Pharmacy: `/pharmacy/reports/cash-balance`. Reports use the selected branch and print the displayed page. Combined balance is closing cash plus patient/customer receivables minus supplier payables. Module filtering works without Accounting. Original invoice receipts, dated collections and returns are counted separately. Opening cash comes from earlier recorded movements, not an independently entered cashbook opening balance. Historical edited/deleted records and cleared commission-payment states cannot be reconstructed; reports use retained corrected source data.
