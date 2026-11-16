<x-layouts.guest>
    <x-slot:title>Forgot Password — CareMate BD</x-slot:title>
    <x-slot:description>Recover and reset your CareMate BD account password.</x-slot:description>

    <div class="container container-narrow" style="padding: 4rem 1.25rem 6rem 1.25rem;">
        <div style="max-width: 480px; margin: 0 auto;">
            <!-- Brand logo -->
            <div style="text-align: center; margin-bottom: 2rem;">
                <a href="{{ route('home') }}" style="display: inline-block; margin-bottom: 1rem;">
                    <img src="{{ asset('images/logo.png') }}" alt="CareMate BD" style="height: 52px; width: auto; object-fit: contain;">
                </a>
                <h1 style="font-size: 1.85rem; font-weight: 800; color: #092632;">Reset Your Password</h1>
                <p style="color: var(--text-secondary); font-size: 0.95rem; margin-top: 0.35rem;">Enter your email to receive recovery instructions</p>
            </div>

            <!-- Forgot Password Glass Card -->
            <div class="glass-card" style="padding: 2.25rem; border-radius: var(--radius-xl); box-shadow: var(--glass-shadow-lg);">
                @if (session('status'))
                    <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.35); color: #065f46; padding: 1rem 1.25rem; border-radius: var(--radius-lg); margin-bottom: 1.5rem; font-size: 0.88rem; line-height: 1.5;">
                        <strong style="display: block; font-weight: 700; margin-bottom: 0.25rem;">✓ Link Sent</strong>
                        {{ session('status') }}

                        @if (session('direct_reset_url'))
                            <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px dashed rgba(16, 185, 129, 0.3);">
                                <a href="{{ session('direct_reset_url') }}" class="btn btn-sm btn-primary" style="display: inline-block; text-decoration: none; font-size: 0.8rem; padding: 0.4rem 0.85rem;">
                                    Click Here to Reset Password Now →
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div style="margin-bottom: 1.5rem;">
                        <label class="form-label" for="resetEmail">Registered Email Address</label>
                        <input type="email" id="resetEmail" name="email" value="{{ old('email') }}" required autofocus placeholder="e.g. your.email@example.com" class="glass-input">
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-bottom: 1.25rem;">
                        Send Password Reset Link
                    </button>
                </form>

                <div style="text-align: center; margin-top: 1rem; border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 1.25rem;">
                    <a href="{{ route('login') }}" style="color: var(--primary, #0a394a); font-size: 0.88rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem;">
                        ← Back to Sign In
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>
