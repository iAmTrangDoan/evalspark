

<?php $__env->startSection('content'); ?>
<div class="container-xxl">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 gap-3">
        <div>
            <h1 class="fw-black h2 mb-1">Welcome Back, <?php echo e(Auth::user()->name); ?>!</h1>
            <p class="text-muted">Here's an overview of your classes and groups today.</p>
        </div>
        <div class="bg-white px-3 py-2 rounded-3 border small fw-bold text-secondary shadow-sm d-flex align-items-center gap-2">
            <span class="material-symbols-outlined text-primary fs-5">calendar_today</span>
            <?php echo e(now()->format('M d, Y')); ?>

        </div>
    </div>

    <!-- Recently Viewed (Preserving col-lg-8 from snippet, but wrapping in row for grid correctness) -->
    <div class="row mb-5">
        <div class="col-lg-12">
            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined text-muted">history</span>
                Recently Viewed
            </h5>
            <div class="d-flex flex-row gap-3 overflow-auto pb-2">
                <?php $__empty_1 = true; $__currentLoopData = $groups->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('groups.show', $group->id)); ?>" class="card-custom card-hover p-4 text-decoration-none d-flex align-items-center gap-3" style="min-width: 300px;">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary">
                        <span class="material-symbols-outlined">groups</span>
                    </div>
                    <div>
                        <p class="mb-0 fw-bold text-dark small"><?php echo e($group->name); ?></p>
                        <p class="mb-0 text-muted extra-small" style="font-size: 11px;"><?php echo e($group->classRoom->name ?? 'Class Group'); ?></p>
                    </div>
                    <span class="material-symbols-outlined ms-auto text-muted fs-6">arrow_forward</span>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-muted small fst-italic">No recently viewed groups.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Active Classes Section -->
    <div class="pt-4 border-top">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">My Active Classes</h4>
            <a href="<?php echo e(route('classes.index')); ?>" class="text-decoration-none fw-bold small text-primary d-flex align-items-center gap-1">
                View All <span class="material-symbols-outlined fs-6">chevron_right</span>
            </a>
        </div>

        <div class="row g-4">
            <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $colors = ['#0d6efd', '#a855f7', '#198754', '#ffc107', '#dc3545'];
                $themeColor = $colors[$index % count($colors)];
                $bgClass = $index % 5 == 1 ? 'purple' : ($index % 5 == 2 ? 'success' : ($index % 5 == 3 ? 'warning' : 'primary')); // rudimentary mapping for badge class if needed, or just use inline style as per snippet
                
                // Demo progress
                $progress = rand(30, 90);
                $progressColor = $progress > 75 ? 'success' : ($progress > 40 ? 'warning' : 'danger');
            ?>
            <div class="col-md-6 col-xl-4">
                <a href="<?php echo e(route('classes.show', $class->id)); ?>" class="text-decoration-none text-dark">
                <div class="card-custom card-hover h-100 flex-column d-flex" style="border-top: 5px solid <?php echo e($themeColor); ?>">
                    <div class="p-4 flex-grow-1">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge fw-bold" 
                                  style="color: <?php echo e($themeColor); ?>; background: <?php echo e($themeColor); ?>1a;">
                                <?php echo e($class->class_code); ?>

                            </span>
                            <button class="btn btn-sm p-0 text-muted"><span class="material-symbols-outlined">more_horiz</span></button>
                        </div>
                        <h5 class="fw-bold mb-1"><?php echo e($class->name); ?></h5>
                        <p class="text-muted small mb-4"><?php echo e($class->semester ?? 'Current Semester'); ?></p>
                        
                        <div class="row g-2 text-center">
                            <div class="col-6">
                                <div class="bg-light p-2 rounded-3 border">
                                    <div class="h5 fw-bold mb-0 text-dark"><?php echo e($class->students_count); ?></div>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 9px;">Students</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light p-2 rounded-3 border">
                                    <div class="h5 fw-bold mb-0 text-dark"><?php echo e($class->groups->count()); ?></div>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 9px;">Groups</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 pt-0">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted fw-bold">Grading Progress</span>
                            <span class="text-<?php echo e($progressColor); ?> fw-bold"><?php echo e($progress); ?>%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-<?php echo e($progressColor); ?>" style="width: <?php echo e($progress); ?>%"></div>
                        </div>
                    </div>
                </div>
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
             <div class="col-12">
                <div class="text-center py-5 bg-white border border-dashed rounded-3">
                    <div class="bg-light d-inline-flex p-3 rounded-circle mb-3 text-secondary">
                        <span class="material-icons-outlined fs-2">class</span>
                    </div>
                    <h5 class="fw-bold">No Classes Yet</h5>
                    <p class="text-muted mb-4">You are not teaching any classes yet.</p>
                    <button type="button" class="btn btn-primary px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createClassModal">
                        Create Class
                    </button>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .card-custom {
        background: var(--card-bg, #ffffff);
        border: 1px solid var(--border-color, #dfe1e6);
        border-radius: 12px;
        box-shadow: 0 1px 1px rgba(9, 30, 66, 0.25), 0 0 1px rgba(9, 30, 66, 0.31);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px -2px rgba(9, 30, 66, 0.25), 0 0 1px rgba(9, 30, 66, 0.31);
    }

    .icon-box {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .fw-black {
        font-weight: 900;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.lecturer_dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\LaravelProjects\resources\views/lecturer/dashboard.blade.php ENDPATH**/ ?>