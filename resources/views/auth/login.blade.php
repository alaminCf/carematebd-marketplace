<x-layouts.guest>
    <x-slot:title>Login — CareMate BD</x-slot:title>
    <x-slot:description>Log into CareMate BD Family, Caregiver, or Admin Portal.</x-slot:description>

    <div class="container container-narrow" style="padding: 4rem 1.25rem 6rem 1.25rem;">
        <div style="max-width: 480px; margin: 0 auto;">
            <!-- Brand logo -->
            <div style="text-align: center; margin-bottom: 2rem;">
                <a href="{{ route('home') }}" style="display: inline-block; margin-bottom: 1rem;">
                    <img src="{{ asset('images/logo.png') }}" alt="CareMate BD" style="height: 52px; width: auto; object-fit: contain;">
                </a>
                <h1 style="font-size: 2rem; font-weight: 800; color: #092632;">Sign into CareMate BD</h1>
                <p style="color: var(--text-secondary); font-size: 0.95rem; margin-top: 0.35rem;">Enter your email & password to access your portal</p>
            </div>

            <!-- Login Glass Card -->
            <div class="glass-card" style="padding: 2.25rem; border-radius: var(--radius-xl); box-shadow: var(--glass-shadow-lg);">
                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf

                    <div style="margin-bottom: 1.25rem;">
                        <label class="form-label" for="loginEmail">Email Address</label>
                        <input type="email" id="loginEmail" name="email" value="{{ old('email') }}" required autofocus placeholder="your.email@example.com" class="glass-input">
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.35rem;">
                            <label class="form-label" for="loginPassword" style="margin-bottom: 0;">Password</label>
                        </div>
                        <div class="password-input-group" style="position: relative; display: flex; align-items: center; width: 100%;">
                            <input type="password" id="loginPassword" name="password" required placeholder="••••••••" class="glass-input" style="padding-right: 2.75rem;">
                            <button type="button" onclick="togglePasswordVisibility('loginPassword', this)" aria-label="Show password" title="Show password" class="password-toggle-btn" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: transparent; border: none; padding: 0.25rem; margin: 0; cursor: pointer; color: #64748b; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; box-shadow: none; line-height: 1; z-index: 2;">
                                <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg class="eye-closed" style="display: none;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.75rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer; color: var(--text-secondary);">
                            <input type="checkbox" name="remember" style="accent-color: var(--brand-primary); width: 16px; height: 16px;">
                            <span>Keep me signed in</span>
                        </label>
                        <a href="{{ route('password.request') }}" style="color: var(--primary, #0a394a); font-size: 0.85rem; font-weight: 700; text-decoration: none;">
                            Forgot Password?
                        </a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-bottom: 1.25rem;">
                        Sign In to Dashboard
                    </button>
                </form>

            </div>

            <!-- Footer Links -->
            <div style="text-align: center; margin-top: 2rem; font-size: 0.9rem; color: var(--text-secondary);">
                Don't have an account? <br>
                <div style="display: flex; justify-content: center; gap: 1.5rem; margin-top: 0.5rem;">
                    <a href="{{ route('register.client') }}" style="font-weight: 700;">Register as Family Client</a>
                    <span>•</span>
                    <a href="{{ route('caregiver.register') }}" style="color: #059669; font-weight: 700;">Join as Caregiver</a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            const eyeOpen = btn.querySelector('.eye-open');
            const eyeClosed = btn.querySelector('.eye-closed');
            if (eyeOpen && eyeClosed) {
                eyeOpen.style.display = isPassword ? 'none' : 'block';
                eyeClosed.style.display = isPassword ? 'block' : 'none';
            }
            btn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
            btn.setAttribute('title', isPassword ? 'Hide password' : 'Show password');
        }
    </script>
    @endpush
</x-layouts.guest>
