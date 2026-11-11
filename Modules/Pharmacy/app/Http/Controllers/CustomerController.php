<?php

namespace Modules\Pharmacy\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Models\Setting;
use Modules\Hospital\Contracts\PharmacyCustomers;
use Modules\Pharmacy\Http\Requests\CustomerRequest;

class CustomerController extends BaseController
{
    public function index(CustomerRequest $request, PharmacyCustomers $customers): View
    {
        $actor      = $request->user('web');
        $filters    = $request->validated();
        $records    = $customers->paginate($actor, $filters);

        $data = [
            'pageTitle' => 'Customers / patients',
            'filters'   => $filters,
            'records'   => $records,
        ];

        return view('pharmacy::customers.index', $data);
    }

    public function choices(CustomerRequest $request, PharmacyCustomers $customers): JsonResponse
    {
        $filters        = $request->validated();
        $settings       = Setting::values('pharmacy');
        $customersOnly  = ! empty($filters['new_sale']) && $settings['customer_scope'] === 'customer';

        return response()->json($customers->search($request->user('web'), $filters['search'] ?? '', $customersOnly, (int) ($filters['page'] ?? 1)));
    }

    public function store(CustomerRequest $request, PharmacyCustomers $customers): JsonResponse
    {
        $customer = $customers->create($request->user('web'), $request->validated(), (int) $request->attributes->get('branch_id'));

        return response()->json(['customer' => $customer], 201);
    }
}
