<?php

app('router')->setCompiledRoutes(
    array (
  'compiled' => 
  array (
    0 => false,
    1 => 
    array (
      '/_debugbar/open' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'debugbar.openhandler',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/_debugbar/cache' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'debugbar.cache.delete',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/_debugbar/queries/explain' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'debugbar.queries.explain',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/_debugbar/assets' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'debugbar.assets',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/_boost/browser-logs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'boost.browser-logs',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accounting/ledgers/options' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.ledgers.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accounting/chart-of-accounts' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.chart-of-accounts.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.chart-of-accounts.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accounting/chart-of-accounts/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.chart-of-accounts.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accounting/account-modules' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.account-modules.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accounting/payment-accounts' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.payment-accounts.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.payment-accounts.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accounting/payment-accounts/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.payment-accounts.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accounting' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accounting/journal-entries' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.journal-entries.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.journal-entries.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accounting/journal-entries/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.journal-entries.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accounting/reports/ledger' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.reports.ledger',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accounting/reports/trial-balance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.reports.trial-balance',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accounting/reports/income-expense' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.reports.income-expense',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accounting/reports/balance-sheet' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.reports.balance-sheet',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accounting/reports/cash-flow' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.reports.cash-flow',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accounting/reports/cash-bank-book' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.reports.cash-bank-book',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accounting/reports/changes-in-equity' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.reports.changes-in-equity',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accounting/reports/receipts-payments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.reports.receipts-payments',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/investigation-categories/options' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.investigation-categories.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/investigations/options' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.investigations.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/operation-categories/options' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.operation-categories.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/operations/options' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.operations.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/departments/options' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.departments.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/patients/options' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patients.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/references/options' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.references.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/doctors/options' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.doctors.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.settings.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/investigation-categories' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.investigation-categories.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.investigation-categories.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/investigation-categories/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.investigation-categories.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/investigations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.investigations.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.investigations.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/investigations/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.investigations.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/operation-categories' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.operation-categories.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.operation-categories.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/operation-categories/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.operation-categories.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/operations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.operations.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.operations.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/operations/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.operations.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/appointments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.appointments.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.appointments.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/appointments/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.appointments.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/consultations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.consultations.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.consultations.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/consultations/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.consultations.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/patient-investigations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patient-investigations.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patient-investigations.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/patient-investigations/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patient-investigations.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/delivery-investigations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.delivery-investigations.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/patient-operations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patient-operations.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patient-operations.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/patient-operations/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patient-operations.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/commissions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.commissions.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/refunds/invoices' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.refunds.invoices',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/refunds/invoice' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.refunds.invoice',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/refunds' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.refunds.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.refunds.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/refunds/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.refunds.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/due-collections/invoices' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.due-collections.invoices',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/due-collections/invoice' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.due-collections.invoice',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/due-collections' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.due-collections.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.due-collections.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/due-collections/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.due-collections.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/patients/modal' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patients.modal',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/patients' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patients.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patients.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/patients/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patients.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/references' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.references.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.references.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/references/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.references.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/departments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.departments.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.departments.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/departments/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.departments.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/refund-causes' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.refund-causes.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.refund-causes.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/refund-causes/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.refund-causes.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/doctor-permissions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.doctors.permissions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.doctors.permissions-update',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/doctors' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.doctors.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.doctors.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/doctors/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.doctors.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/reports/cash-balance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.reports.cash-balance',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hospital/reports/patient-due' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.reports.patient-due',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/selectors/categories' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.categories.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/selectors/dosages' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.dosages.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/selectors/units' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.units.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/selectors/shelf' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.shelf.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/selectors/suppliers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.suppliers.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/selectors/products' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.products.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/selectors/variants' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.variants.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/selectors/product-units' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.product-units.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.settings.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/selectors/items' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.items.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/selectors/report-products' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.reports.products',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/selectors/purchase-sources' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchase-sources.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/selectors/sale-sources' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sale-sources.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/selectors/lots' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.lots.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/selectors/purchase-items' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchase-items.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/selectors/customers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.customers.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/customers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.customers.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.customers.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/categories' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.categories.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.categories.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/categories/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.categories.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/dosages' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.dosages.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.dosages.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/dosages/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.dosages.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/units' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.units.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.units.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/units/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.units.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/shelf' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.shelf.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.shelf.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/shelf/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.shelf.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/suppliers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.suppliers.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.suppliers.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/suppliers/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.suppliers.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/products' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.products.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.products.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/products/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.products.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/variants' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.variants.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.variants.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/variants/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.variants.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/product-units' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.product-units.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.product-units.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/product-units/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.product-units.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/sales' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sales.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sales.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/sales/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sales.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/purchases' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchases.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchases.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/purchases/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchases.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/sale-returns' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sale-returns.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sale-returns.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/sale-returns/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sale-returns.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/purchase-returns' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchase-returns.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchase-returns.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/purchase-returns/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchase-returns.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/opening-stock' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.opening-stock.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.opening-stock.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/opening-stock/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.opening-stock.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/adjustments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.adjustments.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.adjustments.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/adjustments/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.adjustments.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/damage' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.damage.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.damage.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/damage/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.damage.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/transfers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.transfers.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.transfers.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/transfers/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.transfers.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/sale-payments/invoices' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sale-payments.invoices',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/sale-payments/customers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sale-payments.customers',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/sale-payments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sale-payments.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sale-payments.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/sale-payments/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sale-payments.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/purchase-payments/invoices' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchase-payments.invoices',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/purchase-payments/suppliers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchase-payments.suppliers',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/purchase-payments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchase-payments.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchase-payments.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/purchase-payments/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchase-payments.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/reports/cash-balance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.reports.cash-balance',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/reports' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.reports.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/reports/stock' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.reports.stock',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/reports/movements' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.reports.movements',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/reports/expiry' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.reports.expiry',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/reports/profit-loss' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.reports.profit-loss',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/reports/purchase-discounts' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.reports.purchase-discounts',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/reports/daily' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.reports.daily',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/reports/customer-dues' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.reports.customer-dues',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pharmacy/reports/supplier-dues' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.reports.supplier-dues',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/prescription/options/selector' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.options.selector',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/prescription/options' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.options.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.options.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/prescription/options/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.options.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/prescription/prescription-dose' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.prescription-dose.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.prescription-dose.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/prescription/prescription-dose/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.prescription-dose.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/prescription/settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.settings.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/prescription/patients/modal' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.patients.modal',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/prescription/patients' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.patients.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/prescription/glass-patients/modal' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.glass-patients.modal',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/prescription/glass-patients' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.glass-patients.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/prescription' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/prescription/prescriptions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.prescriptions.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.prescriptions.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/prescription/prescriptions/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.prescriptions.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/prescription/glass-prescriptions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.glass-prescriptions.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.glass-prescriptions.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/prescription/glass-prescriptions/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.glass-prescriptions.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/module-dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.module-dashboard.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/workspace' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/sms' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.sms.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/sms/recharges' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.sms-recharges.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.sms-recharges.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/sms/recharges/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.sms-recharges.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/branch-dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.branch-dashboard.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.branch-dashboard.destroy',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/divisions/options' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.divisions.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/districts/options' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.districts.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/thanas/options' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.thanas.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/income-heads/options' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.income-heads.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/expense-heads/options' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.expense-heads.options',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/audit-logs/branches' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.audit-logs.branches',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/income-heads' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.income-heads.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.income-heads.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/income-heads/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.income-heads.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/expense-heads' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.expense-heads.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.expense-heads.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/expense-heads/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.expense-heads.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/reports/daily' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.reports.daily',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/reports/cash' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.reports.cash',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/reports/monthly-cash' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.reports.monthly-cash',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/incomes' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.incomes.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.incomes.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/incomes/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.incomes.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/expenses' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.expenses.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.expenses.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/expenses/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.expenses.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/audit-logs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.audit-logs.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/branches' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.branches.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.branches.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/branches/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.branches.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/divisions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.divisions.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.divisions.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/divisions/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.divisions.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/districts' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.districts.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.districts.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/districts/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.districts.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/thanas' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.thanas.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.thanas.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/thanas/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.thanas.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/payment-types' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.payment-types.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.payment-types.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/payment-types/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.payment-types.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/users' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.accounts.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.accounts.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/users/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.accounts.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.settings.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/billing' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.billing.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/deploy' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.deploy.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'core.deploy.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/deploy/run' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.deploy.run',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/tenants' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.tenants.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'core.tenants.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/tenants/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.tenants.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/admins' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.accounts.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'core.accounts.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/admins/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.accounts.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/plans' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.plans.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'core.plans.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/plans/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.plans.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/plan-permissions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.plan-permissions.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/subscriptions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.subscriptions.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'core.subscriptions.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/subscriptions/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.subscriptions.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/invoices/generate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.invoices.generate',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/invoices' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.invoices.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'core.invoices.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/invoices/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.invoices.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/payments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.payments.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'core.payments.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/payments/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.payments.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.settings.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/sms-accounts' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.sms-accounts.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'core.sms-accounts.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/sms-accounts/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.sms-accounts.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/sms-recharges' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.sms-recharges.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/sms-history' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.sms-history.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/roles' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.roles.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'core.roles.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/roles/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.roles.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/core/permissions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.permissions.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/up' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::utbIabknBjMDHAHZ',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'home',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/auth/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'auth.login',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'auth.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/auth/forgot-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'auth.password.request',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'auth.password.email',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/auth/reset-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'auth.password.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/auth/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'auth.logout',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
    ),
    2 => 
    array (
      0 => '{^(?|/_debugbar/clockwork/([^/]++)(*:36)|/a(?|ccounting/(?|chart\\-of\\-accounts/([^/]++)(?|/edit(*:97)|(*:104))|account\\-modules/([^/]++)(?|/edit(*:146)|(*:154))|payment\\-accounts/([^/]++)(?|/edit(*:197)|(*:205))|journal\\-entries/([^/]++)(?|(*:242)|/edit(*:255)|(*:263)))|u(?|dit\\-logs/([^/]++)(*:295)|th/(?|tenant\\-entry/([^/]++)(*:331)|reset\\-password/([^/]++)(*:363))))|/hospital/(?|settings/([^/]++)(*:404)|investigation(?|\\-categories/([^/]++)(?|/edit(*:457)|(*:465))|s/([^/]++)(?|/edit(*:492)|(*:500)))|operation(?|\\-categories/([^/]++)(?|/edit(*:551)|(*:559))|s/([^/]++)(?|/edit(*:586)|(*:594)))|appointments/([^/]++)(?|/edit(*:633)|(*:641))|co(?|nsultations/([^/]++)(?|(*:678)|/edit(*:691)|(*:699))|mmissions/(consultation|investigation|operation)/([0-9]+)(*:765))|patient(?|\\-(?|investigations/([^/]++)(?|(*:815)|/edit(*:828)|(*:836))|operations/([^/]++)(?|(*:867)|/edit(*:880)|(*:888)))|s/([^/]++)(?|/edit(*:916)|(*:924)))|d(?|e(?|livery\\-investigations/([^/]++)(*:973)|partments/([^/]++)(?|/edit(*:1007)|(*:1016)))|ue\\-collections/([^/]++)(?|(*:1054)|/edit(*:1068)|(*:1077))|octors/([^/]++)(?|(*:1105)|/edit(*:1119)|(*:1128)))|ref(?|und(?|s/([^/]++)(?|(*:1164)|/edit(*:1178)|(*:1187))|\\-causes/([^/]++)(?|/edit(*:1222)|(*:1231)))|erences/([^/]++)(?|/edit(*:1266)|(*:1275))))|/p(?|harmacy/(?|s(?|ettings/([^/]++)(*:1323)|helf/([^/]++)(?|/edit(*:1353)|(*:1362))|uppliers/([^/]++)(?|/edit(*:1397)|(*:1406))|ale(?|s/([^/]++)(?|/(?|void(*:1443)|edit(*:1456))|(*:1466))|\\-(?|returns/([^/]++)(?|/(?|void(*:1508)|edit(*:1521))|(*:1531))|payments/([^/]++)(?|/void(*:1566)|(*:1575)))))|categories/([^/]++)(?|/edit(*:1615)|(*:1624))|d(?|osages/([^/]++)(?|/edit(*:1661)|(*:1670))|amage/([^/]++)(?|/void(*:1702)|(*:1711)))|units/([^/]++)(?|/edit(*:1744)|(*:1753))|p(?|roduct(?|s/([^/]++)(?|/edit(*:1794)|(*:1803))|\\-units/([^/]++)(?|/edit(*:1837)|(*:1846)))|urchase(?|s/([^/]++)(?|/(?|void(*:1888)|edit(*:1901))|(*:1911))|\\-(?|returns/([^/]++)(?|/(?|void(*:1953)|edit(*:1966))|(*:1976))|payments/([^/]++)(?|/void(*:2011)|(*:2020)))))|variants/([^/]++)(?|/edit(*:2058)|(*:2067))|opening\\-stock/([^/]++)(?|/void(*:2108)|(*:2117))|adjustments/([^/]++)(?|/void(*:2155)|(*:2164))|transfers/([^/]++)(?|/void(*:2200)|(*:2209)))|rescription/(?|options/([^/]++)(?|/edit(*:2259)|(*:2268))|prescription(?|\\-dose/([^/]++)(?|/edit(*:2316)|(*:2325))|s/([^/]++)(?|(*:2348)|/edit(*:2362)|(*:2371)))|settings/([^/]++)(*:2399)|glass\\-prescriptions/([^/]++)(?|(*:2440)|/edit(*:2454)|(*:2463)))|ayment\\-types/([^/]++)(?|/edit(*:2504)|(*:2513)))|/s(?|ms/recharges/([^/]++)/proof(*:2556)|ettings/([^/]++)(?|/edit(*:2589)|(*:2598)))|/b(?|ranch(?|\\-dashboard/([^/]++)(*:2642)|es/([^/]++)(?|/edit(*:2670)|(*:2679)))|illing/invoices/([^/]++)(?|(*:2717)|/checkouts(*:2736)))|/income(?|\\-heads/([^/]++)(?|/edit(*:2781)|(*:2790))|s/([^/]++)(?|/edit(*:2818)|(*:2827)))|/expense(?|\\-heads/([^/]++)(?|/edit(*:2873)|(*:2882))|s/([^/]++)(?|/edit(*:2910)|(*:2919)))|/di(?|visions/([^/]++)(?|/edit(*:2960)|(*:2969))|stricts/([^/]++)(?|/edit(*:3003)|(*:3012)))|/thanas/([^/]++)(?|/edit(*:3047)|(*:3056))|/users/([^/]++)(?|/edit(*:3089)|(*:3098))|/core/(?|tenants/([^/]++)(?|/e(?|nter(*:3145)|dit(*:3157))|(*:3167))|admins/([^/]++)(?|/edit(*:3200)|(*:3209))|p(?|lan(?|s/([^/]++)(?|/edit(*:3247)|(*:3256))|\\-permissions/([^/]++)(*:3288))|ayments/(?|([^/]++)(*:3317)|callback/([^/]++)/([^/]++)(*:3352)|notify/([^/]++)/([^/]++)(*:3385))|ermissions/([^/]++)(*:3414))|s(?|ubscriptions/([^/]++)(?|/edit(*:3457)|(*:3466))|ettings/([^/]++)(?|/edit(*:3500)|(*:3509))|ms\\-(?|accounts/([^/]++)(?|/edit(*:3551)|(*:3560))|recharges/([^/]++)(?|/proof(*:3597)|(*:3606))|history/([^/]++)(?|/resolve(*:3643)|(*:3652))))|invoices/([^/]++)(?|/(?|issue(*:3693)|void(*:3706)|edit(*:3719))|(*:3729))|checkouts/([^/]++)/(?|approve(*:3768)|reject(*:3783))|roles/([^/]++)(?|/edit(*:3815)|(*:3824))))/?$}sDu',
    ),
    3 => 
    array (
      36 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'debugbar.clockwork',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      97 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.chart-of-accounts.edit',
          ),
          1 => 
          array (
            0 => 'account_head',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      104 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.chart-of-accounts.update',
          ),
          1 => 
          array (
            0 => 'account_head',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.chart-of-accounts.destroy',
          ),
          1 => 
          array (
            0 => 'account_head',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      146 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.account-modules.edit',
          ),
          1 => 
          array (
            0 => 'account_module',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      154 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.account-modules.update',
          ),
          1 => 
          array (
            0 => 'account_module',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      197 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.payment-accounts.edit',
          ),
          1 => 
          array (
            0 => 'payment_account',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      205 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.payment-accounts.update',
          ),
          1 => 
          array (
            0 => 'payment_account',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.payment-accounts.destroy',
          ),
          1 => 
          array (
            0 => 'payment_account',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      242 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.journal-entries.show',
          ),
          1 => 
          array (
            0 => 'journal_entry',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      255 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.journal-entries.edit',
          ),
          1 => 
          array (
            0 => 'journal_entry',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      263 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.journal-entries.update',
          ),
          1 => 
          array (
            0 => 'journal_entry',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'accounting.journal-entries.destroy',
          ),
          1 => 
          array (
            0 => 'journal_entry',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      295 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.audit-logs.show',
          ),
          1 => 
          array (
            0 => 'audit_log',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      331 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'auth.tenant-entry',
          ),
          1 => 
          array (
            0 => 'token',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      363 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'auth.password.reset',
          ),
          1 => 
          array (
            0 => 'token',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      404 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.settings.update',
          ),
          1 => 
          array (
            0 => 'setting',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      457 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.investigation-categories.edit',
          ),
          1 => 
          array (
            0 => 'investigation_category',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      465 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.investigation-categories.update',
          ),
          1 => 
          array (
            0 => 'investigation_category',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.investigation-categories.destroy',
          ),
          1 => 
          array (
            0 => 'investigation_category',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      492 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.investigations.edit',
          ),
          1 => 
          array (
            0 => 'investigation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      500 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.investigations.update',
          ),
          1 => 
          array (
            0 => 'investigation',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.investigations.destroy',
          ),
          1 => 
          array (
            0 => 'investigation',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      551 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.operation-categories.edit',
          ),
          1 => 
          array (
            0 => 'operation_category',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      559 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.operation-categories.update',
          ),
          1 => 
          array (
            0 => 'operation_category',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.operation-categories.destroy',
          ),
          1 => 
          array (
            0 => 'operation_category',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      586 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.operations.edit',
          ),
          1 => 
          array (
            0 => 'operation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      594 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.operations.update',
          ),
          1 => 
          array (
            0 => 'operation',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.operations.destroy',
          ),
          1 => 
          array (
            0 => 'operation',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      633 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.appointments.edit',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      641 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.appointments.update',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.appointments.destroy',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      678 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.consultations.show',
          ),
          1 => 
          array (
            0 => 'consultation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      691 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.consultations.edit',
          ),
          1 => 
          array (
            0 => 'consultation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      699 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.consultations.update',
          ),
          1 => 
          array (
            0 => 'consultation',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.consultations.destroy',
          ),
          1 => 
          array (
            0 => 'consultation',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      765 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.commissions.update',
          ),
          1 => 
          array (
            0 => 'service',
            1 => 'invoice',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      815 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patient-investigations.show',
          ),
          1 => 
          array (
            0 => 'patient_investigation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      828 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patient-investigations.edit',
          ),
          1 => 
          array (
            0 => 'patient_investigation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      836 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patient-investigations.update',
          ),
          1 => 
          array (
            0 => 'patient_investigation',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patient-investigations.destroy',
          ),
          1 => 
          array (
            0 => 'patient_investigation',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      867 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patient-operations.show',
          ),
          1 => 
          array (
            0 => 'patient_operation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      880 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patient-operations.edit',
          ),
          1 => 
          array (
            0 => 'patient_operation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      888 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patient-operations.update',
          ),
          1 => 
          array (
            0 => 'patient_operation',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patient-operations.destroy',
          ),
          1 => 
          array (
            0 => 'patient_operation',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      916 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patients.edit',
          ),
          1 => 
          array (
            0 => 'patient',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      924 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patients.update',
          ),
          1 => 
          array (
            0 => 'patient',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.patients.destroy',
          ),
          1 => 
          array (
            0 => 'patient',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      973 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.delivery-investigations.update',
          ),
          1 => 
          array (
            0 => 'patient_investigation',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1007 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.departments.edit',
          ),
          1 => 
          array (
            0 => 'department',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1016 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.departments.update',
          ),
          1 => 
          array (
            0 => 'department',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.departments.destroy',
          ),
          1 => 
          array (
            0 => 'department',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1054 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.due-collections.show',
          ),
          1 => 
          array (
            0 => 'due_collection',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1068 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.due-collections.edit',
          ),
          1 => 
          array (
            0 => 'due_collection',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1077 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.due-collections.update',
          ),
          1 => 
          array (
            0 => 'due_collection',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.due-collections.destroy',
          ),
          1 => 
          array (
            0 => 'due_collection',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1105 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.doctors.show',
          ),
          1 => 
          array (
            0 => 'doctor',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1119 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.doctors.edit',
          ),
          1 => 
          array (
            0 => 'doctor',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1128 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.doctors.update',
          ),
          1 => 
          array (
            0 => 'doctor',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.doctors.destroy',
          ),
          1 => 
          array (
            0 => 'doctor',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1164 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.refunds.show',
          ),
          1 => 
          array (
            0 => 'refund',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1178 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.refunds.edit',
          ),
          1 => 
          array (
            0 => 'refund',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1187 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.refunds.update',
          ),
          1 => 
          array (
            0 => 'refund',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.refunds.destroy',
          ),
          1 => 
          array (
            0 => 'refund',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1222 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.refund-causes.edit',
          ),
          1 => 
          array (
            0 => 'refund_cause',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1231 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.refund-causes.update',
          ),
          1 => 
          array (
            0 => 'refund_cause',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.refund-causes.destroy',
          ),
          1 => 
          array (
            0 => 'refund_cause',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1266 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.references.edit',
          ),
          1 => 
          array (
            0 => 'reference',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1275 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.references.update',
          ),
          1 => 
          array (
            0 => 'reference',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'hospital.references.destroy',
          ),
          1 => 
          array (
            0 => 'reference',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1323 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.settings.update',
          ),
          1 => 
          array (
            0 => 'setting',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1353 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.shelf.edit',
          ),
          1 => 
          array (
            0 => 'shelf',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1362 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.shelf.update',
          ),
          1 => 
          array (
            0 => 'shelf',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.shelf.destroy',
          ),
          1 => 
          array (
            0 => 'shelf',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1397 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.suppliers.edit',
          ),
          1 => 
          array (
            0 => 'supplier',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1406 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.suppliers.update',
          ),
          1 => 
          array (
            0 => 'supplier',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.suppliers.destroy',
          ),
          1 => 
          array (
            0 => 'supplier',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1443 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sales.void',
          ),
          1 => 
          array (
            0 => 'sale',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1456 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sales.edit',
          ),
          1 => 
          array (
            0 => 'sale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1466 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sales.show',
          ),
          1 => 
          array (
            0 => 'sale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sales.update',
          ),
          1 => 
          array (
            0 => 'sale',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sales.destroy',
          ),
          1 => 
          array (
            0 => 'sale',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1508 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sale-returns.void',
          ),
          1 => 
          array (
            0 => 'sale_return',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1521 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sale-returns.edit',
          ),
          1 => 
          array (
            0 => 'sale_return',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1531 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sale-returns.show',
          ),
          1 => 
          array (
            0 => 'sale_return',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sale-returns.update',
          ),
          1 => 
          array (
            0 => 'sale_return',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sale-returns.destroy',
          ),
          1 => 
          array (
            0 => 'sale_return',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1566 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sale-payments.void',
          ),
          1 => 
          array (
            0 => 'sale_payment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1575 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sale-payments.show',
          ),
          1 => 
          array (
            0 => 'sale_payment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.sale-payments.destroy',
          ),
          1 => 
          array (
            0 => 'sale_payment',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1615 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.categories.edit',
          ),
          1 => 
          array (
            0 => 'product_category',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1624 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.categories.update',
          ),
          1 => 
          array (
            0 => 'product_category',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.categories.destroy',
          ),
          1 => 
          array (
            0 => 'product_category',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1661 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.dosages.edit',
          ),
          1 => 
          array (
            0 => 'dosage',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1670 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.dosages.update',
          ),
          1 => 
          array (
            0 => 'dosage',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.dosages.destroy',
          ),
          1 => 
          array (
            0 => 'dosage',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1702 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.damage.void',
          ),
          1 => 
          array (
            0 => 'wastage',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1711 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.damage.show',
          ),
          1 => 
          array (
            0 => 'wastage',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1744 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.units.edit',
          ),
          1 => 
          array (
            0 => 'unit',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1753 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.units.update',
          ),
          1 => 
          array (
            0 => 'unit',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.units.destroy',
          ),
          1 => 
          array (
            0 => 'unit',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1794 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.products.edit',
          ),
          1 => 
          array (
            0 => 'product',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1803 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.products.update',
          ),
          1 => 
          array (
            0 => 'product',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.products.destroy',
          ),
          1 => 
          array (
            0 => 'product',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1837 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.product-units.edit',
          ),
          1 => 
          array (
            0 => 'product_unit',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1846 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.product-units.update',
          ),
          1 => 
          array (
            0 => 'product_unit',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.product-units.destroy',
          ),
          1 => 
          array (
            0 => 'product_unit',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1888 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchases.void',
          ),
          1 => 
          array (
            0 => 'purchase',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1901 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchases.edit',
          ),
          1 => 
          array (
            0 => 'purchase',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1911 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchases.show',
          ),
          1 => 
          array (
            0 => 'purchase',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchases.update',
          ),
          1 => 
          array (
            0 => 'purchase',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchases.destroy',
          ),
          1 => 
          array (
            0 => 'purchase',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1953 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchase-returns.void',
          ),
          1 => 
          array (
            0 => 'purchase_return',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1966 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchase-returns.edit',
          ),
          1 => 
          array (
            0 => 'purchase_return',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1976 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchase-returns.show',
          ),
          1 => 
          array (
            0 => 'purchase_return',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchase-returns.update',
          ),
          1 => 
          array (
            0 => 'purchase_return',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchase-returns.destroy',
          ),
          1 => 
          array (
            0 => 'purchase_return',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2011 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchase-payments.void',
          ),
          1 => 
          array (
            0 => 'purchase_payment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2020 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchase-payments.show',
          ),
          1 => 
          array (
            0 => 'purchase_payment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.purchase-payments.destroy',
          ),
          1 => 
          array (
            0 => 'purchase_payment',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2058 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.variants.edit',
          ),
          1 => 
          array (
            0 => 'product_variant',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2067 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.variants.update',
          ),
          1 => 
          array (
            0 => 'product_variant',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.variants.destroy',
          ),
          1 => 
          array (
            0 => 'product_variant',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2108 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.opening-stock.void',
          ),
          1 => 
          array (
            0 => 'opening_stock',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2117 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.opening-stock.show',
          ),
          1 => 
          array (
            0 => 'opening_stock',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2155 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.adjustments.void',
          ),
          1 => 
          array (
            0 => 'adjustment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2164 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.adjustments.show',
          ),
          1 => 
          array (
            0 => 'adjustment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2200 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.transfers.void',
          ),
          1 => 
          array (
            0 => 'transfer',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2209 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pharmacy.transfers.show',
          ),
          1 => 
          array (
            0 => 'transfer',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2259 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.options.edit',
          ),
          1 => 
          array (
            0 => 'prescription_option',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2268 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.options.update',
          ),
          1 => 
          array (
            0 => 'prescription_option',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.options.destroy',
          ),
          1 => 
          array (
            0 => 'prescription_option',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2316 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.prescription-dose.edit',
          ),
          1 => 
          array (
            0 => 'prescription_dose',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2325 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.prescription-dose.update',
          ),
          1 => 
          array (
            0 => 'prescription_dose',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.prescription-dose.destroy',
          ),
          1 => 
          array (
            0 => 'prescription_dose',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2348 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.prescriptions.show',
          ),
          1 => 
          array (
            0 => 'prescription',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2362 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.prescriptions.edit',
          ),
          1 => 
          array (
            0 => 'prescription',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2371 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.prescriptions.update',
          ),
          1 => 
          array (
            0 => 'prescription',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.prescriptions.destroy',
          ),
          1 => 
          array (
            0 => 'prescription',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2399 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.settings.update',
          ),
          1 => 
          array (
            0 => 'setting',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2440 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.glass-prescriptions.show',
          ),
          1 => 
          array (
            0 => 'glass_prescription',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2454 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.glass-prescriptions.edit',
          ),
          1 => 
          array (
            0 => 'glass_prescription',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2463 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.glass-prescriptions.update',
          ),
          1 => 
          array (
            0 => 'glass_prescription',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'prescription.glass-prescriptions.destroy',
          ),
          1 => 
          array (
            0 => 'glass_prescription',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2504 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.payment-types.edit',
          ),
          1 => 
          array (
            0 => 'payment_type',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2513 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.payment-types.update',
          ),
          1 => 
          array (
            0 => 'payment_type',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.payment-types.destroy',
          ),
          1 => 
          array (
            0 => 'payment_type',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2556 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.sms-recharges.proof',
          ),
          1 => 
          array (
            0 => 'sms_recharge',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2589 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.settings.edit',
          ),
          1 => 
          array (
            0 => 'setting',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2598 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.settings.update',
          ),
          1 => 
          array (
            0 => 'setting',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2642 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.branch-dashboard.store',
          ),
          1 => 
          array (
            0 => 'branch',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2670 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.branches.edit',
          ),
          1 => 
          array (
            0 => 'branch',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2679 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.branches.update',
          ),
          1 => 
          array (
            0 => 'branch',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.branches.destroy',
          ),
          1 => 
          array (
            0 => 'branch',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2717 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.billing.show',
          ),
          1 => 
          array (
            0 => 'invoice',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2736 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.billing.checkouts.store',
          ),
          1 => 
          array (
            0 => 'invoice',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2781 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.income-heads.edit',
          ),
          1 => 
          array (
            0 => 'income_head',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2790 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.income-heads.update',
          ),
          1 => 
          array (
            0 => 'income_head',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.income-heads.destroy',
          ),
          1 => 
          array (
            0 => 'income_head',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2818 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.incomes.edit',
          ),
          1 => 
          array (
            0 => 'income',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2827 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.incomes.update',
          ),
          1 => 
          array (
            0 => 'income',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.incomes.destroy',
          ),
          1 => 
          array (
            0 => 'income',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2873 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.expense-heads.edit',
          ),
          1 => 
          array (
            0 => 'expense_head',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2882 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.expense-heads.update',
          ),
          1 => 
          array (
            0 => 'expense_head',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.expense-heads.destroy',
          ),
          1 => 
          array (
            0 => 'expense_head',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2910 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.expenses.edit',
          ),
          1 => 
          array (
            0 => 'expense',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2919 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.expenses.update',
          ),
          1 => 
          array (
            0 => 'expense',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.expenses.destroy',
          ),
          1 => 
          array (
            0 => 'expense',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2960 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.divisions.edit',
          ),
          1 => 
          array (
            0 => 'division',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2969 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.divisions.update',
          ),
          1 => 
          array (
            0 => 'division',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.divisions.destroy',
          ),
          1 => 
          array (
            0 => 'division',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3003 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.districts.edit',
          ),
          1 => 
          array (
            0 => 'district',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3012 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.districts.update',
          ),
          1 => 
          array (
            0 => 'district',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.districts.destroy',
          ),
          1 => 
          array (
            0 => 'district',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3047 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.thanas.edit',
          ),
          1 => 
          array (
            0 => 'thana',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3056 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.thanas.update',
          ),
          1 => 
          array (
            0 => 'thana',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.thanas.destroy',
          ),
          1 => 
          array (
            0 => 'thana',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3089 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.accounts.edit',
          ),
          1 => 
          array (
            0 => 'account',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3098 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.accounts.update',
          ),
          1 => 
          array (
            0 => 'account',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tenantcore.accounts.destroy',
          ),
          1 => 
          array (
            0 => 'account',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3145 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.tenants.enter',
          ),
          1 => 
          array (
            0 => 'tenant',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3157 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.tenants.edit',
          ),
          1 => 
          array (
            0 => 'tenant',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3167 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.tenants.update',
          ),
          1 => 
          array (
            0 => 'tenant',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'core.tenants.destroy',
          ),
          1 => 
          array (
            0 => 'tenant',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3200 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.accounts.edit',
          ),
          1 => 
          array (
            0 => 'account',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3209 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.accounts.update',
          ),
          1 => 
          array (
            0 => 'account',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'core.accounts.destroy',
          ),
          1 => 
          array (
            0 => 'account',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3247 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.plans.edit',
          ),
          1 => 
          array (
            0 => 'plan',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3256 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.plans.update',
          ),
          1 => 
          array (
            0 => 'plan',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'core.plans.destroy',
          ),
          1 => 
          array (
            0 => 'plan',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3288 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.plan-permissions.update',
          ),
          1 => 
          array (
            0 => 'plan',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3317 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.payments.show',
          ),
          1 => 
          array (
            0 => 'payment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3352 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'gateway.callback',
          ),
          1 => 
          array (
            0 => 'gateway',
            1 => 'token',
          ),
          2 => 
          array (
            'GET' => 0,
            'POST' => 1,
            'HEAD' => 2,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3385 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'gateway.notify',
          ),
          1 => 
          array (
            0 => 'gateway',
            1 => 'token',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3414 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.permissions.update',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3457 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.subscriptions.edit',
          ),
          1 => 
          array (
            0 => 'subscription',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3466 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.subscriptions.update',
          ),
          1 => 
          array (
            0 => 'subscription',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3500 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.settings.edit',
          ),
          1 => 
          array (
            0 => 'setting',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3509 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.settings.update',
          ),
          1 => 
          array (
            0 => 'setting',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3551 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.sms-accounts.edit',
          ),
          1 => 
          array (
            0 => 'sms_account',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3560 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.sms-accounts.update',
          ),
          1 => 
          array (
            0 => 'sms_account',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3597 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.sms-recharges.proof',
          ),
          1 => 
          array (
            0 => 'sms_recharge',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3606 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.sms-recharges.show',
          ),
          1 => 
          array (
            0 => 'sms_recharge',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'core.sms-recharges.update',
          ),
          1 => 
          array (
            0 => 'sms_recharge',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3643 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.sms-history.resolve',
          ),
          1 => 
          array (
            0 => 'sms_message',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3652 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.sms-history.show',
          ),
          1 => 
          array (
            0 => 'sms_message',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3693 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.invoices.issue',
          ),
          1 => 
          array (
            0 => 'invoice',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3706 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.invoices.void',
          ),
          1 => 
          array (
            0 => 'invoice',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3719 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.invoices.edit',
          ),
          1 => 
          array (
            0 => 'invoice',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3729 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.invoices.show',
          ),
          1 => 
          array (
            0 => 'invoice',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'core.invoices.update',
          ),
          1 => 
          array (
            0 => 'invoice',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3768 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.checkouts.approve',
          ),
          1 => 
          array (
            0 => 'checkout',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3783 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.checkouts.reject',
          ),
          1 => 
          array (
            0 => 'checkout',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3815 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.roles.edit',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3824 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'core.roles.update',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'core.roles.destroy',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => NULL,
          1 => NULL,
          2 => NULL,
          3 => NULL,
          4 => false,
          5 => false,
          6 => 0,
        ),
      ),
    ),
    4 => NULL,
  ),
  'attributes' => 
  array (
    'debugbar.openhandler' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '_debugbar/open',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'Fruitcake\\LaravelDebugbar\\Middleware\\DebugbarEnabled',
          1 => 'Fruitcake\\LaravelDebugbar\\Middleware\\StopRecordingTelescope',
        ),
        'uses' => 'Fruitcake\\LaravelDebugbar\\Controllers\\OpenHandlerController@handle',
        'controller' => 'Fruitcake\\LaravelDebugbar\\Controllers\\OpenHandlerController@handle',
        'namespace' => NULL,
        'prefix' => '_debugbar',
        'where' => 
        array (
        ),
        'as' => 'debugbar.openhandler',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'debugbar.cache.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => '_debugbar/cache',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'Fruitcake\\LaravelDebugbar\\Middleware\\DebugbarEnabled',
          1 => 'Fruitcake\\LaravelDebugbar\\Middleware\\StopRecordingTelescope',
        ),
        'uses' => 'Fruitcake\\LaravelDebugbar\\Controllers\\CacheController@delete',
        'controller' => 'Fruitcake\\LaravelDebugbar\\Controllers\\CacheController@delete',
        'namespace' => NULL,
        'prefix' => '_debugbar',
        'where' => 
        array (
        ),
        'as' => 'debugbar.cache.delete',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'debugbar.queries.explain' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '_debugbar/queries/explain',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'Fruitcake\\LaravelDebugbar\\Middleware\\DebugbarEnabled',
          1 => 'Fruitcake\\LaravelDebugbar\\Middleware\\StopRecordingTelescope',
        ),
        'uses' => 'Fruitcake\\LaravelDebugbar\\Controllers\\QueriesController@explain',
        'controller' => 'Fruitcake\\LaravelDebugbar\\Controllers\\QueriesController@explain',
        'namespace' => NULL,
        'prefix' => '_debugbar',
        'where' => 
        array (
        ),
        'as' => 'debugbar.queries.explain',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'debugbar.clockwork' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '_debugbar/clockwork/{id}',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'Fruitcake\\LaravelDebugbar\\Middleware\\DebugbarEnabled',
          1 => 'Fruitcake\\LaravelDebugbar\\Middleware\\StopRecordingTelescope',
        ),
        'uses' => 'Fruitcake\\LaravelDebugbar\\Controllers\\OpenHandlerController@clockwork',
        'controller' => 'Fruitcake\\LaravelDebugbar\\Controllers\\OpenHandlerController@clockwork',
        'namespace' => NULL,
        'prefix' => '_debugbar',
        'where' => 
        array (
        ),
        'as' => 'debugbar.clockwork',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'debugbar.assets' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '_debugbar/assets',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'Fruitcake\\LaravelDebugbar\\Middleware\\DebugbarEnabled',
          1 => 'Fruitcake\\LaravelDebugbar\\Middleware\\StopRecordingTelescope',
        ),
        'uses' => 'Fruitcake\\LaravelDebugbar\\Controllers\\AssetController@getAssets',
        'controller' => 'Fruitcake\\LaravelDebugbar\\Controllers\\AssetController@getAssets',
        'namespace' => NULL,
        'prefix' => '_debugbar',
        'where' => 
        array (
        ),
        'as' => 'debugbar.assets',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'boost.browser-logs' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '_boost/browser-logs',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:1598:"function (\\Illuminate\\Http\\Request $request) {
            $logs = $request->input(\'logs\', []);

            // Handle sendBeacon\'s text/plain content type.
            if (empty($logs) && ! $request->isJson()) {
                $decoded = json_decode($request->getContent(), true);
                $logs = $decoded[\'logs\'] ?? [];
            }

            /** @var Logger $logger */
            $logger = \\Illuminate\\Support\\Facades\\Log::channel(\'browser\');

            /**
             *  @var array{
             *      type: \'error\'|\'warn\'|\'info\'|\'log\'|\'table\'|\'window_error\'|\'uncaught_error\'|\'unhandled_rejection\',
             *      timestamp: string,
             *      data: array,
             *      url:string,
             *      userAgent:string
             *  } $log */
            foreach ($logs as $log) {
                $logger->write(
                    level: match ($log[\'type\']) {
                        \'warn\' => \'warning\',
                        \'log\', \'table\' => \'debug\',
                        \'window_error\', \'uncaught_error\', \'unhandled_rejection\' => \'error\',
                        default => $log[\'type\']
                    },
                    message: self::buildLogMessageFromData($log[\'data\']),
                    context: [
                        \'url\' => $log[\'url\'],
                        \'user_agent\' => $log[\'userAgent\'] ?: null,
                        \'timestamp\' => $log[\'timestamp\'] ?: now()->toIso8601String(),
                    ]
                );
            }

            return response()->json([\'status\' => \'logged\']);
        }";s:5:"scope";s:34:"Laravel\\Boost\\BoostServiceProvider";s:4:"this";N;s:4:"self";s:32:"000000000000115b0000000000000000";}}',
        'as' => 'boost.browser-logs',
        'excluded_middleware' => 
        array (
          0 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.ledgers.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/ledgers/options',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
        ),
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\AccountingSelectorController@ledgers',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\AccountingSelectorController@ledgers',
        'as' => 'accounting.ledgers.options',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.chart-of-accounts.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/chart-of-accounts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
        ),
        'as' => 'accounting.chart-of-accounts.index',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\AccountHeadController@index',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\AccountHeadController@index',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.chart-of-accounts.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/chart-of-accounts/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
        ),
        'as' => 'accounting.chart-of-accounts.create',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\AccountHeadController@create',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\AccountHeadController@create',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.chart-of-accounts.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'accounting/chart-of-accounts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
        ),
        'as' => 'accounting.chart-of-accounts.store',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\AccountHeadController@store',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\AccountHeadController@store',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.chart-of-accounts.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/chart-of-accounts/{account_head}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
        ),
        'as' => 'accounting.chart-of-accounts.edit',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\AccountHeadController@edit',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\AccountHeadController@edit',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.chart-of-accounts.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'accounting/chart-of-accounts/{account_head}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
        ),
        'as' => 'accounting.chart-of-accounts.update',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\AccountHeadController@store',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\AccountHeadController@store',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.chart-of-accounts.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'accounting/chart-of-accounts/{account_head}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
        ),
        'as' => 'accounting.chart-of-accounts.destroy',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\AccountHeadController@destroy',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\AccountHeadController@destroy',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.account-modules.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/account-modules',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
        ),
        'as' => 'accounting.account-modules.index',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\AccountModuleController@index',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\AccountModuleController@index',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.account-modules.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/account-modules/{account_module}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
        ),
        'as' => 'accounting.account-modules.edit',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\AccountModuleController@edit',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\AccountModuleController@edit',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.account-modules.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'accounting/account-modules/{account_module}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
        ),
        'as' => 'accounting.account-modules.update',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\AccountModuleController@store',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\AccountModuleController@store',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.payment-accounts.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/payment-accounts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
        ),
        'as' => 'accounting.payment-accounts.index',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\PaymentAccountController@index',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\PaymentAccountController@index',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.payment-accounts.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/payment-accounts/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
        ),
        'as' => 'accounting.payment-accounts.create',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\PaymentAccountController@create',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\PaymentAccountController@create',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.payment-accounts.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'accounting/payment-accounts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
        ),
        'as' => 'accounting.payment-accounts.store',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\PaymentAccountController@store',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\PaymentAccountController@store',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.payment-accounts.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/payment-accounts/{payment_account}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
        ),
        'as' => 'accounting.payment-accounts.edit',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\PaymentAccountController@edit',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\PaymentAccountController@edit',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.payment-accounts.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'accounting/payment-accounts/{payment_account}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
        ),
        'as' => 'accounting.payment-accounts.update',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\PaymentAccountController@store',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\PaymentAccountController@store',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.payment-accounts.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'accounting/payment-accounts/{payment_account}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
        ),
        'as' => 'accounting.payment-accounts.destroy',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\PaymentAccountController@destroy',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\PaymentAccountController@destroy',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
          5 => 'branch:required',
        ),
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\DashboardController@index',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\DashboardController@index',
        'as' => 'accounting.dashboard',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.journal-entries.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/journal-entries',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
          5 => 'branch:required',
        ),
        'as' => 'accounting.journal-entries.index',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\JournalEntryController@index',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\JournalEntryController@index',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.journal-entries.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/journal-entries/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
          5 => 'branch:required',
        ),
        'as' => 'accounting.journal-entries.create',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\JournalEntryController@create',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\JournalEntryController@create',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.journal-entries.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'accounting/journal-entries',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
          5 => 'branch:required',
        ),
        'as' => 'accounting.journal-entries.store',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\JournalEntryController@store',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\JournalEntryController@store',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.journal-entries.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/journal-entries/{journal_entry}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
          5 => 'branch:required',
        ),
        'as' => 'accounting.journal-entries.show',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\JournalEntryController@show',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\JournalEntryController@show',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.journal-entries.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/journal-entries/{journal_entry}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
          5 => 'branch:required',
        ),
        'as' => 'accounting.journal-entries.edit',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\JournalEntryController@edit',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\JournalEntryController@edit',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.journal-entries.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'accounting/journal-entries/{journal_entry}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
          5 => 'branch:required',
        ),
        'as' => 'accounting.journal-entries.update',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\JournalEntryController@store',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\JournalEntryController@store',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.journal-entries.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'accounting/journal-entries/{journal_entry}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
          5 => 'branch:required',
        ),
        'as' => 'accounting.journal-entries.destroy',
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\JournalEntryController@destroy',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\JournalEntryController@destroy',
        'namespace' => NULL,
        'prefix' => 'accounting',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.reports.ledger' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/reports/ledger',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
          5 => 'branch:required',
        ),
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\Reports\\LedgerReportController@index',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\Reports\\LedgerReportController@index',
        'as' => 'accounting.reports.ledger',
        'namespace' => NULL,
        'prefix' => 'accounting/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.reports.trial-balance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/reports/trial-balance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
          5 => 'branch:required',
        ),
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\Reports\\TrialBalanceReportController@index',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\Reports\\TrialBalanceReportController@index',
        'as' => 'accounting.reports.trial-balance',
        'namespace' => NULL,
        'prefix' => 'accounting/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.reports.income-expense' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/reports/income-expense',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
          5 => 'branch:required',
        ),
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\Reports\\IncomeExpenseReportController@index',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\Reports\\IncomeExpenseReportController@index',
        'as' => 'accounting.reports.income-expense',
        'namespace' => NULL,
        'prefix' => 'accounting/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.reports.balance-sheet' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/reports/balance-sheet',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
          5 => 'branch:required',
        ),
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\Reports\\BalanceSheetReportController@index',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\Reports\\BalanceSheetReportController@index',
        'as' => 'accounting.reports.balance-sheet',
        'namespace' => NULL,
        'prefix' => 'accounting/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.reports.cash-flow' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/reports/cash-flow',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
          5 => 'branch:required',
        ),
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\Reports\\CashFlowReportController@index',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\Reports\\CashFlowReportController@index',
        'as' => 'accounting.reports.cash-flow',
        'namespace' => NULL,
        'prefix' => 'accounting/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.reports.cash-bank-book' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/reports/cash-bank-book',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
          5 => 'branch:required',
        ),
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\Reports\\CashBankBookReportController@index',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\Reports\\CashBankBookReportController@index',
        'as' => 'accounting.reports.cash-bank-book',
        'namespace' => NULL,
        'prefix' => 'accounting/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.reports.changes-in-equity' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/reports/changes-in-equity',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
          5 => 'branch:required',
        ),
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\Reports\\ChangesInEquityReportController@index',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\Reports\\ChangesInEquityReportController@index',
        'as' => 'accounting.reports.changes-in-equity',
        'namespace' => NULL,
        'prefix' => 'accounting/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accounting.reports.receipts-payments' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accounting/reports/receipts-payments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'accounting.permission',
          5 => 'branch:required',
        ),
        'uses' => 'Modules\\Accounting\\Http\\Controllers\\Reports\\ReceiptsPaymentsReportController@index',
        'controller' => 'Modules\\Accounting\\Http\\Controllers\\Reports\\ReceiptsPaymentsReportController@index',
        'as' => 'accounting.reports.receipts-payments',
        'namespace' => NULL,
        'prefix' => 'accounting/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.investigation-categories.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/investigation-categories/options',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationCategoryController@options',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationCategoryController@options',
        'as' => 'hospital.investigation-categories.options',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.investigations.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/investigations/options',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationController@options',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationController@options',
        'as' => 'hospital.investigations.options',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.operation-categories.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/operation-categories/options',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\OperationCategoryController@options',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\OperationCategoryController@options',
        'as' => 'hospital.operation-categories.options',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.operations.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/operations/options',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\OperationController@options',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\OperationController@options',
        'as' => 'hospital.operations.options',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.departments.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/departments/options',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DepartmentController@options',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DepartmentController@options',
        'as' => 'hospital.departments.options',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patients.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/patients/options',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@options',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@options',
        'as' => 'hospital.patients.options',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.references.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/references/options',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'branch:required',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\ReferenceController@options',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\ReferenceController@options',
        'as' => 'hospital.references.options',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.doctors.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/doctors/options',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@options',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@options',
        'as' => 'hospital.doctors.options',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.settings.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'hospital.permission',
        ),
        'as' => 'hospital.settings.index',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\SettingController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\SettingController@index',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.settings.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'hospital/settings/{setting}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'hospital.permission',
        ),
        'as' => 'hospital.settings.update',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\SettingController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\SettingController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DashboardController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DashboardController@index',
        'as' => 'hospital.dashboard',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.investigation-categories.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/investigation-categories',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.investigation-categories.index',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationCategoryController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationCategoryController@index',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.investigation-categories.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/investigation-categories/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.investigation-categories.create',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationCategoryController@create',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationCategoryController@create',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.investigation-categories.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'hospital/investigation-categories',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.investigation-categories.store',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationCategoryController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationCategoryController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.investigation-categories.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/investigation-categories/{investigation_category}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.investigation-categories.edit',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationCategoryController@edit',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationCategoryController@edit',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.investigation-categories.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'hospital/investigation-categories/{investigation_category}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.investigation-categories.update',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationCategoryController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationCategoryController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.investigation-categories.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'hospital/investigation-categories/{investigation_category}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.investigation-categories.destroy',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationCategoryController@destroy',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationCategoryController@destroy',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.investigations.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/investigations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.investigations.index',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationController@index',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.investigations.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/investigations/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.investigations.create',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationController@create',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationController@create',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.investigations.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'hospital/investigations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.investigations.store',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.investigations.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/investigations/{investigation}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.investigations.edit',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationController@edit',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationController@edit',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.investigations.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'hospital/investigations/{investigation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.investigations.update',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.investigations.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'hospital/investigations/{investigation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.investigations.destroy',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationController@destroy',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\InvestigationController@destroy',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.operation-categories.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/operation-categories',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.operation-categories.index',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\OperationCategoryController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\OperationCategoryController@index',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.operation-categories.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/operation-categories/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.operation-categories.create',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\OperationCategoryController@create',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\OperationCategoryController@create',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.operation-categories.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'hospital/operation-categories',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.operation-categories.store',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\OperationCategoryController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\OperationCategoryController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.operation-categories.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/operation-categories/{operation_category}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.operation-categories.edit',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\OperationCategoryController@edit',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\OperationCategoryController@edit',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.operation-categories.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'hospital/operation-categories/{operation_category}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.operation-categories.update',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\OperationCategoryController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\OperationCategoryController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.operation-categories.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'hospital/operation-categories/{operation_category}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.operation-categories.destroy',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\OperationCategoryController@destroy',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\OperationCategoryController@destroy',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.operations.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/operations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.operations.index',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\OperationController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\OperationController@index',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.operations.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/operations/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.operations.create',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\OperationController@create',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\OperationController@create',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.operations.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'hospital/operations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.operations.store',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\OperationController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\OperationController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.operations.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/operations/{operation}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.operations.edit',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\OperationController@edit',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\OperationController@edit',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.operations.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'hospital/operations/{operation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.operations.update',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\OperationController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\OperationController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.operations.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'hospital/operations/{operation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.operations.destroy',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\OperationController@destroy',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\OperationController@destroy',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.appointments.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/appointments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.appointments.index',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\AppointmentController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\AppointmentController@index',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.appointments.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/appointments/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.appointments.create',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\AppointmentController@create',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\AppointmentController@create',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.appointments.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'hospital/appointments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.appointments.store',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\AppointmentController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\AppointmentController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.appointments.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/appointments/{appointment}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.appointments.edit',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\AppointmentController@edit',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\AppointmentController@edit',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.appointments.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'hospital/appointments/{appointment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.appointments.update',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\AppointmentController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\AppointmentController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.appointments.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'hospital/appointments/{appointment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.appointments.destroy',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\AppointmentController@destroy',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\AppointmentController@destroy',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.consultations.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/consultations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.consultations.index',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\ConsultationController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\ConsultationController@index',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.consultations.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/consultations/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.consultations.create',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\ConsultationController@create',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\ConsultationController@create',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.consultations.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'hospital/consultations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.consultations.store',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\ConsultationController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\ConsultationController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.consultations.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/consultations/{consultation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.consultations.show',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\ConsultationController@show',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\ConsultationController@show',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.consultations.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/consultations/{consultation}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.consultations.edit',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\ConsultationController@edit',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\ConsultationController@edit',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.consultations.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'hospital/consultations/{consultation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.consultations.update',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\ConsultationController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\ConsultationController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.consultations.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'hospital/consultations/{consultation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.consultations.destroy',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\ConsultationController@destroy',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\ConsultationController@destroy',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patient-investigations.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/patient-investigations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patient-investigations.index',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientInvestigationController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientInvestigationController@index',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patient-investigations.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/patient-investigations/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patient-investigations.create',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientInvestigationController@create',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientInvestigationController@create',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patient-investigations.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'hospital/patient-investigations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patient-investigations.store',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientInvestigationController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientInvestigationController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patient-investigations.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/patient-investigations/{patient_investigation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patient-investigations.show',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientInvestigationController@show',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientInvestigationController@show',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patient-investigations.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/patient-investigations/{patient_investigation}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patient-investigations.edit',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientInvestigationController@edit',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientInvestigationController@edit',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patient-investigations.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'hospital/patient-investigations/{patient_investigation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patient-investigations.update',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientInvestigationController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientInvestigationController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patient-investigations.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'hospital/patient-investigations/{patient_investigation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patient-investigations.destroy',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientInvestigationController@destroy',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientInvestigationController@destroy',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.delivery-investigations.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/delivery-investigations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DeliveryInvestigationController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DeliveryInvestigationController@index',
        'as' => 'hospital.delivery-investigations.index',
        'namespace' => NULL,
        'prefix' => 'hospital/delivery-investigations',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.delivery-investigations.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'hospital/delivery-investigations/{patient_investigation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DeliveryInvestigationController@update',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DeliveryInvestigationController@update',
        'as' => 'hospital.delivery-investigations.update',
        'namespace' => NULL,
        'prefix' => 'hospital/delivery-investigations',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patient-operations.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/patient-operations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patient-operations.index',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientOperationController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientOperationController@index',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patient-operations.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/patient-operations/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patient-operations.create',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientOperationController@create',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientOperationController@create',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patient-operations.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'hospital/patient-operations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patient-operations.store',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientOperationController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientOperationController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patient-operations.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/patient-operations/{patient_operation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patient-operations.show',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientOperationController@show',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientOperationController@show',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patient-operations.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/patient-operations/{patient_operation}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patient-operations.edit',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientOperationController@edit',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientOperationController@edit',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patient-operations.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'hospital/patient-operations/{patient_operation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patient-operations.update',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientOperationController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientOperationController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patient-operations.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'hospital/patient-operations/{patient_operation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patient-operations.destroy',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientOperationController@destroy',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientOperationController@destroy',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.commissions.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/commissions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\CommissionController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\CommissionController@index',
        'as' => 'hospital.commissions.index',
        'namespace' => NULL,
        'prefix' => 'hospital/commissions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.commissions.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'hospital/commissions/{service}/{invoice}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\CommissionController@update',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\CommissionController@update',
        'as' => 'hospital.commissions.update',
        'namespace' => NULL,
        'prefix' => 'hospital/commissions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'service' => 'consultation|investigation|operation',
        'invoice' => '[0-9]+',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.refunds.invoices' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/refunds/invoices',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\RefundController@invoices',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\RefundController@invoices',
        'as' => 'hospital.refunds.invoices',
        'namespace' => NULL,
        'prefix' => 'hospital/refunds',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.refunds.invoice' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/refunds/invoice',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\RefundController@invoice',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\RefundController@invoice',
        'as' => 'hospital.refunds.invoice',
        'namespace' => NULL,
        'prefix' => 'hospital/refunds',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.refunds.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/refunds',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.refunds.index',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\RefundController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\RefundController@index',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.refunds.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/refunds/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.refunds.create',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\RefundController@create',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\RefundController@create',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.refunds.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'hospital/refunds',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.refunds.store',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\RefundController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\RefundController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.refunds.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/refunds/{refund}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.refunds.show',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\RefundController@show',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\RefundController@show',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.refunds.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/refunds/{refund}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.refunds.edit',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\RefundController@edit',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\RefundController@edit',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.refunds.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'hospital/refunds/{refund}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.refunds.update',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\RefundController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\RefundController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.refunds.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'hospital/refunds/{refund}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.refunds.destroy',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\RefundController@destroy',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\RefundController@destroy',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.due-collections.invoices' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/due-collections/invoices',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DueCollectionController@invoices',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DueCollectionController@invoices',
        'as' => 'hospital.due-collections.invoices',
        'namespace' => NULL,
        'prefix' => 'hospital/due-collections',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.due-collections.invoice' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/due-collections/invoice',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DueCollectionController@invoice',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DueCollectionController@invoice',
        'as' => 'hospital.due-collections.invoice',
        'namespace' => NULL,
        'prefix' => 'hospital/due-collections',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.due-collections.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/due-collections',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.due-collections.index',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DueCollectionController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DueCollectionController@index',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.due-collections.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/due-collections/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.due-collections.create',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DueCollectionController@create',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DueCollectionController@create',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.due-collections.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'hospital/due-collections',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.due-collections.store',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DueCollectionController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DueCollectionController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.due-collections.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/due-collections/{due_collection}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.due-collections.show',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DueCollectionController@show',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DueCollectionController@show',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.due-collections.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/due-collections/{due_collection}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.due-collections.edit',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DueCollectionController@edit',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DueCollectionController@edit',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.due-collections.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'hospital/due-collections/{due_collection}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.due-collections.update',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DueCollectionController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DueCollectionController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.due-collections.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'hospital/due-collections/{due_collection}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.due-collections.destroy',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DueCollectionController@destroy',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DueCollectionController@destroy',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patients.modal' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/patients/modal',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@modal',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@modal',
        'as' => 'hospital.patients.modal',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patients.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/patients',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patients.index',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@index',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patients.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/patients/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patients.create',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@create',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@create',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patients.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'hospital/patients',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patients.store',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patients.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/patients/{patient}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patients.edit',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@edit',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@edit',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patients.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'hospital/patients/{patient}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patients.update',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.patients.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'hospital/patients/{patient}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.patients.destroy',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@destroy',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@destroy',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.references.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/references',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.references.index',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\ReferenceController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\ReferenceController@index',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.references.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/references/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.references.create',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\ReferenceController@create',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\ReferenceController@create',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.references.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'hospital/references',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.references.store',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\ReferenceController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\ReferenceController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.references.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/references/{reference}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.references.edit',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\ReferenceController@edit',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\ReferenceController@edit',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.references.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'hospital/references/{reference}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.references.update',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\ReferenceController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\ReferenceController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.references.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'hospital/references/{reference}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.references.destroy',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\ReferenceController@destroy',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\ReferenceController@destroy',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.departments.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/departments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.departments.index',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DepartmentController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DepartmentController@index',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.departments.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/departments/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.departments.create',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DepartmentController@create',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DepartmentController@create',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.departments.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'hospital/departments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.departments.store',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DepartmentController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DepartmentController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.departments.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/departments/{department}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.departments.edit',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DepartmentController@edit',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DepartmentController@edit',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.departments.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'hospital/departments/{department}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.departments.update',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DepartmentController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DepartmentController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.departments.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'hospital/departments/{department}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.departments.destroy',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DepartmentController@destroy',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DepartmentController@destroy',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.refund-causes.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/refund-causes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.refund-causes.index',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\RefundCauseController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\RefundCauseController@index',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.refund-causes.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/refund-causes/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.refund-causes.create',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\RefundCauseController@create',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\RefundCauseController@create',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.refund-causes.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'hospital/refund-causes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.refund-causes.store',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\RefundCauseController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\RefundCauseController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.refund-causes.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/refund-causes/{refund_cause}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.refund-causes.edit',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\RefundCauseController@edit',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\RefundCauseController@edit',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.refund-causes.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'hospital/refund-causes/{refund_cause}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.refund-causes.update',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\RefundCauseController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\RefundCauseController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.refund-causes.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'hospital/refund-causes/{refund_cause}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.refund-causes.destroy',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\RefundCauseController@destroy',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\RefundCauseController@destroy',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.doctors.permissions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/doctor-permissions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@permissions',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@permissions',
        'as' => 'hospital.doctors.permissions',
        'namespace' => NULL,
        'prefix' => 'hospital/doctor-permissions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.doctors.permissions-update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'hospital/doctor-permissions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@savePermissions',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@savePermissions',
        'as' => 'hospital.doctors.permissions-update',
        'namespace' => NULL,
        'prefix' => 'hospital/doctor-permissions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.doctors.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/doctors',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.doctors.index',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@index',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.doctors.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/doctors/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.doctors.create',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@create',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@create',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.doctors.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'hospital/doctors',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.doctors.store',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.doctors.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/doctors/{doctor}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.doctors.show',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@show',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@show',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.doctors.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/doctors/{doctor}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.doctors.edit',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@edit',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@edit',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.doctors.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'hospital/doctors/{doctor}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.doctors.update',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@store',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.doctors.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'hospital/doctors/{doctor}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'as' => 'hospital.doctors.destroy',
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@destroy',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\DoctorController@destroy',
        'namespace' => NULL,
        'prefix' => 'hospital',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.reports.cash-balance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/reports/cash-balance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\Reports\\CashBalanceReportController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\Reports\\CashBalanceReportController@index',
        'as' => 'hospital.reports.cash-balance',
        'namespace' => NULL,
        'prefix' => 'hospital/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hospital.reports.patient-due' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hospital/reports/patient-due',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'hospital.permission',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\Reports\\PatientDueController@index',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\Reports\\PatientDueController@index',
        'as' => 'hospital.reports.patient-due',
        'namespace' => NULL,
        'prefix' => 'hospital/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.categories.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/selectors/categories',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductCategoryController@options',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductCategoryController@options',
        'as' => 'pharmacy.categories.options',
        'namespace' => NULL,
        'prefix' => 'pharmacy/selectors',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.dosages.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/selectors/dosages',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\DosageController@options',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\DosageController@options',
        'as' => 'pharmacy.dosages.options',
        'namespace' => NULL,
        'prefix' => 'pharmacy/selectors',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.units.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/selectors/units',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\UnitController@options',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\UnitController@options',
        'as' => 'pharmacy.units.options',
        'namespace' => NULL,
        'prefix' => 'pharmacy/selectors',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.shelf.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/selectors/shelf',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ShelfController@options',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ShelfController@options',
        'as' => 'pharmacy.shelf.options',
        'namespace' => NULL,
        'prefix' => 'pharmacy/selectors',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.suppliers.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/selectors/suppliers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SupplierController@options',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SupplierController@options',
        'as' => 'pharmacy.suppliers.options',
        'namespace' => NULL,
        'prefix' => 'pharmacy/selectors',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.products.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/selectors/products',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductController@options',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductController@options',
        'as' => 'pharmacy.products.options',
        'namespace' => NULL,
        'prefix' => 'pharmacy/selectors',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.variants.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/selectors/variants',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductVariantController@options',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductVariantController@options',
        'as' => 'pharmacy.variants.options',
        'namespace' => NULL,
        'prefix' => 'pharmacy/selectors',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.product-units.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/selectors/product-units',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductUnitController@options',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductUnitController@options',
        'as' => 'pharmacy.product-units.options',
        'namespace' => NULL,
        'prefix' => 'pharmacy/selectors',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.settings.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.settings.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SettingController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SettingController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.settings.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'pharmacy/settings/{setting}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.settings.update',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SettingController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SettingController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\DashboardController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\DashboardController@index',
        'as' => 'pharmacy.dashboard',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.items.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/selectors/items',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PharmacySelectorController@items',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PharmacySelectorController@items',
        'as' => 'pharmacy.items.options',
        'namespace' => NULL,
        'prefix' => 'pharmacy/selectors',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.reports.products' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/selectors/report-products',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PharmacySelectorController@reportProducts',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PharmacySelectorController@reportProducts',
        'as' => 'pharmacy.reports.products',
        'namespace' => NULL,
        'prefix' => 'pharmacy/selectors',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchase-sources.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/selectors/purchase-sources',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PharmacySelectorController@purchaseSources',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PharmacySelectorController@purchaseSources',
        'as' => 'pharmacy.purchase-sources.options',
        'namespace' => NULL,
        'prefix' => 'pharmacy/selectors',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sale-sources.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/selectors/sale-sources',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PharmacySelectorController@saleSources',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PharmacySelectorController@saleSources',
        'as' => 'pharmacy.sale-sources.options',
        'namespace' => NULL,
        'prefix' => 'pharmacy/selectors',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.lots.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/selectors/lots',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PharmacySelectorController@lots',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PharmacySelectorController@lots',
        'as' => 'pharmacy.lots.options',
        'namespace' => NULL,
        'prefix' => 'pharmacy/selectors',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchase-items.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/selectors/purchase-items',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseItemSelectorController@items',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseItemSelectorController@items',
        'as' => 'pharmacy.purchase-items.options',
        'namespace' => NULL,
        'prefix' => 'pharmacy/selectors',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.customers.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/selectors/customers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\CustomerController@choices',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\CustomerController@choices',
        'as' => 'pharmacy.customers.options',
        'namespace' => NULL,
        'prefix' => 'pharmacy/selectors',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.customers.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/customers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\CustomerController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\CustomerController@index',
        'as' => 'pharmacy.customers.index',
        'namespace' => NULL,
        'prefix' => 'pharmacy/customers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.customers.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/customers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\CustomerController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\CustomerController@store',
        'as' => 'pharmacy.customers.store',
        'namespace' => NULL,
        'prefix' => 'pharmacy/customers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.categories.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/categories',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.categories.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductCategoryController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductCategoryController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.categories.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/categories/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.categories.create',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductCategoryController@create',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductCategoryController@create',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.categories.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/categories',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.categories.store',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductCategoryController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductCategoryController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.categories.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/categories/{product_category}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.categories.edit',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductCategoryController@edit',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductCategoryController@edit',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.categories.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'pharmacy/categories/{product_category}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.categories.update',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductCategoryController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductCategoryController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.categories.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pharmacy/categories/{product_category}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.categories.destroy',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductCategoryController@destroy',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductCategoryController@destroy',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.dosages.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/dosages',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.dosages.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\DosageController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\DosageController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.dosages.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/dosages/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.dosages.create',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\DosageController@create',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\DosageController@create',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.dosages.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/dosages',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.dosages.store',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\DosageController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\DosageController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.dosages.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/dosages/{dosage}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.dosages.edit',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\DosageController@edit',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\DosageController@edit',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.dosages.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'pharmacy/dosages/{dosage}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.dosages.update',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\DosageController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\DosageController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.dosages.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pharmacy/dosages/{dosage}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.dosages.destroy',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\DosageController@destroy',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\DosageController@destroy',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.units.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/units',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.units.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\UnitController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\UnitController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.units.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/units/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.units.create',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\UnitController@create',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\UnitController@create',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.units.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/units',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.units.store',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\UnitController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\UnitController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.units.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/units/{unit}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.units.edit',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\UnitController@edit',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\UnitController@edit',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.units.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'pharmacy/units/{unit}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.units.update',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\UnitController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\UnitController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.units.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pharmacy/units/{unit}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.units.destroy',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\UnitController@destroy',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\UnitController@destroy',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.shelf.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/shelf',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.shelf.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ShelfController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ShelfController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.shelf.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/shelf/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.shelf.create',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ShelfController@create',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ShelfController@create',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.shelf.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/shelf',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.shelf.store',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ShelfController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ShelfController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.shelf.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/shelf/{shelf}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.shelf.edit',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ShelfController@edit',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ShelfController@edit',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.shelf.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'pharmacy/shelf/{shelf}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.shelf.update',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ShelfController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ShelfController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.shelf.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pharmacy/shelf/{shelf}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.shelf.destroy',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ShelfController@destroy',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ShelfController@destroy',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.suppliers.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/suppliers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.suppliers.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SupplierController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SupplierController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.suppliers.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/suppliers/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.suppliers.create',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SupplierController@create',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SupplierController@create',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.suppliers.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/suppliers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.suppliers.store',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SupplierController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SupplierController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.suppliers.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/suppliers/{supplier}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.suppliers.edit',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SupplierController@edit',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SupplierController@edit',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.suppliers.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'pharmacy/suppliers/{supplier}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.suppliers.update',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SupplierController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SupplierController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.suppliers.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pharmacy/suppliers/{supplier}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.suppliers.destroy',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SupplierController@destroy',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SupplierController@destroy',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.products.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/products',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.products.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.products.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/products/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.products.create',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductController@create',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductController@create',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.products.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/products',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.products.store',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.products.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/products/{product}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.products.edit',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductController@edit',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductController@edit',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.products.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'pharmacy/products/{product}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.products.update',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.products.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pharmacy/products/{product}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.products.destroy',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductController@destroy',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductController@destroy',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.variants.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/variants',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.variants.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductVariantController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductVariantController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.variants.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/variants/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.variants.create',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductVariantController@create',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductVariantController@create',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.variants.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/variants',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.variants.store',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductVariantController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductVariantController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.variants.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/variants/{product_variant}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.variants.edit',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductVariantController@edit',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductVariantController@edit',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.variants.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'pharmacy/variants/{product_variant}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.variants.update',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductVariantController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductVariantController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.variants.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pharmacy/variants/{product_variant}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.variants.destroy',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductVariantController@destroy',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductVariantController@destroy',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.product-units.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/product-units',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.product-units.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductUnitController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductUnitController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.product-units.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/product-units/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.product-units.create',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductUnitController@create',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductUnitController@create',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.product-units.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/product-units',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.product-units.store',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductUnitController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductUnitController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.product-units.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/product-units/{product_unit}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.product-units.edit',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductUnitController@edit',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductUnitController@edit',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.product-units.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'pharmacy/product-units/{product_unit}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.product-units.update',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductUnitController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductUnitController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.product-units.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pharmacy/product-units/{product_unit}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.product-units.destroy',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductUnitController@destroy',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\ProductUnitController@destroy',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sales.void' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/sales/{sale}/void',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleController@reverse',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleController@reverse',
        'as' => 'pharmacy.sales.void',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sales.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/sales',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sales.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sales.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/sales/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sales.create',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleController@create',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleController@create',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sales.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/sales',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sales.store',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sales.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/sales/{sale}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sales.show',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleController@show',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleController@show',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sales.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/sales/{sale}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sales.edit',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleController@edit',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleController@edit',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sales.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'pharmacy/sales/{sale}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sales.update',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sales.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pharmacy/sales/{sale}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sales.destroy',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleController@destroy',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleController@destroy',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchases.void' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/purchases/{purchase}/void',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseController@reverse',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseController@reverse',
        'as' => 'pharmacy.purchases.void',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchases.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/purchases',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchases.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchases.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/purchases/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchases.create',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseController@create',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseController@create',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchases.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/purchases',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchases.store',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchases.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/purchases/{purchase}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchases.show',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseController@show',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseController@show',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchases.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/purchases/{purchase}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchases.edit',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseController@edit',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseController@edit',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchases.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'pharmacy/purchases/{purchase}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchases.update',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchases.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pharmacy/purchases/{purchase}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchases.destroy',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseController@destroy',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseController@destroy',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sale-returns.void' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/sale-returns/{sale_return}/void',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleReturnController@reverse',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleReturnController@reverse',
        'as' => 'pharmacy.sale-returns.void',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sale-returns.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/sale-returns',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sale-returns.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleReturnController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleReturnController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sale-returns.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/sale-returns/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sale-returns.create',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleReturnController@create',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleReturnController@create',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sale-returns.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/sale-returns',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sale-returns.store',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleReturnController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleReturnController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sale-returns.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/sale-returns/{sale_return}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sale-returns.show',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleReturnController@show',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleReturnController@show',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sale-returns.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/sale-returns/{sale_return}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sale-returns.edit',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleReturnController@edit',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleReturnController@edit',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sale-returns.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'pharmacy/sale-returns/{sale_return}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sale-returns.update',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleReturnController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleReturnController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sale-returns.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pharmacy/sale-returns/{sale_return}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sale-returns.destroy',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleReturnController@destroy',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SaleReturnController@destroy',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchase-returns.void' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/purchase-returns/{purchase_return}/void',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseReturnController@reverse',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseReturnController@reverse',
        'as' => 'pharmacy.purchase-returns.void',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchase-returns.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/purchase-returns',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchase-returns.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseReturnController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseReturnController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchase-returns.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/purchase-returns/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchase-returns.create',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseReturnController@create',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseReturnController@create',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchase-returns.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/purchase-returns',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchase-returns.store',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseReturnController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseReturnController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchase-returns.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/purchase-returns/{purchase_return}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchase-returns.show',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseReturnController@show',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseReturnController@show',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchase-returns.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/purchase-returns/{purchase_return}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchase-returns.edit',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseReturnController@edit',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseReturnController@edit',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchase-returns.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'pharmacy/purchase-returns/{purchase_return}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchase-returns.update',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseReturnController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseReturnController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchase-returns.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pharmacy/purchase-returns/{purchase_return}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchase-returns.destroy',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseReturnController@destroy',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchaseReturnController@destroy',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.opening-stock.void' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/opening-stock/{opening_stock}/void',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\OpeningStockController@reverse',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\OpeningStockController@reverse',
        'as' => 'pharmacy.opening-stock.void',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.opening-stock.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/opening-stock',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.opening-stock.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\OpeningStockController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\OpeningStockController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.opening-stock.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/opening-stock/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.opening-stock.create',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\OpeningStockController@create',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\OpeningStockController@create',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.opening-stock.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/opening-stock',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.opening-stock.store',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\OpeningStockController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\OpeningStockController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.opening-stock.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/opening-stock/{opening_stock}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.opening-stock.show',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\OpeningStockController@show',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\OpeningStockController@show',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.adjustments.void' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/adjustments/{adjustment}/void',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\AdjustmentController@reverse',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\AdjustmentController@reverse',
        'as' => 'pharmacy.adjustments.void',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.adjustments.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/adjustments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.adjustments.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\AdjustmentController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\AdjustmentController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.adjustments.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/adjustments/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.adjustments.create',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\AdjustmentController@create',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\AdjustmentController@create',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.adjustments.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/adjustments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.adjustments.store',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\AdjustmentController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\AdjustmentController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.adjustments.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/adjustments/{adjustment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.adjustments.show',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\AdjustmentController@show',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\AdjustmentController@show',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.damage.void' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/damage/{wastage}/void',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\WastageController@reverse',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\WastageController@reverse',
        'as' => 'pharmacy.damage.void',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.damage.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/damage',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.damage.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\WastageController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\WastageController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.damage.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/damage/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.damage.create',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\WastageController@create',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\WastageController@create',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.damage.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/damage',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.damage.store',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\WastageController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\WastageController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.damage.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/damage/{wastage}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.damage.show',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\WastageController@show',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\WastageController@show',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.transfers.void' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/transfers/{transfer}/void',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\TransferController@reverse',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\TransferController@reverse',
        'as' => 'pharmacy.transfers.void',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.transfers.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/transfers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.transfers.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\TransferController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\TransferController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.transfers.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/transfers/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.transfers.create',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\TransferController@create',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\TransferController@create',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.transfers.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/transfers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.transfers.store',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\TransferController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\TransferController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.transfers.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/transfers/{transfer}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.transfers.show',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\TransferController@show',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\TransferController@show',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sale-payments.invoices' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/sale-payments/invoices',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SalePaymentController@invoices',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SalePaymentController@invoices',
        'as' => 'pharmacy.sale-payments.invoices',
        'namespace' => NULL,
        'prefix' => 'pharmacy/sale-payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sale-payments.customers' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/sale-payments/customers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SalePaymentController@customers',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SalePaymentController@customers',
        'as' => 'pharmacy.sale-payments.customers',
        'namespace' => NULL,
        'prefix' => 'pharmacy/sale-payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sale-payments.void' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/sale-payments/{sale_payment}/void',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SalePaymentController@reverse',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SalePaymentController@reverse',
        'as' => 'pharmacy.sale-payments.void',
        'namespace' => NULL,
        'prefix' => 'pharmacy/sale-payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sale-payments.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/sale-payments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sale-payments.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SalePaymentController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SalePaymentController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sale-payments.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/sale-payments/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sale-payments.create',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SalePaymentController@create',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SalePaymentController@create',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sale-payments.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/sale-payments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sale-payments.store',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SalePaymentController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SalePaymentController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sale-payments.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/sale-payments/{sale_payment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sale-payments.show',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SalePaymentController@show',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SalePaymentController@show',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.sale-payments.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pharmacy/sale-payments/{sale_payment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.sale-payments.destroy',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\SalePaymentController@destroy',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\SalePaymentController@destroy',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchase-payments.invoices' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/purchase-payments/invoices',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchasePaymentController@invoices',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchasePaymentController@invoices',
        'as' => 'pharmacy.purchase-payments.invoices',
        'namespace' => NULL,
        'prefix' => 'pharmacy/purchase-payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchase-payments.suppliers' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/purchase-payments/suppliers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchasePaymentController@suppliers',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchasePaymentController@suppliers',
        'as' => 'pharmacy.purchase-payments.suppliers',
        'namespace' => NULL,
        'prefix' => 'pharmacy/purchase-payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchase-payments.void' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/purchase-payments/{purchase_payment}/void',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchasePaymentController@reverse',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchasePaymentController@reverse',
        'as' => 'pharmacy.purchase-payments.void',
        'namespace' => NULL,
        'prefix' => 'pharmacy/purchase-payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchase-payments.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/purchase-payments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchase-payments.index',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchasePaymentController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchasePaymentController@index',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchase-payments.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/purchase-payments/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchase-payments.create',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchasePaymentController@create',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchasePaymentController@create',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchase-payments.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pharmacy/purchase-payments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchase-payments.store',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchasePaymentController@store',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchasePaymentController@store',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchase-payments.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/purchase-payments/{purchase_payment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchase-payments.show',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchasePaymentController@show',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchasePaymentController@show',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.purchase-payments.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pharmacy/purchase-payments/{purchase_payment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'as' => 'pharmacy.purchase-payments.destroy',
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchasePaymentController@destroy',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\PurchasePaymentController@destroy',
        'namespace' => NULL,
        'prefix' => 'pharmacy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.reports.cash-balance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/reports/cash-balance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\CashBalanceReportController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\CashBalanceReportController@index',
        'as' => 'pharmacy.reports.cash-balance',
        'namespace' => NULL,
        'prefix' => 'pharmacy/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.reports.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/reports',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\StockReportController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\StockReportController@index',
        'as' => 'pharmacy.reports.index',
        'namespace' => NULL,
        'prefix' => 'pharmacy/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.reports.stock' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/reports/stock',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\StockReportController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\StockReportController@index',
        'as' => 'pharmacy.reports.stock',
        'namespace' => NULL,
        'prefix' => 'pharmacy/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.reports.movements' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/reports/movements',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\StockMovementReportController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\StockMovementReportController@index',
        'as' => 'pharmacy.reports.movements',
        'namespace' => NULL,
        'prefix' => 'pharmacy/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.reports.expiry' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/reports/expiry',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\ExpiryReportController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\ExpiryReportController@index',
        'as' => 'pharmacy.reports.expiry',
        'namespace' => NULL,
        'prefix' => 'pharmacy/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.reports.profit-loss' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/reports/profit-loss',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\ProfitLossReportController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\ProfitLossReportController@index',
        'as' => 'pharmacy.reports.profit-loss',
        'namespace' => NULL,
        'prefix' => 'pharmacy/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.reports.purchase-discounts' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/reports/purchase-discounts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\PurchaseDiscountReportController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\PurchaseDiscountReportController@index',
        'as' => 'pharmacy.reports.purchase-discounts',
        'namespace' => NULL,
        'prefix' => 'pharmacy/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.reports.daily' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/reports/daily',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\DailyReportController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\DailyReportController@index',
        'as' => 'pharmacy.reports.daily',
        'namespace' => NULL,
        'prefix' => 'pharmacy/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.reports.customer-dues' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/reports/customer-dues',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\CustomerDueReportController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\CustomerDueReportController@index',
        'as' => 'pharmacy.reports.customer-dues',
        'namespace' => NULL,
        'prefix' => 'pharmacy/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pharmacy.reports.supplier-dues' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pharmacy/reports/supplier-dues',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'access',
          4 => 'subscribed',
          5 => 'branch:required',
          6 => 'pharmacy.permission',
        ),
        'uses' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\SupplierDueReportController@index',
        'controller' => 'Modules\\Pharmacy\\Http\\Controllers\\Reports\\SupplierDueReportController@index',
        'as' => 'pharmacy.reports.supplier-dues',
        'namespace' => NULL,
        'prefix' => 'pharmacy/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.options.selector' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription/options/selector',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
        ),
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionOptionController@options',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionOptionController@options',
        'as' => 'prescription.options.selector',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.options.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription/options',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
        ),
        'as' => 'prescription.options.index',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionOptionController@index',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionOptionController@index',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.options.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription/options/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
        ),
        'as' => 'prescription.options.create',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionOptionController@create',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionOptionController@create',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.options.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'prescription/options',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
        ),
        'as' => 'prescription.options.store',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionOptionController@store',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionOptionController@store',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.options.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription/options/{prescription_option}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
        ),
        'as' => 'prescription.options.edit',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionOptionController@edit',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionOptionController@edit',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.options.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'prescription/options/{prescription_option}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
        ),
        'as' => 'prescription.options.update',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionOptionController@store',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionOptionController@store',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.options.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'prescription/options/{prescription_option}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
        ),
        'as' => 'prescription.options.destroy',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionOptionController@destroy',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionOptionController@destroy',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.prescription-dose.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription/prescription-dose',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
        ),
        'as' => 'prescription.prescription-dose.index',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionDoseController@index',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionDoseController@index',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.prescription-dose.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription/prescription-dose/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
        ),
        'as' => 'prescription.prescription-dose.create',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionDoseController@create',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionDoseController@create',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.prescription-dose.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'prescription/prescription-dose',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
        ),
        'as' => 'prescription.prescription-dose.store',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionDoseController@store',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionDoseController@store',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.prescription-dose.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription/prescription-dose/{prescription_dose}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
        ),
        'as' => 'prescription.prescription-dose.edit',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionDoseController@edit',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionDoseController@edit',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.prescription-dose.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'prescription/prescription-dose/{prescription_dose}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
        ),
        'as' => 'prescription.prescription-dose.update',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionDoseController@store',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionDoseController@store',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.prescription-dose.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'prescription/prescription-dose/{prescription_dose}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
        ),
        'as' => 'prescription.prescription-dose.destroy',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionDoseController@destroy',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionDoseController@destroy',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.settings.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
        ),
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\SettingController@index',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\SettingController@index',
        'as' => 'prescription.settings.index',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.settings.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'prescription/settings/{setting}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
        ),
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\SettingController@store',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\SettingController@store',
        'as' => 'prescription.settings.update',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.patients.modal' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription/patients/modal',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@modal',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@modal',
        'as' => 'prescription.patients.modal',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.patients.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'prescription/patients',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@store',
        'as' => 'prescription.patients.store',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.glass-patients.modal' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription/glass-patients/modal',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@modal',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@modal',
        'as' => 'prescription.glass-patients.modal',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.glass-patients.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'prescription/glass-patients',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'uses' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@store',
        'controller' => 'Modules\\Hospital\\Http\\Controllers\\PatientController@store',
        'as' => 'prescription.glass-patients.store',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\DashboardController@index',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\DashboardController@index',
        'as' => 'prescription.dashboard',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.prescriptions.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription/prescriptions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'as' => 'prescription.prescriptions.index',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionController@index',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionController@index',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.prescriptions.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription/prescriptions/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'as' => 'prescription.prescriptions.create',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionController@create',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionController@create',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.prescriptions.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'prescription/prescriptions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'as' => 'prescription.prescriptions.store',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionController@store',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionController@store',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.prescriptions.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription/prescriptions/{prescription}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'as' => 'prescription.prescriptions.show',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionController@show',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionController@show',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.prescriptions.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription/prescriptions/{prescription}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'as' => 'prescription.prescriptions.edit',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionController@edit',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionController@edit',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.prescriptions.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'prescription/prescriptions/{prescription}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'as' => 'prescription.prescriptions.update',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionController@store',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionController@store',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.prescriptions.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'prescription/prescriptions/{prescription}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'as' => 'prescription.prescriptions.destroy',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionController@destroy',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\PrescriptionController@destroy',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.glass-prescriptions.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription/glass-prescriptions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'as' => 'prescription.glass-prescriptions.index',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\GlassPrescriptionController@index',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\GlassPrescriptionController@index',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.glass-prescriptions.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription/glass-prescriptions/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'as' => 'prescription.glass-prescriptions.create',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\GlassPrescriptionController@create',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\GlassPrescriptionController@create',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.glass-prescriptions.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'prescription/glass-prescriptions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'as' => 'prescription.glass-prescriptions.store',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\GlassPrescriptionController@store',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\GlassPrescriptionController@store',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.glass-prescriptions.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription/glass-prescriptions/{glass_prescription}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'as' => 'prescription.glass-prescriptions.show',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\GlassPrescriptionController@show',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\GlassPrescriptionController@show',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.glass-prescriptions.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'prescription/glass-prescriptions/{glass_prescription}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'as' => 'prescription.glass-prescriptions.edit',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\GlassPrescriptionController@edit',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\GlassPrescriptionController@edit',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.glass-prescriptions.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'prescription/glass-prescriptions/{glass_prescription}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'as' => 'prescription.glass-prescriptions.update',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\GlassPrescriptionController@store',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\GlassPrescriptionController@store',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'prescription.glass-prescriptions.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'prescription/glass-prescriptions/{glass_prescription}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'subscribed',
          4 => 'prescription.permission',
          5 => 'branch:required',
        ),
        'as' => 'prescription.glass-prescriptions.destroy',
        'uses' => 'Modules\\Prescription\\Http\\Controllers\\GlassPrescriptionController@destroy',
        'controller' => 'Modules\\Prescription\\Http\\Controllers\\GlassPrescriptionController@destroy',
        'namespace' => NULL,
        'prefix' => 'prescription',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.module-dashboard.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'module-dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
        ),
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ModuleDashboardController@index',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ModuleDashboardController@index',
        'as' => 'tenantcore.module-dashboard.index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'workspace',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\DashboardController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\DashboardController@index',
        'as' => 'tenantcore.dashboard',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.sms.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'sms',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
        ),
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\SmsController@index',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\SmsController@index',
        'as' => 'tenantcore.sms.index',
        'namespace' => NULL,
        'prefix' => '/sms',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.sms-recharges.proof' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'sms/recharges/{sms_recharge}/proof',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
        ),
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\SmsRechargeController@proof',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\SmsRechargeController@proof',
        'as' => 'tenantcore.sms-recharges.proof',
        'namespace' => NULL,
        'prefix' => '/sms',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.sms-recharges.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'sms/recharges',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
        ),
        'as' => 'tenantcore.sms-recharges.index',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\SmsRechargeController@index',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\SmsRechargeController@index',
        'namespace' => NULL,
        'prefix' => '/sms',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.sms-recharges.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'sms/recharges/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
        ),
        'as' => 'tenantcore.sms-recharges.create',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\SmsRechargeController@create',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\SmsRechargeController@create',
        'namespace' => NULL,
        'prefix' => '/sms',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.sms-recharges.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'sms/recharges',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
        ),
        'as' => 'tenantcore.sms-recharges.store',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\SmsRechargeController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\SmsRechargeController@store',
        'namespace' => NULL,
        'prefix' => '/sms',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.branch-dashboard.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'branch-dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
        ),
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\BranchDashboardController@index',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\BranchDashboardController@index',
        'as' => 'tenantcore.branch-dashboard.index',
        'namespace' => NULL,
        'prefix' => '/branch-dashboard',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.branch-dashboard.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'branch-dashboard/{branch}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
        ),
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\BranchDashboardController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\BranchDashboardController@store',
        'as' => 'tenantcore.branch-dashboard.store',
        'namespace' => NULL,
        'prefix' => '/branch-dashboard',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.branch-dashboard.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'branch-dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
        ),
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\BranchDashboardController@destroy',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\BranchDashboardController@destroy',
        'as' => 'tenantcore.branch-dashboard.destroy',
        'namespace' => NULL,
        'prefix' => '/branch-dashboard',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.divisions.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'divisions/options',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
        ),
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\DivisionController@options',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\DivisionController@options',
        'as' => 'tenantcore.divisions.options',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.districts.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'districts/options',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
        ),
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\DistrictController@options',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\DistrictController@options',
        'as' => 'tenantcore.districts.options',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.thanas.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'thanas/options',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
        ),
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ThanaController@options',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ThanaController@options',
        'as' => 'tenantcore.thanas.options',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.income-heads.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'income-heads/options',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
        ),
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeHeadController@options',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeHeadController@options',
        'as' => 'tenantcore.income-heads.options',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.expense-heads.options' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'expense-heads/options',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
        ),
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseHeadController@options',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseHeadController@options',
        'as' => 'tenantcore.expense-heads.options',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.audit-logs.branches' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'audit-logs/branches',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
        ),
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\AuditLogController@branches',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\AuditLogController@branches',
        'as' => 'tenantcore.audit-logs.branches',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.income-heads.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'income-heads',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.income-heads.index',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeHeadController@index',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeHeadController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.income-heads.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'income-heads/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.income-heads.create',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeHeadController@create',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeHeadController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.income-heads.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'income-heads',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.income-heads.store',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeHeadController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeHeadController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.income-heads.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'income-heads/{income_head}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.income-heads.edit',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeHeadController@edit',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeHeadController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.income-heads.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'income-heads/{income_head}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.income-heads.update',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeHeadController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeHeadController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.income-heads.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'income-heads/{income_head}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.income-heads.destroy',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeHeadController@destroy',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeHeadController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.expense-heads.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'expense-heads',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.expense-heads.index',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseHeadController@index',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseHeadController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.expense-heads.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'expense-heads/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.expense-heads.create',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseHeadController@create',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseHeadController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.expense-heads.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'expense-heads',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.expense-heads.store',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseHeadController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseHeadController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.expense-heads.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'expense-heads/{expense_head}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.expense-heads.edit',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseHeadController@edit',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseHeadController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.expense-heads.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'expense-heads/{expense_head}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.expense-heads.update',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseHeadController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseHeadController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.expense-heads.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'expense-heads/{expense_head}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.expense-heads.destroy',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseHeadController@destroy',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseHeadController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.reports.daily' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'reports/daily',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
          5 => 'branch:required',
        ),
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\Reports\\DailyReportController@index',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\Reports\\DailyReportController@index',
        'as' => 'tenantcore.reports.daily',
        'namespace' => NULL,
        'prefix' => '/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.reports.cash' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'reports/cash',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
          5 => 'branch:required',
        ),
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\Reports\\CashReportController@index',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\Reports\\CashReportController@index',
        'as' => 'tenantcore.reports.cash',
        'namespace' => NULL,
        'prefix' => '/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.reports.monthly-cash' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'reports/monthly-cash',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
          5 => 'branch:required',
        ),
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\Reports\\MonthlyCashReportController@index',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\Reports\\MonthlyCashReportController@index',
        'as' => 'tenantcore.reports.monthly-cash',
        'namespace' => NULL,
        'prefix' => '/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.incomes.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'incomes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
          5 => 'branch:required',
        ),
        'as' => 'tenantcore.incomes.index',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeController@index',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.incomes.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'incomes/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
          5 => 'branch:required',
        ),
        'as' => 'tenantcore.incomes.create',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeController@create',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.incomes.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'incomes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
          5 => 'branch:required',
        ),
        'as' => 'tenantcore.incomes.store',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.incomes.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'incomes/{income}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
          5 => 'branch:required',
        ),
        'as' => 'tenantcore.incomes.edit',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeController@edit',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.incomes.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'incomes/{income}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
          5 => 'branch:required',
        ),
        'as' => 'tenantcore.incomes.update',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.incomes.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'incomes/{income}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
          5 => 'branch:required',
        ),
        'as' => 'tenantcore.incomes.destroy',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeController@destroy',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\IncomeController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.expenses.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'expenses',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
          5 => 'branch:required',
        ),
        'as' => 'tenantcore.expenses.index',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseController@index',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.expenses.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'expenses/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
          5 => 'branch:required',
        ),
        'as' => 'tenantcore.expenses.create',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseController@create',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.expenses.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'expenses',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
          5 => 'branch:required',
        ),
        'as' => 'tenantcore.expenses.store',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.expenses.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'expenses/{expense}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
          5 => 'branch:required',
        ),
        'as' => 'tenantcore.expenses.edit',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseController@edit',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.expenses.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'expenses/{expense}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
          5 => 'branch:required',
        ),
        'as' => 'tenantcore.expenses.update',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.expenses.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'expenses/{expense}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
          5 => 'branch:required',
        ),
        'as' => 'tenantcore.expenses.destroy',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseController@destroy',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ExpenseController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.audit-logs.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'audit-logs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.audit-logs.index',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\AuditLogController@index',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\AuditLogController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.audit-logs.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'audit-logs/{audit_log}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.audit-logs.show',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\AuditLogController@show',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\AuditLogController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.branches.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'branches',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.branches.index',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\BranchController@index',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\BranchController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.branches.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'branches/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.branches.create',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\BranchController@create',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\BranchController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.branches.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'branches',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.branches.store',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\BranchController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\BranchController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.branches.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'branches/{branch}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.branches.edit',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\BranchController@edit',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\BranchController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.branches.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'branches/{branch}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.branches.update',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\BranchController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\BranchController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.branches.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'branches/{branch}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.branches.destroy',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\BranchController@destroy',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\BranchController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.divisions.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'divisions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.divisions.index',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\DivisionController@index',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\DivisionController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.divisions.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'divisions/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.divisions.create',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\DivisionController@create',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\DivisionController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.divisions.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'divisions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.divisions.store',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\DivisionController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\DivisionController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.divisions.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'divisions/{division}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.divisions.edit',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\DivisionController@edit',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\DivisionController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.divisions.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'divisions/{division}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.divisions.update',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\DivisionController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\DivisionController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.divisions.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'divisions/{division}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.divisions.destroy',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\DivisionController@destroy',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\DivisionController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.districts.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'districts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.districts.index',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\DistrictController@index',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\DistrictController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.districts.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'districts/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.districts.create',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\DistrictController@create',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\DistrictController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.districts.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'districts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.districts.store',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\DistrictController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\DistrictController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.districts.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'districts/{district}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.districts.edit',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\DistrictController@edit',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\DistrictController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.districts.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'districts/{district}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.districts.update',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\DistrictController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\DistrictController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.districts.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'districts/{district}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.districts.destroy',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\DistrictController@destroy',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\DistrictController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.thanas.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'thanas',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.thanas.index',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ThanaController@index',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ThanaController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.thanas.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'thanas/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.thanas.create',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ThanaController@create',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ThanaController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.thanas.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'thanas',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.thanas.store',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ThanaController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ThanaController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.thanas.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'thanas/{thana}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.thanas.edit',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ThanaController@edit',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ThanaController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.thanas.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'thanas/{thana}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.thanas.update',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ThanaController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ThanaController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.thanas.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'thanas/{thana}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.thanas.destroy',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\ThanaController@destroy',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\ThanaController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.payment-types.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'payment-types',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.payment-types.index',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\PaymentTypeController@index',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\PaymentTypeController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.payment-types.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'payment-types/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.payment-types.create',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\PaymentTypeController@create',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\PaymentTypeController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.payment-types.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'payment-types',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.payment-types.store',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\PaymentTypeController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\PaymentTypeController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.payment-types.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'payment-types/{payment_type}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.payment-types.edit',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\PaymentTypeController@edit',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\PaymentTypeController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.payment-types.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'payment-types/{payment_type}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.payment-types.update',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\PaymentTypeController@store',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\PaymentTypeController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.payment-types.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'payment-types/{payment_type}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.payment-types.destroy',
        'uses' => 'Modules\\TenantCore\\Http\\Controllers\\PaymentTypeController@destroy',
        'controller' => 'Modules\\TenantCore\\Http\\Controllers\\PaymentTypeController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.accounts.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.accounts.index',
        'uses' => 'Modules\\Core\\Http\\Controllers\\AccountController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\AccountController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.accounts.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'users/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.accounts.create',
        'uses' => 'Modules\\Core\\Http\\Controllers\\AccountController@create',
        'controller' => 'Modules\\Core\\Http\\Controllers\\AccountController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.accounts.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.accounts.store',
        'uses' => 'Modules\\Core\\Http\\Controllers\\AccountController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\AccountController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.accounts.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'users/{account}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.accounts.edit',
        'uses' => 'Modules\\Core\\Http\\Controllers\\AccountController@edit',
        'controller' => 'Modules\\Core\\Http\\Controllers\\AccountController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.accounts.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'users/{account}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.accounts.update',
        'uses' => 'Modules\\Core\\Http\\Controllers\\AccountController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\AccountController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.accounts.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'users/{account}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.accounts.destroy',
        'uses' => 'Modules\\Core\\Http\\Controllers\\AccountController@destroy',
        'controller' => 'Modules\\Core\\Http\\Controllers\\AccountController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.settings.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.settings.index',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SettingController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SettingController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.settings.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'settings/{setting}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.settings.edit',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SettingController@edit',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SettingController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.settings.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'settings/{setting}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'subscribed',
        ),
        'as' => 'tenantcore.settings.update',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SettingController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SettingController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.billing.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'billing',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\BillingController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\BillingController@index',
        'as' => 'tenantcore.billing.index',
        'namespace' => NULL,
        'prefix' => '/billing',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.billing.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'billing/invoices/{invoice}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@show',
        'controller' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@show',
        'as' => 'tenantcore.billing.show',
        'namespace' => NULL,
        'prefix' => '/billing',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tenantcore.billing.checkouts.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'billing/invoices/{invoice}/checkouts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'tenant',
          3 => 'tenantcore.permission',
          4 => 'throttle:checkout',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\CheckoutController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\CheckoutController@store',
        'as' => 'tenantcore.billing.checkouts.store',
        'namespace' => NULL,
        'prefix' => '/billing',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.deploy.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/deploy',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
          4 => 'can:deploy-server',
          5 => 'throttle:600,1',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\DeploymentController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\DeploymentController@index',
        'as' => 'core.deploy.index',
        'namespace' => NULL,
        'prefix' => 'core/deploy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.deploy.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'core/deploy',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
          4 => 'can:deploy-server',
          5 => 'throttle:600,1',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\DeploymentController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\DeploymentController@store',
        'as' => 'core.deploy.store',
        'namespace' => NULL,
        'prefix' => 'core/deploy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.deploy.run' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'core/deploy/run',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
          4 => 'can:deploy-server',
          5 => 'throttle:600,1',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\DeploymentController@run',
        'controller' => 'Modules\\Core\\Http\\Controllers\\DeploymentController@run',
        'as' => 'core.deploy.run',
        'namespace' => NULL,
        'prefix' => 'core/deploy',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.tenants.enter' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'core/tenants/{tenant}/enter',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
          4 => 'throttle:10,1',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\TenantController@enter',
        'controller' => 'Modules\\Core\\Http\\Controllers\\TenantController@enter',
        'as' => 'core.tenants.enter',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.tenants.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/tenants',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.tenants.index',
        'uses' => 'Modules\\Core\\Http\\Controllers\\TenantController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\TenantController@index',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.tenants.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/tenants/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.tenants.create',
        'uses' => 'Modules\\Core\\Http\\Controllers\\TenantController@create',
        'controller' => 'Modules\\Core\\Http\\Controllers\\TenantController@create',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.tenants.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'core/tenants',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.tenants.store',
        'uses' => 'Modules\\Core\\Http\\Controllers\\TenantController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\TenantController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.tenants.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/tenants/{tenant}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.tenants.edit',
        'uses' => 'Modules\\Core\\Http\\Controllers\\TenantController@edit',
        'controller' => 'Modules\\Core\\Http\\Controllers\\TenantController@edit',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.tenants.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'core/tenants/{tenant}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.tenants.update',
        'uses' => 'Modules\\Core\\Http\\Controllers\\TenantController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\TenantController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.tenants.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'core/tenants/{tenant}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.tenants.destroy',
        'uses' => 'Modules\\Core\\Http\\Controllers\\TenantController@destroy',
        'controller' => 'Modules\\Core\\Http\\Controllers\\TenantController@destroy',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.accounts.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/admins',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.accounts.index',
        'uses' => 'Modules\\Core\\Http\\Controllers\\AccountController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\AccountController@index',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.accounts.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/admins/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.accounts.create',
        'uses' => 'Modules\\Core\\Http\\Controllers\\AccountController@create',
        'controller' => 'Modules\\Core\\Http\\Controllers\\AccountController@create',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.accounts.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'core/admins',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.accounts.store',
        'uses' => 'Modules\\Core\\Http\\Controllers\\AccountController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\AccountController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.accounts.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/admins/{account}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.accounts.edit',
        'uses' => 'Modules\\Core\\Http\\Controllers\\AccountController@edit',
        'controller' => 'Modules\\Core\\Http\\Controllers\\AccountController@edit',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.accounts.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'core/admins/{account}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.accounts.update',
        'uses' => 'Modules\\Core\\Http\\Controllers\\AccountController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\AccountController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.accounts.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'core/admins/{account}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.accounts.destroy',
        'uses' => 'Modules\\Core\\Http\\Controllers\\AccountController@destroy',
        'controller' => 'Modules\\Core\\Http\\Controllers\\AccountController@destroy',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.plans.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/plans',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.plans.index',
        'uses' => 'Modules\\Core\\Http\\Controllers\\PlanController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\PlanController@index',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.plans.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/plans/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.plans.create',
        'uses' => 'Modules\\Core\\Http\\Controllers\\PlanController@create',
        'controller' => 'Modules\\Core\\Http\\Controllers\\PlanController@create',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.plans.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'core/plans',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.plans.store',
        'uses' => 'Modules\\Core\\Http\\Controllers\\PlanController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\PlanController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.plans.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/plans/{plan}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.plans.edit',
        'uses' => 'Modules\\Core\\Http\\Controllers\\PlanController@edit',
        'controller' => 'Modules\\Core\\Http\\Controllers\\PlanController@edit',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.plans.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'core/plans/{plan}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.plans.update',
        'uses' => 'Modules\\Core\\Http\\Controllers\\PlanController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\PlanController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.plans.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'core/plans/{plan}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.plans.destroy',
        'uses' => 'Modules\\Core\\Http\\Controllers\\PlanController@destroy',
        'controller' => 'Modules\\Core\\Http\\Controllers\\PlanController@destroy',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.plan-permissions.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/plan-permissions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.plan-permissions.index',
        'uses' => 'Modules\\Core\\Http\\Controllers\\PlanPermissionController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\PlanPermissionController@index',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.plan-permissions.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'core/plan-permissions/{plan}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.plan-permissions.update',
        'uses' => 'Modules\\Core\\Http\\Controllers\\PlanPermissionController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\PlanPermissionController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.subscriptions.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/subscriptions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.subscriptions.index',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SubscriptionController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SubscriptionController@index',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.subscriptions.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/subscriptions/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.subscriptions.create',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SubscriptionController@create',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SubscriptionController@create',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.subscriptions.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'core/subscriptions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.subscriptions.store',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SubscriptionController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SubscriptionController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.subscriptions.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/subscriptions/{subscription}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.subscriptions.edit',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SubscriptionController@edit',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SubscriptionController@edit',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.subscriptions.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'core/subscriptions/{subscription}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.subscriptions.update',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SubscriptionController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SubscriptionController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.invoices.generate' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'core/invoices/generate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@generate',
        'controller' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@generate',
        'as' => 'core.invoices.generate',
        'namespace' => NULL,
        'prefix' => 'core/invoices',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.invoices.issue' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'core/invoices/{invoice}/issue',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@issue',
        'controller' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@issue',
        'as' => 'core.invoices.issue',
        'namespace' => NULL,
        'prefix' => 'core/invoices',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.invoices.void' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'core/invoices/{invoice}/void',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@void',
        'controller' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@void',
        'as' => 'core.invoices.void',
        'namespace' => NULL,
        'prefix' => 'core/invoices',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.invoices.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/invoices',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.invoices.index',
        'uses' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@index',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.invoices.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/invoices/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.invoices.create',
        'uses' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@create',
        'controller' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@create',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.invoices.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'core/invoices',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.invoices.store',
        'uses' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.invoices.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/invoices/{invoice}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.invoices.show',
        'uses' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@show',
        'controller' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@show',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.invoices.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/invoices/{invoice}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.invoices.edit',
        'uses' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@edit',
        'controller' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@edit',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.invoices.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'core/invoices/{invoice}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.invoices.update',
        'uses' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\InvoiceController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.payments.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/payments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.payments.index',
        'uses' => 'Modules\\Core\\Http\\Controllers\\PaymentController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\PaymentController@index',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.payments.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/payments/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.payments.create',
        'uses' => 'Modules\\Core\\Http\\Controllers\\PaymentController@create',
        'controller' => 'Modules\\Core\\Http\\Controllers\\PaymentController@create',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.payments.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'core/payments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.payments.store',
        'uses' => 'Modules\\Core\\Http\\Controllers\\PaymentController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\PaymentController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.payments.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/payments/{payment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.payments.show',
        'uses' => 'Modules\\Core\\Http\\Controllers\\PaymentController@show',
        'controller' => 'Modules\\Core\\Http\\Controllers\\PaymentController@show',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.settings.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.settings.index',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SettingController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SettingController@index',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.settings.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/settings/{setting}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.settings.edit',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SettingController@edit',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SettingController@edit',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.settings.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'core/settings/{setting}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.settings.update',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SettingController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SettingController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.sms-accounts.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/sms-accounts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.sms-accounts.index',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SmsAccountController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SmsAccountController@index',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.sms-accounts.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/sms-accounts/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.sms-accounts.create',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SmsAccountController@create',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SmsAccountController@create',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.sms-accounts.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'core/sms-accounts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.sms-accounts.store',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SmsAccountController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SmsAccountController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.sms-accounts.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/sms-accounts/{sms_account}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.sms-accounts.edit',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SmsAccountController@edit',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SmsAccountController@edit',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.sms-accounts.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'core/sms-accounts/{sms_account}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.sms-accounts.update',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SmsAccountController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SmsAccountController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.sms-recharges.proof' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/sms-recharges/{sms_recharge}/proof',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\SmsRechargeController@proof',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SmsRechargeController@proof',
        'as' => 'core.sms-recharges.proof',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.sms-recharges.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/sms-recharges',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.sms-recharges.index',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SmsRechargeController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SmsRechargeController@index',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.sms-recharges.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/sms-recharges/{sms_recharge}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.sms-recharges.show',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SmsRechargeController@show',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SmsRechargeController@show',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.sms-recharges.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'core/sms-recharges/{sms_recharge}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.sms-recharges.update',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SmsRechargeController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SmsRechargeController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.sms-history.resolve' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'core/sms-history/{sms_message}/resolve',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\SmsMessageController@resolve',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SmsMessageController@resolve',
        'as' => 'core.sms-history.resolve',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.sms-history.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/sms-history',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.sms-history.index',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SmsMessageController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SmsMessageController@index',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.sms-history.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/sms-history/{sms_message}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'as' => 'core.sms-history.show',
        'uses' => 'Modules\\Core\\Http\\Controllers\\SmsMessageController@show',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SmsMessageController@show',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.checkouts.approve' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'core/checkouts/{checkout}/approve',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\CheckoutController@approve',
        'controller' => 'Modules\\Core\\Http\\Controllers\\CheckoutController@approve',
        'as' => 'core.checkouts.approve',
        'namespace' => NULL,
        'prefix' => 'core/checkouts/{checkout}',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.checkouts.reject' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'core/checkouts/{checkout}/reject',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'central',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\CheckoutController@reject',
        'controller' => 'Modules\\Core\\Http\\Controllers\\CheckoutController@reject',
        'as' => 'core.checkouts.reject',
        'namespace' => NULL,
        'prefix' => 'core/checkouts/{checkout}',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.roles.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/roles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'subscribed',
        ),
        'as' => 'core.roles.index',
        'uses' => 'Modules\\Core\\Http\\Controllers\\RoleController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\RoleController@index',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.roles.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/roles/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'subscribed',
        ),
        'as' => 'core.roles.create',
        'uses' => 'Modules\\Core\\Http\\Controllers\\RoleController@create',
        'controller' => 'Modules\\Core\\Http\\Controllers\\RoleController@create',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.roles.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'core/roles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'subscribed',
        ),
        'as' => 'core.roles.store',
        'uses' => 'Modules\\Core\\Http\\Controllers\\RoleController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\RoleController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.roles.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/roles/{role}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'subscribed',
        ),
        'as' => 'core.roles.edit',
        'uses' => 'Modules\\Core\\Http\\Controllers\\RoleController@edit',
        'controller' => 'Modules\\Core\\Http\\Controllers\\RoleController@edit',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.roles.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'core/roles/{role}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'subscribed',
        ),
        'as' => 'core.roles.update',
        'uses' => 'Modules\\Core\\Http\\Controllers\\RoleController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\RoleController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.roles.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'core/roles/{role}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'subscribed',
        ),
        'as' => 'core.roles.destroy',
        'uses' => 'Modules\\Core\\Http\\Controllers\\RoleController@destroy',
        'controller' => 'Modules\\Core\\Http\\Controllers\\RoleController@destroy',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.permissions.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'core/permissions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'subscribed',
        ),
        'as' => 'core.permissions.index',
        'uses' => 'Modules\\Core\\Http\\Controllers\\PermissionController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\PermissionController@index',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'core.permissions.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'core/permissions/{role}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'core.permission',
          3 => 'subscribed',
        ),
        'as' => 'core.permissions.update',
        'uses' => 'Modules\\Core\\Http\\Controllers\\PermissionController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\PermissionController@store',
        'namespace' => NULL,
        'prefix' => 'core',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'gateway.callback' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'POST',
        2 => 'HEAD',
      ),
      'uri' => 'core/payments/callback/{gateway}/{token}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:gateway-callback',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\GatewayController@callback',
        'controller' => 'Modules\\Core\\Http\\Controllers\\GatewayController@callback',
        'as' => 'gateway.callback',
        'namespace' => NULL,
        'prefix' => 'core/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'gateway.notify' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'core/payments/notify/{gateway}/{token}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:gateway-callback',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\GatewayController@notify',
        'controller' => 'Modules\\Core\\Http\\Controllers\\GatewayController@notify',
        'as' => 'gateway.notify',
        'namespace' => NULL,
        'prefix' => 'core/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::utbIabknBjMDHAHZ' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'up',
      'action' => 
      array (
        'uses' => 'Illuminate\\Foundation\\Http\\HealthCheck@__invoke',
        'controller' => 'Illuminate\\Foundation\\Http\\HealthCheck',
        'as' => 'generated::utbIabknBjMDHAHZ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'home' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '/',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'access',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\DashboardController@index',
        'controller' => 'Modules\\Core\\Http\\Controllers\\DashboardController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'home',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'auth.tenant-entry' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'auth/tenant-entry/{token}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'tenant',
          2 => 'signed:relative',
          3 => 'throttle:30,1',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\SessionController@enterTenant',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SessionController@enterTenant',
        'as' => 'auth.tenant-entry',
        'namespace' => NULL,
        'prefix' => '/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'auth.login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'auth/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\SessionController@create',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SessionController@create',
        'as' => 'auth.login',
        'namespace' => NULL,
        'prefix' => '/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'auth.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'auth/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
          2 => 'throttle:login',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\SessionController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SessionController@store',
        'as' => 'auth.store',
        'namespace' => NULL,
        'prefix' => '/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'auth.password.request' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'auth/forgot-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\PasswordController@create',
        'controller' => 'Modules\\Core\\Http\\Controllers\\PasswordController@create',
        'as' => 'auth.password.request',
        'namespace' => NULL,
        'prefix' => '/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'auth.password.email' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'auth/forgot-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
          2 => 'throttle:password-reset',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\PasswordController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\PasswordController@store',
        'as' => 'auth.password.email',
        'namespace' => NULL,
        'prefix' => '/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'auth.password.reset' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'auth/reset-password/{token}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\PasswordController@create',
        'controller' => 'Modules\\Core\\Http\\Controllers\\PasswordController@create',
        'as' => 'auth.password.reset',
        'namespace' => NULL,
        'prefix' => '/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'auth.password.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'auth/reset-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
          2 => 'throttle:password-reset',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\PasswordController@store',
        'controller' => 'Modules\\Core\\Http\\Controllers\\PasswordController@store',
        'as' => 'auth.password.update',
        'namespace' => NULL,
        'prefix' => '/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'auth.logout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'auth/logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'Modules\\Core\\Http\\Controllers\\SessionController@destroy',
        'controller' => 'Modules\\Core\\Http\\Controllers\\SessionController@destroy',
        'as' => 'auth.logout',
        'namespace' => NULL,
        'prefix' => '/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
  ),
)
);
