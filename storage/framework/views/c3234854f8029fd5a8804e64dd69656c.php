

<?php $__env->startSection('content'); ?>
<div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-4">Edit Class</h1>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="<?php echo e(route('classes.update', $class->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row g-3">
                             <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Class Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><span class="material-symbols-outlined text-muted fs-5">school</span></span>
                                    <input type="text" name="name" class="form-control bg-light border-0 py-2" value="<?php echo e(old('name', $class->name)); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Class Code</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><span class="material-symbols-outlined text-muted fs-5">badge</span></span>
                                    <input type="text" name="join_code" class="form-control bg-light border-0 py-2" value="<?php echo e(old('join_code', $class->join_code)); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-uppercase text-muted">Semester</label>
                                 <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><span class="material-symbols-outlined text-muted fs-5">calendar_month</span></span>
                                    <input type="text" name="semester" class="form-control bg-light border-0 py-2" value="<?php echo e(old('semester', $class->semester)); ?>" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-uppercase text-muted">Description</label>
                                <textarea name="description" class="form-control bg-light border-0" rows="3"><?php echo e(old('description', $class->description)); ?></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                             <a href="<?php echo e(route('classes.index')); ?>" class="btn btn-light fw-bold">Cancel</a>
                             <button type="submit" class="btn btn-primary fw-bold px-4">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.lecturer_dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\LaravelProjects\resources\views/classes/edit.blade.php ENDPATH**/ ?>