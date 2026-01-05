<div class="modal fade" id="createGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="<?php echo e(route('groups.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pt-4 px-4 pb-0">
                    <h5 class="modal-title fw-black d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-primary fs-3">group_add</span>
                        Create New Group
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-muted">Group Name</label>
                        <input type="text" name="name" class="form-control bg-light border-0 py-2" placeholder="e.g. Final Project Team" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-muted">Class</label>
                        <?php if(isset($class)): ?>
                            <input type="hidden" name="class_id" value="<?php echo e($class->id); ?>">
                            <input type="text" class="form-control bg-light border-0 py-2" value="<?php echo e($class->name); ?>" readonly>
                            <div class="form-text mt-2"><span class="material-symbols-outlined fs-6 align-middle me-1">lock</span>Linked to current class</div>
                        <?php else: ?>
                            <select name="class_id" class="form-select bg-light border-0 py-2" required>
                                <option value="" disabled selected>Select a class...</option>
                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-muted">Description (Optional)</label>
                        <textarea name="description" class="form-control bg-light border-0" rows="3" placeholder="Briefly describe your group's goal..."></textarea>
                    </div>

                    <div class="border rounded-3 p-3">
                        <div class="form-check form-switch d-flex align-items-center gap-3 ps-0 mb-0">
                            <input class="form-check-input ms-0" type="checkbox" name="is_public" value="1" id="publicSwitch" checked style="width: 3em; height: 1.5em; flex-shrink: 0;">
                            <label class="form-check-label flex-grow-1 cursor-pointer" for="publicSwitch">
                                <span class="d-block fw-bold text-dark">Public Group</span>
                                <span class="d-block text-muted small mt-1">Allow other classmates to view and request to join this group.</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 p-3 rounded-bottom-4">
                    <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4 shadow-sm">Create Group</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\LaravelProjects\resources\views/groups/partials/create_modal.blade.php ENDPATH**/ ?>