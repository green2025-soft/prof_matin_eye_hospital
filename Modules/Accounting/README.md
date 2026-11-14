# Accounting

Tenant Accounting follows the application's Controller → Core BaseController → Model and Request → Core BaseRequest pattern. It is separate from Core's platform subscriptions, invoices and gateway payments.

## Screens

- `/accounting`: selected-branch dashboard and recent vouchers.
- `/accounting/chart-of-accounts`: all accounts in five root trees without pagination, local name/code search with ancestor paths, and expand/collapse controls.
- `/accounting/journal-entries`: manual Receipt, Payment, Journal and Contra vouchers, with same-page printing.
- `/accounting/account-modules`: module-filtered Hospital and Medicine/Optic posting mappings, AJAX ledger search and voucher type.
- `/accounting/payment-accounts`: Payment Type → active Cash/Bank/Wallet asset-ledger mapping.
- `/accounting/reports/ledger`: account activity, opening balance and running balance for a selected period.
- `/accounting/reports/trial-balance`: opening, period movement and closing debit/credit balances.
- `/accounting/reports/income-expense`: monthly and financial-year-to-date income, expenditure and surplus/deficit.
- `/accounting/reports/balance-sheet`: month-end assets, liabilities and equity compared with the prior financial year-end.
- `/accounting/reports/cash-flow`: operating, investing, financing and unclassified cash movements.
- `/accounting/reports/cash-bank-book`: separate cash and bank running balances, including internal transfers.
- `/accounting/reports/changes-in-equity`: opening equity, explicit movements and unclosed earnings for four comparison periods.
- `/accounting/reports/receipts-payments`: external receipts/payments and cash reconciliation.

Accounts and mappings are tenant-wide. Vouchers belong to their actual branch. A transfer creates one balanced voucher in each branch; its destination write never changes the request's selected branch. Normal voucher lists and route bindings remain restricted to that selected branch.

The chart remembers expanded accounts and open root groups in browser session storage, scoped to the current login session. Refreshing or returning from create/edit in the same tab restores that state; search does not overwrite it. Compact group rows provide Add group / Add ledger links with the parent preselected. A child of a level-four group defaults to a voucher ledger.

## Posting ownership

Hospital and Pharmacy prepare their own financial components in their existing services. They call Accounting's `JournalPosting` contract after source totals and stock costs are final, inside the same tenant transaction and before SMS is queued. Operators need permission for their source action; automatic posting does not also require manual Accounting permissions.

Posting definitions also belong to their source module: `Modules/Hospital/config/accounting.php` and `Modules/Pharmacy/config/accounting.php`. Their existing providers register those definitions with native `mergeConfigFrom()` under `accounting.integrations`. Accounting's own config retains the common chart defaults and an initially empty integration map. The generic seeder reads that merged map; it does not scan module files or contain source-specific dispatch. Configuration caching retains all registered definitions.

`JournalService::sync()` performs the same operation for every caller: resolve the feature mapping, use the Payment Type ledger when supplied, validate the balanced entry, and persist its header and lines. It contains no Purchase/Sale/clinical type dispatcher. `save()` owns manual voucher input, while both paths share the unchanged final persistence operation.

Money is stored as exact integer paisa in `total`, `debit` and `credit`; display/input conversion uses the existing Money helper. There are no float calculations, copied ancestor IDs or daily balance cache tables. Account relationships remain native Eloquent relations.

Automatic identity is the native source morph pair plus feature. Repeated posting replaces that voucher's saved lines while retaining its number. Source cancellation marks its existing vouchers void; saved lines remain available for review. Commission Unpaid reverses only the commission feature. Automatic and voided vouchers cannot be manually edited or deleted.

## New transactions only

There is no historical invoice import or accounting backfill. A new source can create its first voucher; editing an older source without a voucher cannot. Updates to an already linked source remain synchronized. New collections, refunds and commission payments are new financial actions even when their original invoice is older.

A zero-value automatic voucher preserves enrollment so a later positive edit can be synchronized. Void dates are retained, and repeated Paid → Unpaid → Paid commission actions reuse their own feature safely.

Existing stock, receivables and Cash/Bank balances can be introduced explicitly with a manual opening Journal voucher and the default Opening Balance equity ledger. Installing this module does not reconstruct those balances from old invoices.

## Reports and printing

Reports read only unvoided vouchers for the trusted selected branch. Disabled ledger accounts remain reportable when they have saved activity. Dates refer to the voucher's entry date. All totals and running balances use exact integer paisa, including values aggregated by the database.

Ledger and Cash & Bank Book use From/To dates. The six financial statements use a report month and hierarchy detail level. Trial Balance splits debit/credit at the posting ledger before summing groups. Income–Expenditure compares the selected month with financial-year-to-date; Balance Sheet compares month-end with the preceding June year-end. Changes in Equity compares the current month, previous month, current financial year and previous financial year. Bangladesh financial years start on July 1. Balance Sheet and equity include cumulative unclosed income less expenses; there is no automatic fiscal-year closing entry.

