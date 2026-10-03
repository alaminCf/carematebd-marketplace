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
                        <input type="password" id="loginPassword" name="password" required placeholder="••••••••" class="glass-input">
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.75rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer; color: var(--text-secondary);">
                            <input type="checkbox" name="remember" style="accent-color: var(--brand-primary); width: 16px; height: 16px;">
                            <span>Keep me signed in</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-bottom: 1.25rem;">
                        Sign In to Dashboard
                    </button>
                </form>

                <!-- Quick Test Credentials Demo Bar -->
                <div style="border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 1.25rem; margin-top: 1rem;">
                    <div style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); letter-spacing: 0.05em; text-align: center; margin-bottom: 0.75rem;">
                        Quick Test Profiles (Click to fill)
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem;">
                        <button type="button" onclick="fillCreds('admin@caremate.com', 'password')" class="btn btn-secondary btn-sm" style="font-size: 0.75rem; padding: 0.35rem 0.5rem;">
                            👑 Admin
                        </button>
                        <button type="button" onclick="fillCreds('client@caremate.com', 'password')" class="btn btn-secondary btn-sm" style="font-size: 0.75rem; padding: 0.35rem 0.5rem;">
                            👨‍👩‍👧 Client
                        </button>
                        <button type="button" onclick="fillCreds('nusrat@caremate.com', 'password')" class="btn btn-secondary btn-sm" style="font-size: 0.75rem; padding: 0.35rem 0.5rem;">
                            🩺 Caregiver
                        </button>
                    </div>
                </div>
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
        function fillCreds(email, pass) {
            document.getElementById('loginEmail').value = email;
            document.getElementById('loginPassword').value = pass;
        }
    </script>
    @endpush
</x-layouts.guest>
