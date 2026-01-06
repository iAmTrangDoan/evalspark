<?php $__env->startSection('content'); ?>
<style>
    :root {
        --primary-color: #0078bd;
        --bg-light: #f5f7f8;
        --surface: #ffffff;
        --shadow-soft: 0 2px 15px rgba(0, 0, 0, 0.04);
        --shadow-hover: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .fw-black { font-weight: 900; }

    /* Class Card */
    .class-card {
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        background: white;
        transition: all 0.3s ease;
        height: 100%;
        box-shadow: var(--shadow-soft);
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }

    .class-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        border-color: #cbd5e1;
    }

    .lecturer-avatar {
        width: 42px; height: 42px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #f8fafc;
    }

    /* Toolbar & Inputs */
    .search-container { position: relative; }
    .search-container .material-symbols-outlined {
        position: absolute; left: 1rem; top: 50%;
        transform: translateY(-50%); color: #94a3b8;
    }
    .search-input {
        padding-left: 3rem; border-radius: 0.75rem;
        border: 1px solid #e2e8f0; background-color: white;
        transition: all 0.2s;
    }
    .search-input:focus { border-color: var(--primary-color); box-shadow: 0 0 0 4px rgba(0, 120, 189, 0.1); }

    /* Add Placeholder Card */
    .add-placeholder {
        border: 2px dashed #cbd5e1;
        background: rgba(241, 245, 249, 0.4);
        border-radius: 1.25rem;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        min-height: 100%;
        cursor: pointer;
        transition: all 0.2s;
        padding: 2rem;
    }
    .add-placeholder:hover {
        border-color: var(--primary-color);
        background: rgba(0, 120, 189, 0.03);
    }
    .add-icon-box {
        width: 64px; height: 64px;
        background: white; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 1.5rem; color: #94a3b8;
        box-shadow: var(--shadow-soft);
    }
    .add-placeholder:hover .add-icon-box { background: var(--primary-color); color: white; }

    .btn-primary-custom {
        background-color: var(--primary-color);
        border: none; color: white; font-weight: 700;
        padding: 0.75rem 1.5rem; border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 120, 189, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    .btn-primary-custom:hover {
        background-color: #00629b;
        color: white;
    }
</style>

<div class="container-xxl">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-4 mb-5">
        <div>
            <h1 class=" fw-black mb-1 fw-bold">My Classes</h1>
            <p class="text-muted mb-0">Manage your courses, track progress, and view assignments.</p>
        </div>
        <?php if(Auth::user()->role === 'lecturer'): ?>
            <a href="<?php echo e(route('classes.create')); ?>" class="btn btn-primary-custom transition-all">
                <span class="material-symbols-outlined">add</span>
                Create Class
            </a>
        <?php else: ?>
            <button class="btn btn-primary-custom transition-all" data-bs-toggle="modal" data-bs-target="#joinModal">
                <span class="material-symbols-outlined">add</span>
                Join New Class
            </button>
        <?php endif; ?>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-2 mb-5">
        <form action="<?php echo e(route('classes.index')); ?>" method="GET" class="row g-2 align-items-center w-100 m-0">
            <div class="col-md-8 position-relative">
                <span class="material-symbols-outlined position-absolute text-muted ms-2" style="top: 50%; transform: translateY(-50%);">search</span>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control border-0 ps-5 shadow-none" placeholder="Search by class name or lecturer...">
            </div>
            <div class="col-md-4 d-flex align-items-center border-start ps-3">
                <span class="small text-muted text-nowrap me-2">Sort by:</span>
                <select name="sort" class="form-select form-select-sm border-0 bg-transparent shadow-none fw-medium" onchange="this.form.submit()">
                    <option value="newest" <?php echo e(request('sort') == 'newest' ? 'selected' : ''); ?>>Newest First</option>
                    <option value="alphabetical" <?php echo e(request('sort') == 'alphabetical' ? 'selected' : ''); ?>>Alphabetical</option>
                    <option value="academic_year" <?php echo e(request('sort') == 'academic_year' ? 'selected' : ''); ?>>Academic Year</option>
                </select>
            </div>
        </form>
    </div>

    <div class="row g-4" id="classGrid">
        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="class-card p-4">

                <h4 class="fw-bold mb-4" style="min-height: 3rem"><?php echo e($class->name); ?></h4>
                
                <div class="d-flex align-items-center gap-3 mb-4 pb-4 border-bottom">
                    <img src="<?php echo e($class->lecturer->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($class->lecturer->name ?? 'Unknown').'&background=f1f5f9&color=475569'); ?>" class="lecturer-avatar" alt="<?php echo e($class->lecturer->name ?? 'Lecturer'); ?>">
                    <div>
                        <div class="text-uppercase text-muted fw-bold" style="font-size: 0.6rem; letter-spacing: 0.05em">Lecturer</div>
                        <div class="fw-bold small"><?php echo e($class->lecturer->name ?? 'Unknown'); ?></div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small fw-medium">Semester:</span>
                            <span class="fw-bold small text-nowrap"><?php echo e($class->semester); ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <!-- Space for Academic Year if available, else empty -->
                    </div>
                    <div class="col-6 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-muted" style="font-size: 18px">person</span>
                        <span class="small fw-bold"><?php echo e($class->students_count ?? $class->students->count()); ?> Students</span>
                    </div>
                    <div class="col-6 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-muted" style="font-size: 18px">group</span>
                        <span class="small fw-bold"><?php echo e($class->groups->count()); ?> Groups</span>
                    </div>
                </div>

                <a href="<?php echo e(route('classes.show', $class)); ?>" class="btn btn-outline-light text-dark border-secondary-subtle w-100 fw-bold py-2 mt-auto d-flex align-items-center justify-content-center gap-2">
                    View Details <span class="material-symbols-outlined" style="font-size: 18px">arrow_forward</span>
                </a>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="text-center mt-5">
        <p id="resultsCount" class="text-muted fw-medium small">Showing <?php echo e($classes->count()); ?> active classes</p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.student_dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\LaravelProjects\resources\views/classes/index.blade.php ENDPATH**/ ?>