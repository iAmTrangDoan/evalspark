
<x-guest-layout>
    <div class="text-center mb-4">
    <p class="text-secondary small mb-0">Sign up for your account</p>
    </div>
    <form method="POST" action="{{ route('register') }}" class="mt-4">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Full Name') }}</label>
            <div class="input-wrapper">
                <div class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <input type="text" class="form-control form-control-custom" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required autofocus>
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger small" />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <div class="input-wrapper">
                <div class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                </div>
                <input type="email" class="form-control form-control-custom" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger small" />
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <div class="input-wrapper">
                <div class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <input type="password" class="form-control form-control-custom" id="password" name="password" placeholder="Create a password" minlength="8" required autocomplete="new-password">
            </div>
            <div class="helper-text">Use at least 8 characters</div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger small" />
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <div class="input-wrapper">
                <div class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <input type="password" class="form-control form-control-custom" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required autocomplete="new-password">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger small" />
        </div>

        <div class="mb-4">
            <label class="form-label d-block text-dark fw-bold">
                {{ __('Role') }} <span class="text-danger">*</span>
            </label>
            <input type="hidden" id="roleInput" name="role" value="student">
            <div class="row g-3">
                <div class="col-6">
                    <div class="role-card selected" onclick="selectRole('student', this)" id="roleStudent">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/></svg>
                            <span class="title">Student</span>
                        </div>
                        <p class="description m-0">Join projects and collaborate with your team</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="role-card" onclick="selectRole('lecturer', this)" id="roleLecturer">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                            <span class="title">Lecturer</span>
                        </div>
                        <p class="description m-0">Create and manage student projects</p>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-primary-custom">{{ __('Register') }}</button>
    </form>

    <div class="mt-4 text-center">
        <p class="small text-secondary mb-2">
            By signing up, you agree to our 
            <a href="#" class="link-custom">Terms of Service</a> and 
            <a href="#" class="link-custom">Privacy Policy</a>
        </p>
        <div class="small text-secondary">
            Already have an account? 
            <a href="{{ route('login') }}" class="link-custom fw-semibold">{{ __('Log in') }}</a>
        </div>
    </div>
    
</x-guest-layout>

<script>
    function selectRole(role, element) {
        // Cập nhật hidden input
        document.getElementById('roleInput').value = role.toLowerCase(); // Đảm bảo giá trị là lowercase: student hoặc lecturer

        // Xóa class 'selected' khỏi tất cả role cards
        document.querySelectorAll('.role-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Thêm class 'selected' vào thẻ được click
        element.classList.add('selected');
    }
</script>
