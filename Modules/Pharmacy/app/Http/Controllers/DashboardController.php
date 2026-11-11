<?php

namespace Modules\Pharmacy\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Hospital\Contracts\PharmacyCustomers;
use Modules\Pharmacy\Models\Product;
use Modules\Pharmacy\Models\Purchase;
use Modules\Pharmacy\Models\Sale;
use Modules\Pharmacy\Models\StockLot;

class DashboardController extends BaseController
{
    public function index(Request $request, PharmacyCustomers $pharmacyCustomers): View
    {
        $productType        = $request->query('product_type') === 'optic' ? 'optic' : 'medicine';
        $request->attributes->set('pharmacy_product_type', $productType);

        $actor              = $request->user('web');
        $permissions        = $request->attributes->get('navigation_permissions', []);
        $today              = now(config('app.timezone'));
        $todayIso           = $today->toDateString();
        $expiryBefore       = $today->copy()->addDays(30)->toDateString();

        $canViewSales       = isset($permissions['pharmacy-sales.view']);
        $canViewPurchases   = isset($permissions['pharmacy-purchases.view']);
        $canViewReports     = isset($permissions['pharmacy-reports.view']);
        $canViewProducts    = isset($permissions['pharmacy-products.view']);

        $salesTotal         = null;
        $salesCount         = null;
        $purchaseTotal      = null;
        $purchaseCount      = null;
        $customerDue        = null;
        $supplierDue        = null;
        $expiryCount        = null;
        $productCount       = null;
        $recentSales        = null;
        $recentPurchases    = null;
        $customers          = [];

        if ($canViewProducts) {
            $productCount = Product::where('type', $productType)->count();
        }

        if ($canViewSales) {
            $sales          = Sale::whereNull('voided_at')->where('product_type', $productType);
            $todaySales     = (clone $sales)->where('entry_date', $todayIso);
            $salesTotal     = (clone $todaySales)->sum('total');
            $salesCount     = $todaySales->count();
            $recentSales    = $sales->select(['id', 'number', 'entry_date', 'customer_id', 'total', 'outstanding'])->orderByDesc('entry_date')->orderByDesc('id')->limit(5)->get();

            $customerIds    = $recentSales->pluck('customer_id')->filter()->all();
            $customers      = $pharmacyCustomers->findMany($actor, $customerIds);
        }

        if ($canViewPurchases) {
            $purchases          = Purchase::whereNull('voided_at')->where('product_type', $productType);
            $todayPurchases     = (clone $purchases)->where('entry_date', $todayIso);
            $purchaseTotal      = (clone $todayPurchases)->sum('total');
            $purchaseCount      = $todayPurchases->count();
            $recentPurchases    = $purchases->select(['id', 'number', 'entry_date', 'supplier_id', 'total', 'outstanding'])
                ->with('supplier:id,name')->orderByDesc('entry_date')
                ->orderByDesc('id')->limit(5)->get();
        }

        if ($canViewReports) {
            $customerDue = Sale::outstanding()->where('product_type', $productType)->whereNotNull('customer_id')->sum('outstanding');

            $supplierDue = Purchase::outstanding()->where('product_type', $productType)->whereNotNull('supplier_id')->sum('outstanding');
        }

        if ($canViewReports && $productType === 'medicine') {
            $expiryCount = StockLot::whereNull('reversed_at')
                ->where('remaining_quantity', '>', 0)
                ->where('expiry_date', '<=', $expiryBefore)
                ->whereHas('variant.product', fn (Builder $products) => $products->where('type', 'medicine'))
                ->count();
        }

        $data = [
            'pageTitle'         => 'Pharmacy Dashboard',
            'branchName'        => (string) $request->attributes->get('branch_name'),
            'todayLabel'        => $today->format('d M Y'),
            'todayIso'          => $todayIso,
            'productType'       => $productType,
            'productTypeLabel'  => Product::types()[$productType],
            'canViewSales'      => $canViewSales,
            'canViewPurchases'  => $canViewPurchases,
            'canViewReports'    => $canViewReports,
            'canViewProducts'   => $canViewProducts,
            'canCreateSale'     => isset($permissions['pharmacy-sales.create']),
            'canCreatePurchase' => isset($permissions['pharmacy-purchases.create']),
            'canCreatePayment'  => isset($permissions['pharmacy-payments.create']),
            'productCount'      => $productCount,
            'salesTotal'        => $salesTotal,
            'salesCount'        => $salesCount,
            'purchaseTotal'     => $purchaseTotal,
            'purchaseCount'     => $purchaseCount,
            'customerDue'       => $customerDue,
            'supplierDue'       => $supplierDue,
            'recentSales'       => $recentSales,
            'recentPurchases'   => $recentPurchases,
            'customers'         => $customers,
            'expiryCount'       => $expiryCount,
            'expiryBefore'      => $expiryBefore,
        ];

        return view('pharmacy::dashboard.index', $data);
    }
}
