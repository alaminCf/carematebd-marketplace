<x-layouts.guest>
    <x-slot:title>Choose New Password — CareMate BD</x-slot:title>
    <x-slot:description>Set a new password for your CareMate BD account.</x-slot:description>

    <div class="container container-narrow" style="padding: 4rem 1.25rem 6rem 1.25rem;">
        <div style="max-width: 480px; margin: 0 auto;">
            <!-- Brand logo -->
            <div style="text-align: center; margin-bottom: 2rem;">
                <a href="{{ route('home') }}" style="display: inline-block; margin-bottom: 1rem;">
                    <img src="{{ asset('images/logo.png') }}" alt="CareMate BD" style="height: 52px; width: auto; object-fit: contain;">
                </a>
                <h1 style="font-size: 1.85rem; font-weight: 800; color: #092632;">Set New Password</h1>
                <p style="color: var(--text-secondary); font-size: 0.95rem; margin-top: 0.35rem;">Enter your email and choose a strong new password</p>
            </div>

            <!-- Reset Password Glass Card -->
            <div class="glass-card" style="padding: 2.25rem; border-radius: var(--radius-xl); box-shadow: var(--glass-shadow-lg);">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Address -->
                    <div style="margin-bottom: 1.25rem;">
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required class="glass-input" placeholder="your.email@example.com">
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div style="margin-bottom: 1.25rem;">
                        <label class="form-label" for="password">New Password</label>
                        <div class="password-input-group">
                            <input type="password" id="password" name="password" required autofocus class="glass-input" placeholder="Minimum 6 characters">
                            <button type="button" onclick="togglePasswordVisibility('password', this)" aria-label="Show password" title="Show password" class="password-toggle-btn">
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

                    <!-- Confirm Password -->
                    <div style="margin-bottom: 1.75rem;">
                        <label class="form-label" for="password_confirmation">Confirm New Password</label>
                        <div class="password-input-group">
                            <input type="password" id="password_confirmation" name="password_confirmation" required class="glass-input" placeholder="Re-enter new password">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" aria-label="Show password" title="Show password" class="password-toggle-btn">
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
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-bottom: 1.25rem;">
                        Update & Reset Password
                    </button>
                </form>

                <div style="text-align: center; margin-top: 1rem; border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 1.25rem;">
                    <a href="{{ route('login') }}" style="color: var(--primary, #0a394a); font-size: 0.88rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem;">
                        ← Return to Sign In
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>
