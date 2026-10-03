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
    <link rel="stylesheet" href="{{ asset('css/caremate.css') }}">
    @stack('styles')
</head>
<body>
    <!-- Top announcement banner -->
    <div style="background: linear-gradient(90deg, #0a394a 0%, #15798e 100%); color: #ffffff; padding: 0.45rem 1rem; font-size: 0.82rem; text-align: center; font-weight: 600;">
        🛡️ CareMate Protected: 100% Background Verified • Admin-Mediated Coordination • No Direct Phone Exposing
    </div>

    <!-- Navigation Bar -->
    <header class="glass-header">
        <div class="container" style="display: flex; align-items: center; justify-content: space-between; padding-top: 0.75rem; padding-bottom: 0.75rem;">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" style="display: flex; align-items: center; text-decoration: none;">
                <img src="{{ asset('images/logo.png') }}" alt="CareMate BD" style="height: 44px; width: auto; object-fit: contain;">
            </a>

            <!-- Nav Links -->
            <nav style="display: flex; align-items: center; gap: 1.75rem;" class="desktop-nav">
                <a href="{{ route('services.index') }}" style="font-weight: 600; color: var(--text-secondary); font-size: 0.95rem;">Services</a>
                <a href="{{ route('marketplace.index') }}" style="font-weight: 600; color: var(--text-secondary); font-size: 0.95rem;">Caregivers</a>
                <a href="{{ route('how-it-works') }}" style="font-weight: 600; color: var(--text-secondary); font-size: 0.95rem;">How It Works</a>
                <a href="{{ route('about') }}" style="font-weight: 600; color: var(--text-secondary); font-size: 0.95rem;">About</a>
                <a href="{{ route('faq') }}" style="font-weight: 600; color: var(--text-secondary); font-size: 0.95rem;">FAQ</a>
                <a href="{{ route('contact') }}" style="font-weight: 600; color: var(--text-secondary); font-size: 0.95rem;">Contact</a>
            </nav>

            <!-- Actions -->
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                @auth
                    <a href="{{ route(auth()->user()->role->dashboardRoute()) }}" class="btn btn-secondary btn-sm" style="display: flex; align-items: center; gap: 0.4rem;">
                        <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}" style="width: 24px; height: 24px; border-radius: 50%;">
                        <span>Dashboard ({{ ucfirst(auth()->user()->role->value) }})</span>
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

    @stack('scripts')
</body>
</html>
