<x-guest-layout>

    <div class="text-center mb-4">
        <p class="text-secondary small mb-0">Collaborative learning, beautifully organized</p>
    </div>
    
    <x-auth-session-status class="mb-4" :status="session('status')"  />

    <form method="POST" action="{{ route('login') }}" class="mt-4">
        @csrf

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

        <div class="mb-2">
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

        <div class="d-flex justify-content-end align-items-center mb-4">

     
            
            <div  class="small text-secondary ">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="link-custom">{{ __('Forgot Password?') }}</a>
                @endif
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-primary-custom">
                {{ __('Login') }}
            </button>
        </div>
    </form>

    <div class="mt-4 text-center">
        <div  class="small text-secondary">
            {{ __('New to EvalSpark?') }} 
            <a href="{{ route('register') }}" class="link-custom">{{ __('Create an account') }}</a>
        </div>
    </div>
    
    <script>
       
    </script>
</x-guest-layout>