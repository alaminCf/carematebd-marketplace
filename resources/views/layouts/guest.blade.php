<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'CareMate BD — Care that feels like family, found in minutes.' }}</title>
    <meta name="description" content="{{ $description ?? 'CareMate BD is a premier admin-mediated caregiver marketplace in Bangladesh connecting families with verified caregivers for Elderly, Child, Nursing, and Transportation Care.' }}">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $title ?? 'CareMate BD — Trusted Caregiver Marketplace' }}">
    <meta property="og:description" content="Verified, background-checked caregivers across Bangladesh. Elderly Care, Child Care, Nursing Care & Medical Transportation.">
    <meta property="og:image" content="{{ asset('images/hero_caregiver.jpg') }}">
    <meta property="og:type" content="website">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/caremate.css') }}?v=2.7.0">
    @stack('styles')
</head>
<body>
    <!-- Top announcement banner -->
    <div class="top-announcement-banner" style="background: linear-gradient(90deg, #0a394a 0%, #15798e 100%); color: #ffffff; padding: 0.45rem 1rem; font-size: 0.82rem; text-align: center; font-weight: 600;">
        🛡️ CareMate Protected: 100% Background Verified • Admin-Mediated Coordination • No Direct Phone Exposing
    </div>

    <!-- Navigation Bar -->
    <header class="glass-header">
        <div class="container" style="display: flex; align-items: center; justify-content: space-between; padding-top: 0.75rem; padding-bottom: 0.75rem;">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" style="display: flex; align-items: center; text-decoration: none;">
                <img src="{{ asset('images/logo.png') }}" alt="CareMate BD" class="brand-logo-img" style="height: 44px; width: auto; object-fit: contain;">
            </a>

            <!-- Desktop Nav Links -->
            <nav style="display: flex; align-items: center; gap: 1.75rem;" class="desktop-nav">
                <a href="{{ route('services.index') }}" style="font-weight: 600; color: var(--text-secondary); font-size: 0.95rem;">Services</a>
                <a href="{{ route('marketplace.index') }}" style="font-weight: 600; color: var(--text-secondary); font-size: 0.95rem;">Caregivers</a>
                <a href="{{ route('how-it-works') }}" style="font-weight: 600; color: var(--text-secondary); font-size: 0.95rem;">How It Works</a>
                <a href="{{ route('about') }}" style="font-weight: 600; color: var(--text-secondary); font-size: 0.95rem;">About</a>
                <a href="{{ route('faq') }}" style="font-weight: 600; color: var(--text-secondary); font-size: 0.95rem;">FAQ</a>
                <a href="{{ route('contact') }}" style="font-weight: 600; color: var(--text-secondary); font-size: 0.95rem;">Contact</a>
            </nav>

            <!-- Actions -->
            <div style="display: flex; align-items: center; gap: 0.6rem;">
                <!-- Desktop-only Action buttons -->
                <div class="desktop-actions" style="display: flex; align-items: center; gap: 0.6rem;">
                    @auth
                        <a href="{{ route(auth()->user()->role->dashboardRoute()) }}" class="btn btn-secondary btn-sm" style="display: flex; align-items: center; gap: 0.4rem;">
                            <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}" style="width: 24px; height: 24px; border-radius: 50%;">
                            <span>Dashboard</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm" style="background: transparent; color: var(--text-muted); border: none; cursor: pointer;">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="font-weight: 600;">Login</a>
                        <a href="{{ route('caregiver.register') }}" class="btn btn-mint btn-sm" style="font-weight: 600;">Become Caregiver</a>
                        <a href="{{ route('marketplace.index') }}" class="btn btn-primary btn-sm" style="font-weight: 700;">Find Caregiver</a>
                    @endauth
                </div>

                <!-- Quick WhatsApp Header Button (Both Mobile & Desktop) -->
                <a href="https://wa.me/8801610296460?text=Hello%20CareMate%20BD%2C%20I%20would%20like%20to%20inquire%20about%20caregiver%20services." target="_blank" rel="noopener noreferrer" class="btn btn-sm header-whatsapp-btn" style="background: rgba(37, 211, 102, 0.14); color: #0d873d; border: 1px solid rgba(37, 211, 102, 0.35); font-weight: 700; display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.45rem 0.75rem;" title="Quick WhatsApp Chat">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.031 2C6.516 2 2.031 6.484 2.031 12C2.031 13.805 2.508 15.5 3.336 16.969L2 22L7.172 20.688C8.594 21.461 10.258 21.906 12.031 21.906C17.547 21.906 22.031 17.422 22.031 12C22.031 6.484 17.547 2 12.031 2ZM12.031 20.156C10.453 20.156 8.969 19.719 7.688 18.969L7.375 18.781L4.312 19.562L5.125 16.578L4.922 16.25C4.109 14.953 3.672 13.5 3.672 12C3.672 7.391 7.422 3.641 12.031 3.641C16.641 3.641 20.391 7.391 20.391 12C20.391 16.609 16.641 20.156 12.031 20.156ZM16.609 14.547C16.359 14.422 15.125 13.812 14.891 13.734C14.656 13.656 14.484 13.609 14.312 13.859C14.141 14.109 13.656 14.688 13.5 14.859C13.344 15.031 13.188 15.047 12.938 14.922C12.688 14.797 11.875 14.531 10.922 13.68C10.172 13.008 9.672 12.18 9.516 11.93C9.359 11.68 9.5 11.539 9.625 11.414C9.734 11.305 9.875 11.125 10 10.984C10.125 10.844 10.172 10.734 10.25 10.578C10.328 10.422 10.281 10.281 10.219 10.156C10.156 10.031 9.656 8.812 9.453 8.312C9.25 7.828 9.047 7.891 8.891 7.891C8.75 7.891 8.578 7.875 8.406 7.875C8.234 7.875 7.953 7.938 7.719 8.188C7.484 8.438 6.828 9.047 6.828 10.281C6.828 11.516 7.734 12.703 7.859 12.875C7.984 13.047 9.641 15.609 12.188 16.703C12.797 16.969 13.266 17.125 13.641 17.25C14.25 17.438 14.812 17.406 15.25 17.344C15.75 17.266 16.781 16.719 17 16.109C17.219 15.5 17.219 14.984 17.156 14.859C17.094 14.734 16.859 14.672 16.609 14.547Z"/></svg>
                    <span class="header-whatsapp-text">WhatsApp</span>
                </a>

                <!-- Mobile Hamburger Toggle Button -->
                <button type="button" class="mobile-menu-btn" id="mobileMenuToggleBtn" aria-label="Toggle navigation menu">
                    <svg id="hamburgerIcon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                    <svg id="closeIcon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div id="mobileNavDrawer" class="mobile-nav-drawer">
            <div style="display: flex; flex-direction: column; gap: 0.35rem; margin-bottom: 1.25rem;">
                <a href="{{ route('services.index') }}" class="mobile-nav-link">
                    <span>🏥 Care Services</span>
                    <span>→</span>
                </a>
                <a href="{{ route('marketplace.index') }}" class="mobile-nav-link">
                    <span>👩‍⚕️ Browse Caregivers</span>
                    <span>→</span>
                </a>
                <a href="{{ route('how-it-works') }}" class="mobile-nav-link">
                    <span>🔍 How It Works</span>
                    <span>→</span>
                </a>
                <a href="{{ route('about') }}" class="mobile-nav-link">
                    <span>🛡️ Safety & Verification</span>
                    <span>→</span>
                </a>
                <a href="{{ route('faq') }}" class="mobile-nav-link">
                    <span>❓ Frequently Asked Questions</span>
                    <span>→</span>
                </a>
                <a href="{{ route('contact') }}" class="mobile-nav-link">
                    <span>📞 Contact & Support</span>
                    <span>→</span>
                </a>
            </div>

            <!-- Mobile Quick Actions -->
            <div style="display: flex; flex-direction: column; gap: 0.65rem; padding-top: 1rem; border-top: 1px solid rgba(226, 232, 240, 0.8);">
                @auth
                    <a href="{{ route(auth()->user()->role->dashboardRoute()) }}" class="btn btn-primary" style="width: 100%;">
                        <span>Go to Dashboard ({{ ucfirst(auth()->user()->role->value) }})</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="width: 100%;">Logout</button>
                    </form>
                @else
                    <a href="{{ route('marketplace.index') }}" class="btn btn-primary" style="width: 100%; font-weight: 700;">
                        <span>Find a Verified Caregiver</span>
                    </a>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                        <a href="{{ route('caregiver.register') }}" class="btn btn-mint btn-sm" style="font-weight: 600; text-align: center;">Become Caregiver</a>
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="font-weight: 600; text-align: center;">Login</a>
                    </div>
                @endauth

                <!-- 24/7 Helpline Card -->
                <div style="background: rgba(10, 57, 74, 0.05); border: 1px solid rgba(10, 57, 74, 0.12); border-radius: var(--radius-md); padding: 0.85rem; margin-top: 0.5rem; text-align: center;">
                    <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">24/7 Care Coordinator Hotline</div>
                    <a href="tel:+8801610296460" style="font-size: 1.05rem; font-weight: 800; color: #0a394a; text-decoration: none; display: block; margin-top: 0.2rem;">📞 +880 1610-296460</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Global Flash Messages -->
    <div class="container" style="margin-top: 1rem;">
        @if (session('success'))
            <div class="alert alert-success">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <span>{{ session('warning') }}</span>
            </div>
        @endif
        @if (session('info'))
            <div class="alert alert-info">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer style="margin-top: 5rem; background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px); border-top: 1px solid rgba(226, 232, 240, 0.8); padding: 4rem 0 2rem 0;">
        <div class="container">
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1.5fr; gap: 2.5rem; margin-bottom: 3rem;" class="footer-grid">
                <!-- Column 1: Brand -->
                <div>
                    <a href="{{ route('home') }}" style="display: inline-block; margin-bottom: 0.25rem; text-decoration: none;">
                        <img src="{{ asset('images/logo.png') }}" alt="CareMate BD" style="height: 42px; width: auto; object-fit: contain;">
                    </a>
                    <div style="font-size: 0.82rem; font-weight: 700; color: #15798e; margin-bottom: 0.85rem; letter-spacing: 0.03em;">
                        A Concern of Techboloy
                    </div>
                    <p style="color: var(--text-secondary); font-size: 0.92rem; line-height: 1.6; margin-bottom: 1.25rem;">
                        Care that feels like family, found in minutes. Bangladesh's premier verified caregiver marketplace protecting family dignity and providing verified care.
                    </p>
                    <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(10, 57, 74, 0.08); border: 1px solid rgba(10, 57, 74, 0.15); padding: 0.4rem 0.8rem; border-radius: var(--radius-pill); font-size: 0.78rem; font-weight: 700; color: #0a394a;">
                        <span>✓ Government NID & Police Background Checked</span>
                    </div>
                </div>

                <!-- Column 2: Care Services -->
                <div>
                    <h4 style="font-size: 0.95rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-bottom: 1.25rem;">Care Services</h4>
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.65rem; font-size: 0.92rem;">
                        <li><a href="{{ route('services.show', 'elderly-care') }}" style="color: var(--text-secondary);">Elderly Care</a></li>
                        <li><a href="{{ route('services.show', 'child-care') }}" style="color: var(--text-secondary);">Child Care & Nanny</a></li>
                        <li><a href="{{ route('services.show', 'nursing-care') }}" style="color: var(--text-secondary);">Clinical Nursing Care</a></li>
                        <li><a href="{{ route('services.show', 'medical-transportation') }}" style="color: var(--text-secondary);">Medical Transportation</a></li>
                        <li><a href="{{ route('marketplace.index') }}" style="color: var(--brand-primary); font-weight: 600;">Browse All Caregivers →</a></li>
                    </ul>
                </div>

                <!-- Column 3: Platform -->
                <div>
                    <h4 style="font-size: 0.95rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-bottom: 1.25rem;">Platform</h4>
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.65rem; font-size: 0.92rem;">
                        <li><a href="{{ route('how-it-works') }}" style="color: var(--text-secondary);">How CareMate Works</a></li>
                        <li><a href="{{ route('about') }}" style="color: var(--text-secondary);">Safety & Verification</a></li>
                        <li><a href="{{ route('caregiver.register') }}" style="color: var(--text-secondary);">Apply as a Caregiver</a></li>
                        <li><a href="{{ route('faq') }}" style="color: var(--text-secondary);">Frequently Asked Questions</a></li>
                        <li><a href="{{ route('contact') }}" style="color: var(--text-secondary);">Care Coordination Desk</a></li>
                    </ul>
                </div>

                <!-- Column 4: Contact & Support -->
                <div>
                    <h4 style="font-size: 0.95rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-bottom: 1.25rem;">CareDesk Bangladesh</h4>
                    <p style="font-size: 0.88rem; color: var(--text-secondary); line-height: 1.6; margin-bottom: 0.75rem;">
                        📍 E-14/X, ICT Tower (14th Floor), Agargaon, Dhaka-1207, Bangladesh
                    </p>
                    <p style="font-size: 0.88rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        📞 <strong>Contact:</strong> <a href="tel:+8801610296460" style="color: inherit; text-decoration: none;">+880 1610-296460</a>
                    </p>
                    <p style="font-size: 0.88rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        💬 <strong>WhatsApp:</strong> <a href="https://wa.me/8801610296460?text=Hello%20CareMate%20BD%2C%20I%20would%20like%20to%20inquire%20about%20caregiver%20services." target="_blank" rel="noopener noreferrer" style="color: #075E54; font-weight: 700; text-decoration: none;">+880 1610-296460</a>
                    </p>
                    <p style="font-size: 0.88rem; color: var(--text-secondary); margin-bottom: 1.25rem;">
                        ✉️ <strong>Email:</strong> <a href="mailto:contact@carematebd.com" style="color: inherit; text-decoration: none;">contact@carematebd.com</a>
                    </p>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                        Operating hours: 24/7 Care Coordination
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div style="border-top: 1px solid rgba(226, 232, 240, 0.7); padding-top: 1.5rem; display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; color: var(--text-muted); flex-wrap: wrap; gap: 1rem;">
                <div>
                    © {{ date('Y') }} CareMate BD — A Concern of Techboloy. All rights reserved.
                </div>
                <div style="display: flex; gap: 1.5rem;">
                    <span>Privacy Policy</span>
                    <span>Terms of Service</span>
                    <span>Safety Standard</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggleBtn = document.getElementById('mobileMenuToggleBtn');
            var drawer = document.getElementById('mobileNavDrawer');
            var hamburgerIcon = document.getElementById('hamburgerIcon');
            var closeIcon = document.getElementById('closeIcon');

            if (toggleBtn && drawer) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    var isOpen = drawer.classList.toggle('open');
                    if (hamburgerIcon && closeIcon) {
                        hamburgerIcon.style.display = isOpen ? 'none' : 'block';
                        closeIcon.style.display = isOpen ? 'block' : 'none';
                    }
                });

                document.addEventListener('click', function(e) {
                    if (drawer.classList.contains('open') && !drawer.contains(e.target) && !toggleBtn.contains(e.target)) {
                        drawer.classList.remove('open');
                        if (hamburgerIcon && closeIcon) {
                            hamburgerIcon.style.display = 'block';
                            closeIcon.style.display = 'none';
                        }
                    }
                });
            }
        });
    </script>

    <!-- Mobile Native App Bottom Navigation Dock for Public/Guests -->
    <nav class="mobile-app-dock" aria-label="Mobile Bottom Navigation">
        <a href="{{ route('home') }}" class="mobile-app-dock-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            <span>Home</span>
        </a>
        <a href="{{ route('marketplace.index') }}" class="mobile-app-dock-item {{ request()->routeIs('marketplace.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <span>Caregivers</span>
        </a>
        <a href="{{ route('services.index') }}" class="mobile-app-dock-item {{ request()->routeIs('services.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            <span>Services</span>
        </a>
        <a href="{{ route('how-it-works') }}" class="mobile-app-dock-item {{ request()->routeIs('how-it-works') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <span>How It Works</span>
        </a>
        @auth
            @php $authRole = auth()->user()->role->value; @endphp
            <a href="{{ $authRole === 'admin' ? route('admin.dashboard') : ($authRole === 'caregiver' ? route('caregiver.dashboard') : route('client.dashboard')) }}" class="mobile-app-dock-item {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span>Portal</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="mobile-app-dock-item {{ request()->routeIs('login') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span>Login</span>
            </a>
        @endauth
    </nav>

    @stack('scripts')
</body>
</html>
