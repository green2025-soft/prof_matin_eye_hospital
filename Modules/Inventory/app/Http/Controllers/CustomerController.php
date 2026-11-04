<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Models\Setting;
use Modules\Hospital\Contracts\InventoryCustomers;
use Modules\Inventory\Http\Requests\CustomerRequest;
use Modules\Inventory\Services\InventoryAccess;

class CustomerController extends BaseController
{
    public function index(CustomerRequest $request, InventoryCustomers $customers, InventoryAccess $access): View
    {
        $access->authorize($request->user('web'), 'inventory-customers.view');
        $filters = $request->validated();
        $records = $customers->paginate($request->user('web'), $filters);

        return view('inventory::customers.index', [
            'pageTitle' => 'Customers / patients',
            'filters' => $filters,
            'records' => $records,
        ]);
    }

    public function choices(CustomerRequest $request, InventoryCustomers $customers): JsonResponse
    {
        $filters = $request->validated();
        $settings = Setting::values('inventory');
        $customersOnly = ! empty($filters['new_sale']) && $settings['customer_scope'] === 'customer';

        return response()
            ->json($customers->search($request->user('web'), $filters['search'] ?? '', $customersOnly, (int) ($filters['page'] ?? 1)));
    }

    public function store(CustomerRequest $request, InventoryCustomers $customers): JsonResponse
    {
        $customer = $customers->create($request->user('web'), $request->validated(), (int) $request->attributes->get('branch_id'));

        return response()
            ->json([
                'customer' => $customer,
            ], 201);
    }
}
