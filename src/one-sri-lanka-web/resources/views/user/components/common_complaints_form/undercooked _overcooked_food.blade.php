<div class="modal fade" id="foodDetailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-header">
                <button class="back-btn" data-bs-target="#foodComplaintModal" data-bs-toggle="modal">&lt;</button>
                <h5 class="ms-3 fw-bold">Food Complaint</h5>
            </div>
            <div class="modal-body">
                <h6>Spoiled / Rotten Food</h6>
                <div class="row g-2 mb-3">
                    <div class="col">
                        <select class="form-select">
                            <option>Select Province</option>
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select">
                            <option>Select District</option>
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select">
                            <option>Select City/Town</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <select class="form-select">
                        <option>Select Store/Market/Canteen/Grocery</option>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="datetime-local" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Photo / Video</label>
                    <div class="d-flex gap-2">
                        <input type="file" class="form-control" accept="image/*,video/*">
                        <input type="file" class="form-control" accept="image/*,video/*">
                        <input type="file" class="form-control" accept="image/*,video/*">
                    </div>
                </div>
                <div class="mb-3">
                    <textarea class="form-control" rows="3" placeholder="Description"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-submit">Submit</button>
            </div>
        </div>
    </div>
</div>