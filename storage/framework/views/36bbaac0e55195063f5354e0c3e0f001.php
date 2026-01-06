<div class="modal fade" id="createClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="<?php echo e(route('classes.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-0 pt-4 px-4 pb-0">
                <h5 class="modal-title fw-black d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-primary fs-3">add_circle</span>
                    Create New Class
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-4">Set up a new class structure. You can add specific groups and students later.</p>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-uppercase text-muted">Class Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><span class="material-symbols-outlined text-muted fs-5">school</span></span>
                            <input type="text" name="name" class="form-control bg-light border-0 py-2" placeholder="e.g. Intro to CS" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-uppercase text-muted">Semester</label>
                         <div class="input-group">
                            <span class="input-group-text bg-light border-0"><span class="material-symbols-outlined text-muted fs-5">calendar_month</span></span>
                            <input type="text" name="semester" class="form-control bg-light border-0 py-2" placeholder="e.g. Fall 2024" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small text-uppercase text-muted">Description (Optional)</label>
                        <textarea name="description" class="form-control bg-light border-0" rows="3" placeholder="Brief overview of the course objectives..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 p-3 rounded-bottom-4">
                <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary fw-bold px-4 shadow-sm">Create Class</button>
            </div>
        </div>
        </form>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\LaravelProjects\resources\views/classes/partials/create_modal.blade.php ENDPATH**/ ?>