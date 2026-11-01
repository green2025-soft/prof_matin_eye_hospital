<div class="modal fade" id="inventory-pack-modal" tabindex="-1" aria-labelledby="inventory-pack-title" aria-hidden="true" data-inventory-pack-modal>
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="inventory-pack-title">Pack size</h2>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label" for="inventory-pack-size" data-inventory-pack-label>Pieces in this pack</label>
                <input class="form-control" id="inventory-pack-size" type="number" inputmode="numeric" min="1" max="1000000" step="1" required data-inventory-pack-size>
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" type="button" data-inventory-pack-confirm>Save</button>
            </div>
        </div>
    </div>
</div>
