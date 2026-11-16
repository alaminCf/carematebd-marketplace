<x-layouts.dashboard>
    <x-slot:title>Change Password — CareMate BD</x-slot:title>
    <x-slot:header>Account Security</x-slot:header>
    <x-slot:subheading>Update your login credentials and enhance your account protection.</x-slot:subheading>

    <div style="max-width: 600px; margin: 0 auto;">
        @if (session('success'))
            <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.35); color: #065f46; padding: 1rem 1.25rem; border-radius: var(--radius-lg); margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; font-weight: 600; font-size: 0.9rem;">
                <span>✓ {{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; font-size: 1.1rem; cursor: pointer; color: #065f46;">✕</button>
            </div>
        @endif

        <div class="glass-card" style="padding: 2.25rem; border-radius: var(--radius-xl);">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                <div style="width: 42px; height: 42px; border-radius: 12px; background: rgba(10, 57, 74, 0.1); color: var(--primary, #0a394a); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    🔑
                </div>
                <div>
                    <h2 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;">Change Password</h2>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0.2rem 0 0 0;">Signed in as <strong>{{ auth()->user()->email }}</strong></p>
                </div>
            </div>

            <form method="POST" action="{{ route('password.change.update') }}">
                @csrf

                <!-- Current Password -->
                <div style="margin-bottom: 1.25rem;">
                    <label class="form-label" for="current_password" style="font-weight: 700;">Current Password <span style="color: #ef4444;">*</span></label>
                    <div class="password-input-group">
                        <input type="password" id="current_password" name="current_password" required placeholder="Enter current password" class="glass-input">
                        <button type="button" onclick="togglePasswordVisibility('current_password', this)" aria-label="Show password" title="Show password" class="password-toggle-btn">
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
                    @error('current_password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- New Password -->
                <div style="margin-bottom: 1.25rem;">
                    <label class="form-label" for="password" style="font-weight: 700;">New Password <span style="color: #ef4444;">*</span></label>
                    <div class="password-input-group">
                        <input type="password" id="password" name="password" required placeholder="Minimum 6 characters" class="glass-input">
                        <button type="button" onclick="togglePasswordVisibility('password', this)" aria-label="Show password" title="Show password" class="password-toggle-btn">
                            <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8z"></path>
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

                <!-- Confirm New Password -->
                <div style="margin-bottom: 1.75rem;">
                    <label class="form-label" for="password_confirmation" style="font-weight: 700;">Confirm New Password <span style="color: #ef4444;">*</span></label>
                    <div class="password-input-group">
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Re-enter new password" class="glass-input">
                        <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" aria-label="Show password" title="Show password" class="password-toggle-btn">
                            <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="eye-closed" style="display: none;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                    <a href="{{ route(auth()->user()->role->dashboardRoute()) }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 700;">
                        <span>✓</span> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
