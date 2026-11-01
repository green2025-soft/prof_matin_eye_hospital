<div class="modal fade" id="inventory-customer-modal" tabindex="-1" aria-labelledby="inventory-customer-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('inventory.customers.store') }}" method="POST" data-inventory-customer-create>
                @csrf
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="inventory-customer-title">New customer</h2>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger d-none" data-inventory-customer-errors role="alert">
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label" for="customer_name">Name</label>
                            <input class="form-control" id="customer_name" name="name" maxlength="255" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="customer_mobile">Mobile</label>
                            <input class="form-control" id="customer_mobile" name="mobile_no" maxlength="30" type="tel" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="customer_age">Age</label>
                            <input class="form-control" id="customer_age" name="age" type="number" min="0" max="150" step="1">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="customer_gender">Gender</label>
                            <select class="form-select" id="customer_gender" name="gender">
                                <option value="">Select</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="customer_address">Address</label>
                            <textarea class="form-control" id="customer_address" name="address" rows="2" maxlength="2000"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning" type="button" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button class="btn btn-primary" type="submit">
                        Save customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
