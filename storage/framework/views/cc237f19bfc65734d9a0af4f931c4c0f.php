

<?php $__env->startSection('content'); ?>
<style>
    /* Group Cards */
    .group-card {
        background: var(--surface, #ffffff);
        border: 1px solid var(--border-color, #dee2e6);
        border-radius: 12px;
        box-shadow: 0 1px 1px rgba(9, 30, 66, 0.25), 0 0 1px rgba(9, 30, 66, 0.31);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .group-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 8px -2px rgba(9, 30, 66, 0.25), 0 0 1px rgba(9, 30, 66, 0.31);
    }

    .group-card-dashed {
        border: 2px dashed #cbd5e1;
        background: transparent;
        cursor: pointer;
        justify-content: center;
        align-items: center;
        min-height: 280px;
        display: flex;
        flex-direction: column;
    }

    .group-card-dashed:hover {
        border-color: var(--primary-color);
        background: rgba(0, 120, 189, 0.05); /* Using primary color var */
    }

    /* Avatar styling */
    .avatar-circle {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 700;
        color: white;
    }

    .fw-black { font-weight: 900; }
    .text-xsmall { font-size: 11px; }

    .btn-primary-custom {
        background-color: var(--primary-color);
        border: none;
        color: white;
        font-weight: 700;
        padding: 8px 20px;
        border-radius: 8px;
    }
    .btn-primary-custom:hover {
        background-color: #00629b;
        color: white;
    }
</style>

<div class="container-xxl p-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-5">
        <div>
            <h1 class="fw-black mb-1 fw-bold">Manage Groups</h1>
            <p class="text-muted mb-0">Create, organize, and monitor student project groups across your classes.</p>
        </div>
    </div>

    <div class="d-flex flex-column gap-5">
        <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            
            <?php 
                $markerColor = ['#0079BF', '#a855f7', '#198754', '#ffc107', '#dc3545'][rand(0,4)];
                $classGroups = $class->groups;
            ?>
            <div>
                <div class="d-flex align-items-center justify-content-between pb-2 border-bottom border-2 mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 4px; height: 24px; background: <?php echo e($markerColor); ?>; border-radius: 2px;"></div>
                        <h5 class="mb-0 fw-bold text-dark"><?php echo e($class->name); ?></h5>
                        <span class="badge bg-light text-muted border px-2 py-1 small"><?php echo e($classGroups->count()); ?> Groups</span>
                    </div>
                    <?php if($classGroups->count() > 3): ?>
                        <a href="<?php echo e(route('classes.show', $class->id)); ?>" class="btn btn-link text-decoration-none small fw-bold p-0 d-flex align-items-center gap-1">
                            View All <span class="material-symbols-outlined fs-6">arrow_forward</span>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="row g-4">
                    <?php $__empty_2 = true; $__currentLoopData = $classGroups->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="group-card">
                            <div class="p-4 border-bottom d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark"><?php echo e($group->name); ?></h6>
                                    
                                    <p class="text-xsmall text-muted mb-0"><?php echo e($group->description ?? 'No description provided'); ?></p>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm text-muted p-0" data-bs-toggle="dropdown"><span class="material-symbols-outlined">more_vert</span></button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                        <li><a class="dropdown-item small" href="<?php echo e(route('groups.show', $group->id)); ?>">View Board</a></li>
                                        <li><a class="dropdown-item small text-danger" href="#">Delete Group</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="p-4 flex-grow-1">
                                <p class="text-uppercase fw-bold text-muted mb-3" style="font-size: 10px; letter-spacing: 1px;">Members (<?php echo e($group->members->count()); ?>)</p>
                                <div class="d-flex flex-column gap-2">
                                    <?php $__currentLoopData = $group->members->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="d-flex align-items-center gap-2 small">
                                        <div class="avatar-circle bg-primary bg-opacity-10 text-primary overflow-hidden" 
                                             style="background-color: var(--primary-color); <?php echo e($member->avatar_url ? 'background-image: url(' . $member->avatar_url . '); background-size: cover; background-position: center;' : ''); ?>">
                                             <?php echo e($member->avatar_url ? '' : substr($member->name, 0, 1)); ?>

                                        </div>
                                        <span><?php echo e($member->name); ?></span>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($group->members->count() > 3): ?>
                                    <div class="d-flex align-items-center gap-2 small text-muted">
                                        <span class="ps-4">+<?php echo e($group->members->count() - 3); ?> more students</span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="p-3 bg-light border-top rounded-bottom d-flex gap-2">
                                <a href="<?php echo e(route('groups.show', $group->id)); ?>" class="btn btn-primary btn-sm w-100 fw-bold text-white text-decoration-none d-flex align-items-center justify-content-center" style="background-color: var(--primary-color);">View Details</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                    <div class="col-12">
                        <div class="text-center py-4 border rounded-3 bg-light">
                             <p class="text-muted mb-0 small">No groups created in this class yet.</p>
                             <a href="<?php echo e(route('classes.show', $class->id)); ?>" class="small fw-bold text-primary text-decoration-none mt-2 d-inline-block">Go to Class Details</a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-5">
                <h5 class="fw-bold">No Classes Found</h5>
                <p class="text-muted">You are not teaching any classes yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php echo $__env->make('groups.partials.create_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.lecturer_dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\LaravelProjects\resources\views/groups/lecturer_index.blade.php ENDPATH**/ ?>