Chart of Accounts stores optional `system_key` Cash in hand / Cash at bank roles and `cash_flow_activity`. Descendants inherit the nearest assigned tag. Cash, bank and mobile-banking default groups receive initial tags through the additive `2026_11_12_172626` migration; new tenants receive them in default seeding. Later seeds preserve operator changes. Multiple bank groups can share the same bank role. No account number or name is guessed inside report calculations.

Cash Flow and Receipts & Payments use actual cash-bearing journals. Pure cash-to-bank transfers stay in the book and do not inflate external receipts/payments. Signed counterpart deductions remain signed. Ambiguous compound cash movements spanning different activities remain Unclassified until their account classification is resolved. Each statement reconciles opening cash plus net movement to closing cash.

Each report has its own controller and Blade view, protected by the grouped Accounting middleware and `accounting-reports.view`. `ReportService` shares only unchanged account-activity queries and exact arithmetic. TenantCore owns the unchanged company/branch letterhead composer and header partial used by both Accounting and Pharmacy.

Print uses the displayed report on the same page, with the organization pad, repeated table headings and signature footer. The Ledger page-size control offers All when the operator wants every filtered row printed. Financial statements display the complete matching ledger totals.

The updated `world_vision_enterprice` Accounting reference contains eight reports; all eight have independent controllers and named report routes here. Its separate Inventory reports are outside this Accounting scope.

## Module dashboard ordering

Tenant administrators with Settings view/update permission can use **Arrange modules** on `/module-dashboard`, or open `/settings?group=general#module-order-heading`. Each module keeps its default name; an optional Display name changes its dashboard label for everyone in that tenant, and clearing it restores the default. Smaller Ordering values appear first. Blank or equal ranks retain native module ordering. Saved names and order change neither module/provider identities nor account/plan permissions. The existing General setting stores both maps; no new table or migration is required.

## Financial links

| Source | Accounting treatment |
| --- | --- |
| Consultation / Investigation / Operation | Initial receipt, patient due, discount and the owning clinical income. |
| Due collection | Receipt against patient receivable. |
| Refund | Cash refund and/or receivable cancellation against refund expense. |
| Commission | Expense when Paid; reversal when Unpaid. Its configured default settlement ledger is used. |
| Purchase | Inventory cost, initial payment and supplier payable. Existing VAT capitalization is preserved. |
| Sale | Receipt/due, discount, revenue, output VAT, cost of goods sold and inventory. |
| Returns | Cash/due relief, tax reversal, actual returned stock cost and any purchase valuation difference. |
| Customer / Supplier payment | Allocated settlement and the saved settlement discount. |
| Opening / Adjustment / Wastage | Actual stock value against opening equity, gain/loss or wastage expense. |
| Branch transfer | Inventory and clearing entries in each actual branch. |
| Shortage cost correction | Its own stock-movement voucher, independent of the invoice voucher. |

Medicine and Optic mappings are independent. Refund and Commission have no Payment Type input, so their own mapping's default settlement ledger applies. A selected Payment Type with no mapping refuses a paid transaction until its ledger is configured.

## Defaults and production update

The native module skeleton was generated with `php artisan module:make Accounting`. Accounting has an explicit tenant migration path. No Accounting migration is registered globally in its provider, so central migrations do not create `acc_` tables.

New tenants receive Accounting defaults through the root DatabaseSeeder after Cash and their default branch exist. Existing tenants receive the same idempotent defaults through `DatabaseInstaller::seedTenantDefaults()` during adoption or the configured `multitenancy.tenant_seeders` step of `tenants:migrate`. Existing accounts, account selections and enabled/disabled choices are preserved. Neither path runs Pharmacy catalog seeding on an existing tenant.

For the production server, take the normal database/file backup. From the existing application folder, for example `eye_app/`, run `php artisan down`, then upload the application changes and built assets. Continue from that application folder:

```bash
composer install --no-dev --optimize-autoloader
php artisan optimize:clear --except=cache
php artisan tenants:migrate --pretend --force
```

Review the pending migration preview before applying it. This release's Accounting migration only adds `acc_` tables. The repository also retains the previously approved `2026_11_03` Pharmacy transaction-reset migration; it must already be applied on an existing live installation. If that reset appears in the preview, resolve the server's migration state before continuing so live transactions are preserved.

```bash
php artisan platform:migrate --force
php artisan tenants:migrate --force
php artisan optimize
php artisan up
```

Upload the application changes and the built `public/build/` assets as one release while maintenance is active. With separate shared-hosting web and application folders, copy `public/build/` into the actual web root's `build/` directory. Keep the production `.env`, APP_KEY, tenant credentials and uploaded storage. Rebuild caches on the server rather than uploading local cache files.

`tenants:migrate --force` synchronizes the tenant permissions and Accounting defaults after schema migration. Core Plan Permissions and tenant roles can then grant the Accounting module and its separate Accounts, Vouchers and Mappings actions. Cash is mapped automatically when the tenant already has its default Cash Payment Type; configure other Payment Types on Payment Accounts.

No cron or queued Accounting worker is required. Journal failures roll back their source transaction; SMS continues to use its existing after-commit flow.
