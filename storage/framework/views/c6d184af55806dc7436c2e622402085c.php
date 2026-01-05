

<?php $__env->startSection('content'); ?>

<style>
    /* Utility */
    .btn-primary-custom {
        background-color: #0078bd;
        border: none;
        padding: 10px 20px;
        font-weight: 700;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 120, 189, 0.25);
        color: white;
        text-decoration: none;
    }
    .btn-primary-custom:hover {
        background-color: #00629b;
        color: white; 
    }

    .group-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .group-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border-color: #cbd5e1;
    }

    .avatar-group {
        display: flex;
        align-items: center;
    }

    .avatar-group img, .avatar-more {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 2px solid white;
        margin-left: -8px;
        object-fit: cover;
    }

    .avatar-group img:first-child { margin-left: 0; }

    .avatar-more {
        background-color: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
    }

    .create-dashed-card {
        border: 2px dashed #cbd5e1;
        background-color: #f8fafc;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 220px;
        transition: 0.2s;
        cursor: pointer;
        text-decoration: none;
        height: 100%;
    }

    .create-dashed-card:hover {
        border-color: #0078bd;
        background-color: rgba(0, 120, 189, 0.05);
    }
    
    .search-box {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 8px;
    }
    
    .bg-purple-soft { background-color: #f3e8ff; color: #7e22ce; }
    .bg-blue-soft { background-color: #e0f2fe; color: #0369a1; }
</style>

<div class="container-fluid max-width-6xl mx-auto">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
        <div>
            <h1 class="fw-black mb-1 fw-bold">My Groups</h1>
            <p class="text-muted">View all your project teams and study groups organized by class.</p>
        </div>
        <button type="button" class="btn btn-primary-custom d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createGroupModal">
            <span class="material-symbols-outlined fs-5">add</span>
            Create New Group
        </button>
    </div>

    <div class="search-box mb-5">
        <form action="<?php echo e(route('groups.index')); ?>" method="GET" class="row g-2 align-items-center w-100 m-0">
            <div class="col-md-8 position-relative">
                <span class="material-symbols-outlined position-absolute text-muted ms-2" style="top: 50%; transform: translateY(-50%);">search</span>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control border-0 ps-5 shadow-none" placeholder="Search by group name or member...">
            </div>
            <div class="col-md-4 d-flex align-items-center border-start ps-3">
                <span class="small text-muted text-nowrap me-2">Sort by:</span>
                <select name="sort" class="form-select form-select-sm border-0 bg-transparent shadow-none fw-medium" onchange="this.form.submit()">
                    <option value="newest" <?php echo e(request('sort') == 'newest' ? 'selected' : ''); ?>>Newest First</option>
                    <option value="alphabetical" <?php echo e(request('sort') == 'alphabetical' ? 'selected' : ''); ?>>Alphabetical</option>
                    <option value="most_members" <?php echo e(request('sort') == 'most_members' ? 'selected' : ''); ?>>Most Members</option>
                </select>
            </div>
        </form>
    </div>

    <div class="d-flex flex-column gap-5">
        <?php $__empty_1 = true; $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $className => $classGroups): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div>
            <div class="d-flex align-items-center gap-3 pb-2 border-bottom mb-4">
                <div class="p-2 bg-blue-soft rounded">
                    <span class="material-symbols-outlined fs-5">class</span>
                </div>
                <!-- Check if className is bool false? -->
                <h4 class="mb-0 fw-bold"><?php echo e(is_string($className) ? $className : 'Unknown Class'); ?></h4>
            </div>

            <div class="row g-4">
                <?php $__currentLoopData = $classGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $colors = ['#0d6efd', '#a855f7', '#198754', '#ffc107', '#dc3545'];
                    $uniqueIndex = $loop->parent->index * 5 + $loop->index;
                    $themeColor = $colors[$uniqueIndex % count($colors)];
                ?>
                <div class="col-lg-4 col-md-6">
                    <div class="group-card p-4 d-flex flex-column gap-3" style="border-top: 4px solid <?php echo e($themeColor); ?>;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="mb-0 fw-bold" style="color: <?php echo e($themeColor); ?>;"><?php echo e($group->name ?? 'No Name'); ?></h6>
                            </div>
                            <!-- Optional: Add random badge or status if needed match snippet rudimentary mapping -->
                        </div>
                        <p class="small text-muted mb-0">
                            <?php echo e(Str::limit($group->description ?? 'No description provided.', 80)); ?>

                        </p>
                        
                        

                        <div class="avatar-group mt-2">
                            <?php if(isset($group->members)): ?>
                                <?php $__currentLoopData = $group->members->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 <img src="<?php echo e($member->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($member->name).'&background=random'); ?>" alt="<?php echo e($member->name); ?>" title="<?php echo e($member->name); ?>">
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>

                        <a href="<?php echo e(route('groups.show', $group->id)); ?>" class="btn btn-outline-secondary w-100 mt-auto small fw-bold py-2 d-flex align-items-center justify-content-center gap-2" style="border-radius: 8px;">
                            View Details <span class="material-symbols-outlined fs-6">arrow_forward</span>
                        </a>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
         <div class="text-center py-5">
            <div class="mb-3 text-muted"><span class="material-symbols-outlined" style="font-size: 48px;">group_off</span></div>
            <h5>No groups found</h5>
            <p class="text-muted">You haven't joined any groups yet. Create one or join a class to get started.</p>
        </div>
        <?php endif; ?>
    </div>
    </div>

    <!-- Include Create Group Modal -->
    <?php echo $__env->make('groups.partials.create_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.student_dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\LaravelProjects\resources\views/groups/index.blade.php ENDPATH**/ ?>