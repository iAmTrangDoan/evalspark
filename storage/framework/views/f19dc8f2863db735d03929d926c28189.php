<?php $__env->startSection('content'); ?>
<div class="container-xxl py-5">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-5 gap-3">
        <div>
            <h1 class="fw-black mb-1 fw-bold">Profile Settings</h1>
            <p class="text-muted mb-0">Manage your personal information, security, and preferences.</p>
        </div>
    </div>

    <!-- Main Profile Form (Header + Personal Info) -->
    <form method="post" action="<?php echo e(route('profile.update')); ?>" enctype="multipart/form-data" class="row g-4">
        <?php echo csrf_field(); ?>
        <?php echo method_field('patch'); ?>
        
        <div class="col-lg-9 mx-auto">
            <!-- Header Card -->
            <div class="card-custom mb-4 bg-white" style="border-radius: 1.5rem; border: 1px solid var(--border-color); padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <div class="row align-items-center g-4">
                    <div class="col-md-auto text-center">
                        <div class="position-relative d-inline-block">
                             <!-- Avatar Display -->
                            <div id="avatarPreview" class="profile-avatar bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-1 overflow-hidden" 
                                style="width: 128px; height: 128px; border-radius: 50%; border: 4px solid #fff; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); background-image: url('<?php echo e($user->avatar ? asset('storage/' . $user->avatar) : ''); ?>'); background-size: cover; background-position: center;">
                                <?php if(!$user->avatar): ?>
                                    <?php echo e(substr($user->name, 0, 1)); ?>

                                <?php endif; ?>
                            </div>
                            
                            <!-- File Input Trigger -->
                            <label for="avatarInput" class="btn btn-primary rounded-circle position-absolute bottom-0 end-0 p-2 shadow-sm cursor-pointer" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                <span class="material-symbols-outlined fs-6">photo_camera</span>
                            </label>
                            <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/*" onchange="previewAvatar(this)">
                            <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="position-absolute top-100 start-50 translate-middle-x text-danger small fw-bold bg-white px-2 py-1 rounded shadow-sm border text-nowrap mt-2" style="z-index: 10;">
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="d-flex align-items-center gap-2 mb-1 justify-content-center justify-content-md-start">
                            <h2 class="h4 fw-bold mb-0"><?php echo e($user->name); ?></h2>
                            <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-3"><?php echo e(ucfirst($user->role)); ?></span>
                        </div>
                        <p class="text-muted text-center text-md-start"><?php echo e($user->email); ?></p>
                    </div>
                </div>
            </div>

            <div class="card-custom p-0 overflow-hidden bg-white" style="border-radius: 1.5rem; border: 1px solid var(--border-color); box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                
                <!-- Personal Information -->
                <div class="p-4 p-md-5 border-bottom" id="personal">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-primary fs-3">badge</span>
                        <h3 class="h5 fw-bold mb-0">Personal Information</h3>
                    </div>
                    
                    <!-- Hidden Email to pass validation -->
                    <input type="hidden" name="email" value="<?php echo e(old('email', $user->email)); ?>">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-secondary">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-secondary">
                                <?php if($user->role == 'student'): ?> Student ID 
                                <?php else: ?> Teacher ID 
                                <?php endif; ?>
                            </label>
                            <input type="text" name="code" class="form-control" value="<?php echo e(old('code', $user->code)); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-secondary">Role</label>
                            <input type="text" class="form-control bg-light" disabled value="<?php echo e(ucfirst($user->role)); ?>">
                        </div>

                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary fw-bold px-4">Save Information</button>
                            <?php if(session('status') === 'profile-updated'): ?>
                                <span class="text-success small fw-bold ms-2 fade-out">Saved.</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="col-lg-9 mx-auto mt-4">
        <div class="card-custom p-0 overflow-hidden bg-white" style="border-radius: 1.5rem; border: 1px solid var(--border-color); box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <!-- Account Security -->
            <div class="p-4 p-md-5 border-bottom" id="security">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="material-symbols-outlined text-primary fs-3">shield_lock</span>
                    <h3 class="h5 fw-bold mb-0">Account Security</h3>
                </div>

                <!-- Email Update Form -->
                <form method="post" action="<?php echo e(route('profile.update')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('patch'); ?>
                    <input type="hidden" name="name" value="<?php echo e(old('name', $user->name)); ?>">

                    <div class="row g-4 align-items-end mb-4">
                        <div class="col-md-9">
                            <label class="form-label fw-bold small text-secondary">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><span class="material-symbols-outlined text-muted">mail</span></span>
                                <input type="email" name="email" class="form-control border-start-0" value="<?php echo e(old('email', $user->email)); ?>" required>
                            </div>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-outline-secondary w-100 fw-semibold rounded-3" style="padding-top: 0.75rem; padding-bottom: 0.75rem;">Change Email</button>
                        </div>
                    </div>
                </form>

                <hr class="my-4">

                <!-- Password Update Form -->
                 <form method="post" action="<?php echo e(route('password.update')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('put'); ?>
                    
                    <div class="mb-4">
                        <h4 class="h5 fw-bold mb-3">Change Password</h4>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label small text-secondary fw-bold">Current Password</label>
                                <input type="password" name="current_password" class="form-control">
                                <?php $__errorArgs = ['current_password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small text-secondary fw-bold">New Password</label>
                                <input type="password" name="password" class="form-control">
                                <?php $__errorArgs = ['password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small text-secondary fw-bold">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                                <?php $__errorArgs = ['password_confirmation', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end align-items-center">
                        <?php if(session('status') === 'password-updated'): ?>
                            <span class="text-success small fw-bold me-2 fade-out">Password Updated.</span>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-primary-subtle text-primary border-0 fw-bold px-4 rounded-3">Update Password</button>
                    </div>
                 </form>
            </div>

            <!-- Delete Account -->
            <div class="p-4 p-md-5 bg-light bg-opacity-50">
                 <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="material-symbols-outlined text-danger fs-3">warning</span>
                    <h3 class="h5 fw-bold mb-0 text-danger">Delete Account</h3>
                </div>
                <p class="text-muted small">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                
                <button class="btn btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Delete Account</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="<?php echo e(route('profile.destroy')); ?>" class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
            <?php echo csrf_field(); ?>
            <?php echo method_field('delete'); ?>
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger">Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted mb-3">Are you sure you want to delete your account? This action cannot be undone.</p>
                <div class="mb-3">
                    <label for="password" class="form-label fw-bold small">Enter Password to Confirm</label>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <?php $__errorArgs = ['password', 'userDeletion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger fw-bold">Delete Account</button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                var preview = document.getElementById('avatarPreview');
                preview.style.backgroundImage = 'url(' + e.target.result + ')';
                preview.innerText = ''; // Clear initials
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<style>
    /* Styling overrides or additions to match snippet */
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(0, 120, 189, 0.1);
    }
    .fade-out {
        animation: fadeOut 3s forwards;
    }
    @keyframes fadeOut {
        0% { opacity: 1; }
        70% { opacity: 1; }
        100% { opacity: 0; }
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(Auth::user()->role === 'lecturer' ? 'layouts.lecturer_dashboard' : 'layouts.student_dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\LaravelProjects\resources\views/profile/edit.blade.php ENDPATH**/ ?>