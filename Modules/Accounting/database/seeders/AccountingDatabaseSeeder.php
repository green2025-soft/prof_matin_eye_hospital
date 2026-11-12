<?php

namespace Modules\Accounting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Accounting\Models\AccountHead;
use Modules\Accounting\Models\AccountModule;
use Modules\Accounting\Models\ModuleEntryAccount;
use Modules\Accounting\Models\PaymentAccount;
use Modules\Accounting\Models\VoucherType;
use Modules\TenantCore\Models\PaymentType;

class AccountingDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        (new AccountHead)->getConnection()->transaction(function (): void {
            $this->seedAccounts();
            $this->seedVouchers();
            $this->seedMappings();

            $cashType = PaymentType::where('name', 'Cash')->first();
            if ($cashType !== null) {
                PaymentAccount::firstOrCreate(['payment_type_id' => $cashType->id], [
                    'account_head_id' => AccountHead::where('code', '111101')->firstOrFail()->id,
                ]);
            }
        });
    }

    private function seedAccounts(): void
    {
        // Seed identities are explicit; ordinary CRUD generates identities from the locked parent.
        AccountHead::withoutEvents(fn () => AccountHead::unguarded(function (): void {
            $roots = [
                ['100000', 'Assets', 'asset'],
                ['200000', 'Liabilities', 'liability'],
                ['300000', 'Equity', 'equity'],
                ['400000', 'Income', 'income'],
                ['500000', 'Expenses', 'expense'],
            ];
            $accounts = collect();
            $reportRoles = config('accounting.report_roles', []);
            $cashFlowActivities = config('accounting.cash_flow_activities', []);
            foreach ($roots as [$code, $name, $type]) {
                $accounts->put($code, AccountHead::firstOrCreate(['code' => $code], [
                    'name' => $name,
                    'type' => $type,
                    'depth' => 1,
                    'is_transaction' => false,
                    'active' => true,
                ]));
            }

            $children = [
                ['110000', 'Current assets', '100000'],
                ['111000', 'Cash and bank', '110000'],
                ['111100', 'Cash accounts', '111000'],
                ['111101', 'Cash in hand', '111100'],
                ['111200', 'Bank accounts', '111000'],
                ['111201', 'Bank', '111200'],
                ['111300', 'Mobile banking accounts', '111000'],
                ['111301', 'Mobile banking', '111300'],
                ['112000', 'Receivables', '110000'],
                ['112100', 'Patient and customer receivables', '112000'],
                ['112101', 'Patient receivable', '112100'],
                ['112102', 'Customer receivable', '112100'],
                ['113000', 'Stock assets', '110000'],
                ['113100', 'Medicine stock', '113000'],
                ['113101', 'Medicine inventory', '113100'],
                ['113200', 'Optic stock', '113000'],
                ['113201', 'Optic inventory', '113200'],
                ['114000', 'Branch balances', '110000'],
                ['114100', 'Interbranch accounts', '114000'],
                ['114101', 'Interbranch clearing', '114100'],
                ['210000', 'Current liabilities', '200000'],
                ['211000', 'Trade payables', '210000'],
                ['211100', 'Supplier accounts', '211000'],
                ['211101', 'Supplier payable', '211100'],
                ['212000', 'Taxes payable', '210000'],
                ['212100', 'VAT accounts', '212000'],
                ['212101', 'VAT payable', '212100'],
                ['213000', 'Customer advances', '210000'],
                ['213100', 'Advance accounts', '213000'],
                ['213101', 'Customer advance', '213100'],
                ['310000', 'Capital and opening balances', '300000'],
                ['311000', 'Owners equity', '310000'],
                ['311100', 'Capital accounts', '311000'],
                ['311101', 'Owner capital', '311100'],
                ['311102', 'Opening balance', '311100'],
                ['410000', 'Operating income', '400000'],
                ['411000', 'Hospital income', '410000'],
                ['411100', 'Clinical services', '411000'],
                ['411101', 'Consultation income', '411100'],
                ['411102', 'Investigation income', '411100'],
                ['411103', 'Operation income', '411100'],
                ['412000', 'Pharmacy income', '410000'],
                ['412100', 'Medicine sales', '412000'],
                ['412101', 'Medicine sales income', '412100'],
                ['412200', 'Optic sales', '412000'],
                ['412201', 'Optic sales income', '412200'],
                ['414000', 'Other income', '410000'],
                ['414100', 'Other income accounts', '414000'],
                ['414101', 'Other income', '414100'],
                ['415000', 'Supplier discounts', '410000'],
                ['415100', 'Discount received', '415000'],
                ['415101', 'Supplier discount', '415100'],
                ['416000', 'Stock adjustment gains', '410000'],
                ['416100', 'Adjustment gain accounts', '416000'],
                ['416101', 'Adjustment gain', '416100'],
                ['417000', 'Purchase return gains', '410000'],
                ['417100', 'Return gain accounts', '417000'],
                ['417101', 'Purchase return gain', '417100'],
                ['510000', 'Operating expenses', '500000'],
                ['511000', 'Cost of goods sold', '510000'],
                ['511100', 'Medicine cost', '511000'],
                ['511101', 'Medicine cost of goods sold', '511100'],
                ['511200', 'Optic cost', '511000'],
                ['511201', 'Optic cost of goods sold', '511200'],
                ['512000', 'General expenses', '510000'],
                ['512100', 'General expense accounts', '512000'],
                ['512101', 'General expense', '512100'],
                ['513000', 'Discount expenses', '510000'],
                ['513100', 'Discount allowed accounts', '513000'],
                ['513101', 'Discount allowed', '513100'],
                ['514000', 'Returns, commission and stock losses', '510000'],
                ['514100', 'Wastage accounts', '514000'],
                ['514101', 'Damage and wastage', '514100'],
                ['514200', 'Sales return accounts', '514000'],
                ['514201', 'Sales returns', '514200'],
                ['514300', 'Hospital refund accounts', '514000'],
                ['514301', 'Hospital refunds', '514300'],
                ['514400', 'Commission accounts', '514000'],
                ['514401', 'Commission expense', '514400'],
                ['514500', 'Stock adjustment losses', '514000'],
                ['514501', 'Adjustment loss', '514500'],
                ['514600', 'Stock valuation differences', '514000'],
                ['514601', 'Valuation variance', '514600'],
                ['514700', 'Purchase return losses', '514000'],
                ['514701', 'Purchase return loss', '514700'],
            ];

            foreach ($children as [$code, $name, $parentCode]) {
                $parent = $accounts->get($parentCode);
                $depth = $parent->depth + 1;
                $accounts->put($code, AccountHead::firstOrCreate(['code' => $code], [
                    'name' => $name,
                    'parent_id' => $parent->id,
                    'type' => $parent->type,
                    'depth' => $depth,
                    'is_transaction' => $depth === 5,
                    'active' => true,
                    'system_key' => $reportRoles[$code] ?? null,
                    'cash_flow_activity' => $cashFlowActivities[$code] ?? null,
                ]));
            }
        }));
    }

    private function seedVouchers(): void
    {
        $vouchers = [
            ['receipt', 'RV', 'Receipt voucher'],
            ['payment', 'PV', 'Payment voucher'],
            ['journal', 'JV', 'Journal voucher'],
            ['contra', 'CV', 'Contra voucher'],
        ];
        foreach ($vouchers as [$code, $prefix, $name]) {
            VoucherType::firstOrCreate(['code' => $code], [
                'prefix' => $prefix,
                'name' => $name,
                'is_manual' => true,
                'active' => true,
            ]);
        }
    }

    private function seedMappings(): void
    {
        $accounts = AccountHead::get(['id', 'code'])->keyBy('code');
        $vouchers = VoucherType::get(['id', 'code'])->keyBy('code');
        $codes = config('accounting.accounts', []);

        AccountModule::unguarded(fn () => ModuleEntryAccount::unguarded(function () use ($accounts, $vouchers, $codes): void {
            foreach (config('accounting.integrations', []) as $key => $integration) {
                $mapping = AccountModule::firstOrCreate(['key' => $key], [
                    'name' => $integration['name'],
                    'module' => $integration['module'],
                    'voucher_type_id' => $vouchers->get($integration['voucher'])->id,
                    'active' => true,
                ]);

                $ordering = 0;
                foreach ($integration['components'] as $component => $values) {
                    $mapping->components()->firstOrCreate(['component' => $component], [
                        'name' => $values['name'],
                        'side' => $values['side'],
                        'account_head_id' => $accounts->get($codes[$values['account']])->id,
                        'use_payment_type' => $values['use_payment_type'] ?? false,
                        'ordering' => $ordering++,
                    ]);
                }
            }
        }));
    }
}
