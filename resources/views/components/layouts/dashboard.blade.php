<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'CareMate BD Dashboard' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/caremate.css') }}?v=2.9.0">
    <style>
        @media (max-width: 900px) {
            .dashboard-layout { display: block !important; }
            .dashboard-grid-2, .booking-layout, .booking-admin-layout, .earnings-layout, .request-grid, .support-layout, .ticket-layout, .job-layout, .schedule-layout, .edit-layout, .documents-layout {
                grid-template-columns: 1fr !important;
                gap: 1.25rem !important;
            }
            .dashboard-sidebar {
                position: fixed !important;
                top: 0 !important;
                bottom: 0 !important;
                left: -320px !important;
                width: 295px !important;
                max-width: 85vw !important;
                z-index: 10000 !important;
                background: #ffffff !important;
                box-shadow: 10px 0 35px rgba(10, 57, 74, 0.18) !important;
                transition: left 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
            }
            .dashboard-sidebar.mobile-open { left: 0 !important; }
            .dashboard-mobile-topbar { display: flex !important; }
            .dashboard-main { padding: 1rem 0.85rem calc(95px + env(safe-area-inset-bottom)) 0.85rem !important; }
            .dashboard-header-actions { display: none !important; }

            /* Responsive Form Cards & Action Buttons */
            .profile-form-card { padding: 1.25rem 0.85rem !important; }
            .form-row-2col, .form-row-3col, .form-row-4col, .services-checkbox-grid {
                grid-template-columns: 1fr !important;
                gap: 0.85rem !important;
            }
            .form-actions-footer,
            .dashboard-main form [style*="justify-content: flex-end"],
            .dashboard-main form [style*="justify-content:flex-end"] {
                flex-direction: column-reverse !important;
                align-items: stretch !important;
                gap: 0.75rem !important;
                width: 100% !important;
            }
            .form-actions-footer .btn,
            .dashboard-main form [style*="justify-content: flex-end"] .btn,
            .dashboard-main form [style*="justify-content:flex-end"] .btn {
                width: 100% !important;
                justify-content: center !important;
                text-align: center !important;
                padding: 0.85rem 1rem !important;
                box-sizing: border-box !important;
            }
            
            /* Native App 2x2 Compact Stats Grid */
            .stats-grid {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 0.65rem !important;
                margin-bottom: 1.25rem !important;
            }
            .stat-card {
                padding: 0.85rem 0.75rem !important;
                border-radius: 16px !important;
                background: #ffffff !important;
                border: 1px solid rgba(226, 232, 240, 0.85) !important;
                box-shadow: 0 2px 8px rgba(10, 57, 74, 0.04) !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                justify-content: center !important;
                gap: 0.15rem !important;
                min-height: 82px !important;
            }
            .stat-card .stat-label {
                font-size: 0.68rem !important;
                font-weight: 700 !important;
                text-transform: uppercase !important;
                color: #64748b !important;
                letter-spacing: 0.03em !important;
                line-height: 1.2 !important;
            }
            .stat-card .stat-value {
                font-size: 1.45rem !important;
                font-weight: 800 !important;
                line-height: 1.1 !important;
                margin: 0.15rem 0 0.1rem 0 !important;
            }
            .stat-card .stat-hint {
                font-size: 0.65rem !important;
                color: #94a3b8 !important;
                line-height: 1.2 !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                width: 100% !important;
                display: block !important;
            }

            .dashboard-main [style*="repeat(2, 1fr)"],
            .dashboard-main [style*="repeat(3, 1fr)"],
            .dashboard-main [style*="repeat(4, 1fr)"],
            .dashboard-main [style*="1fr 1fr"],
            .dashboard-main [style*="1.2fr 1fr"],
            .dashboard-main [style*="2fr 1fr"],
            .dashboard-main [style*="1.3fr 0.9fr"],
            .dashboard-main [style*="1fr 340px"],
            .dashboard-main [style*="1fr 360px"],
            .dashboard-main [style*="1fr 380px"],
            .dashboard-main [style*="1fr 320px"] {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }

            .table-container {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
                border-radius: 14px !important;
            }
            .glass-table {
                min-width: 560px !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    @php
        $user = auth()->user();
        $role = $user->role->value;
    @endphp

    <div class="dashboard-backdrop" id="dashboardBackdrop"></div>

    <div class="dashboard-layout">
        <!-- Mobile Topbar -->
        <div class="dashboard-mobile-topbar" style="display: none; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; background: #ffffff; border-bottom: 1px solid rgba(210, 228, 233, 0.8); width: 100%;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <button type="button" class="btn btn-secondary btn-sm" id="dashboardMobileMenuBtn" aria-label="Toggle Dashboard Menu" style="padding: 0.4rem 0.65rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <a href="{{ route('home') }}" style="display: flex; align-items: center; text-decoration: none;">
                    <img src="{{ asset('images/logo.png') }}" alt="CareMate BD" style="height: 32px; width: auto; object-fit: contain;">
                </a>
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <div class="lang-switch-toggle" title="Switch Language / ভাষা পরিবর্তন করুন">
                    <a href="{{ route('locale.switch', 'en') }}" class="lang-switch-pill {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                    <a href="{{ route('locale.switch', 'bn') }}" class="lang-switch-pill {{ app()->getLocale() === 'bn' ? 'active' : '' }}">বাংলা</a>
                </div>
                <x-badge :tone="$role === 'admin' ? 'danger' : ($role === 'caregiver' ? 'success' : 'primary')">
                    {{ strtoupper($role) }}
                </x-badge>
                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
            </div>
        </div>

        <!-- Sidebar -->
        <aside class="dashboard-sidebar" id="dashboardSidebar">
            <!-- Brand -->
            <div class="dashboard-sidebar-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; padding: 0.25rem 0.5rem;">
                <a href="{{ route('home') }}" style="display: flex; flex-direction: column; gap: 0.35rem; text-decoration: none;">
                    <img src="{{ asset('images/logo.png') }}" alt="CareMate BD" style="height: 38px; width: auto; object-fit: contain; align-self: flex-start;">
                    <span style="display: inline-block; font-size: 0.68rem; color: #15798e; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; padding-left: 2px;">{{ ucfirst($role) }} Portal</span>
                </a>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div class="lang-switch-toggle" title="Switch Language / ভাষা পরিবর্তন করুন">
                        <a href="{{ route('locale.switch', 'en') }}" class="lang-switch-pill {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                        <a href="{{ route('locale.switch', 'bn') }}" class="lang-switch-pill {{ app()->getLocale() === 'bn' ? 'active' : '' }}">বাংলা</a>
                    </div>
                    <button type="button" id="dashboardSidebarCloseBtn" class="mobile-only" aria-label="Close menu" style="background: rgba(10, 57, 74, 0.08); border: none; border-radius: 50%; width: 32px; height: 32px; display: none; align-items: center; justify-content: center; font-size: 1.1rem; color: var(--text-primary); cursor: pointer;">✕</button>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav style="flex: 1;">
                @if ($role === 'admin')
                    <div style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); letter-spacing: 0.06em; margin: 0.5rem 0.75rem;">{{ __('Operations Control') }}</div>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                    <a href="{{ route('admin.applications.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        <span>{{ __('Verification Queue') }}</span>
                    </a>
                    <a href="{{ route('admin.hiring-requests.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.hiring-requests.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 14 14"/></svg>
                        <span>{{ __('Hiring Requests') }}</span>
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <span>{{ __('Bookings') }}</span>
                    </a>
                    <a href="{{ route('admin.caregivers.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.caregivers.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span>{{ __('Caregivers') }}</span>
                    </a>
                    <a href="{{ route('admin.clients.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>{{ __('Clients') }}</span>
                    </a>

                    <div style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); letter-spacing: 0.06em; margin: 1.25rem 0.75rem 0.5rem 0.75rem;">{{ __('Finance & Service') }}</div>
                    <a href="{{ route('admin.payments.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        <span>{{ __('Payments') }}</span>
                    </a>
                    <a href="{{ route('admin.payouts.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.payouts.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        <span>{{ __('Payouts') }}</span>
                    </a>
                    <a href="{{ route('admin.services.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                        <span>{{ __('Services') }}</span>
                    </a>
                    <a href="{{ route('admin.locations.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>{{ __('Locations') }}</span>
                    </a>

                    <div style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); letter-spacing: 0.06em; margin: 1.25rem 0.75rem 0.5rem 0.75rem;">{{ __('Support & Audit') }}</div>
                    <a href="{{ route('admin.support.tickets') }}" class="sidebar-nav-item {{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span>{{ __('Support Tickets') }}</span>
                    </a>
                    <a href="{{ route('admin.disputes.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.disputes.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>{{ __('Disputes') }}</span>
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                        <span>{{ __('Reports & KPIs') }}</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                        <span>{{ __('Platform Settings') }}</span>
                    </a>
                    <a href="{{ route('admin.audit-logs') }}" class="sidebar-nav-item {{ request()->routeIs('admin.audit-logs') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        <span>{{ __('Audit Trail') }}</span>
                    </a>

                @elseif ($role === 'client')
                    <div style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); letter-spacing: 0.06em; margin: 0.5rem 0.75rem;">{{ __('Family Portal') }}</div>
                    <a href="{{ route('client.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        <span>{{ __('Overview') }}</span>
                    </a>
                    <a href="{{ route('marketplace.index') }}" class="sidebar-nav-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <span>{{ __('Find Caregiver') }}</span>
                    </a>
                    <a href="{{ route('client.requests.index') }}" class="sidebar-nav-item {{ request()->routeIs('client.requests.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 14 14"/></svg>
                        <span>{{ __('My Requests') }}</span>
                    </a>
                    <a href="{{ route('client.bookings.index') }}" class="sidebar-nav-item {{ request()->routeIs('client.bookings.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <span>{{ __('My Bookings') }}</span>
                    </a>
                    <a href="{{ route('client.favorites') }}" class="sidebar-nav-item {{ request()->routeIs('client.favorites') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        <span>{{ __('Saved Caregivers') }}</span>
                    </a>
                    <a href="{{ route('client.support.index') }}" class="sidebar-nav-item {{ request()->routeIs('client.support.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span>{{ __('Support Desk') }}</span>
                    </a>
                    <a href="{{ route('client.profile') }}" class="sidebar-nav-item {{ request()->routeIs('client.profile') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>{{ __('Profile & Address') }}</span>
                    </a>

                @elseif ($role === 'caregiver')
                    <div style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); letter-spacing: 0.06em; margin: 0.5rem 0.75rem;">{{ __('Care Provider') }}</div>
                    <a href="{{ route('caregiver.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('caregiver.dashboard') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        <span>{{ __('Overview') }}</span>
                    </a>
                    <a href="{{ route('caregiver.requests.index') }}" class="sidebar-nav-item {{ request()->routeIs('caregiver.requests.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 14 14"/></svg>
                        <span>{{ __('Care Requests') }}</span>
                    </a>
                    <a href="{{ route('caregiver.jobs.index') }}" class="sidebar-nav-item {{ request()->routeIs('caregiver.jobs.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <span>{{ __('My Jobs') }}</span>
                    </a>
                    <a href="{{ route('caregiver.availability') }}" class="sidebar-nav-item {{ request()->routeIs('caregiver.availability') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <span>{{ __('Availability Schedule') }}</span>
                    </a>
                    <a href="{{ route('caregiver.earnings') }}" class="sidebar-nav-item {{ request()->routeIs('caregiver.earnings') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        <span>{{ __('Earnings & Payouts') }}</span>
                    </a>
                    <a href="{{ route('caregiver.documents') }}" class="sidebar-nav-item {{ request()->routeIs('caregiver.documents') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <span>{{ __('Documents & NID') }}</span>
                    </a>
                    <a href="{{ route('caregiver.support.index') }}" class="sidebar-nav-item {{ request()->routeIs('caregiver.support.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span>{{ __('CareMate Support') }}</span>
                    </a>
                    <a href="{{ route('caregiver.profile') }}" class="sidebar-nav-item {{ request()->routeIs('caregiver.profile') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>{{ __('Profile & Rates') }}</span>
                    </a>
                @endif
            </nav>

            <!-- Bottom User Profile snippet -->
            <div style="margin-top: auto; padding-top: 1rem; border-top: 1px solid rgba(226, 232, 240, 0.8); display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 0.65rem;">
                    <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                    <div style="line-height: 1.2;">
                        <div style="font-weight: 700; font-size: 0.88rem; color: var(--text-primary); max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $user->name }}</div>
                        <div style="font-size: 0.72rem; color: var(--text-muted);">{{ ucfirst($role) }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout" style="background: none; border: none; cursor: pointer; color: var(--text-muted); padding: 0.4rem;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="dashboard-main">
            <!-- Header bar inside dashboard -->
            <header class="dashboard-content-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h1 style="font-size: 1.75rem; font-weight: 800; color: #0f172a;">{{ $header ?? 'Dashboard' }}</h1>
                    <p style="color: var(--text-muted); font-size: 0.92rem;">{{ $subheading ?? 'Welcome to CareMate BD management center.' }}</p>
                </div>

                <div class="dashboard-header-actions" style="display: flex; align-items: center; gap: 1rem;">
                    <!-- Public site link -->
                    <a href="{{ route('home') }}" class="btn btn-secondary btn-sm" style="font-size: 0.85rem;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        <span>Visit Website</span>
                    </a>

                    <!-- Role Badge -->
                    <x-badge :tone="$role === 'admin' ? 'danger' : ($role === 'caregiver' ? 'success' : 'primary')">
                        {{ strtoupper($role) }}
                    </x-badge>
                </div>
            </header>

            <!-- Flash alerts -->
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
            @if ($errors->any())
                <div class="alert alert-error" style="margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 0.75rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; margin-top: 0.15rem;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <div>
                        <strong style="display: block; font-weight: 700; margin-bottom: 0.25rem;">Form Submission Errors:</strong>
                        <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.88rem; line-height: 1.5;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var menuBtn = document.getElementById('dashboardMobileMenuBtn');
            var closeBtn = document.getElementById('dashboardSidebarCloseBtn');
            var sidebar = document.getElementById('dashboardSidebar');
            var backdrop = document.getElementById('dashboardBackdrop');

            function openSidebar() {
                if (sidebar) sidebar.classList.add('mobile-open');
                if (backdrop) backdrop.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                if (sidebar) sidebar.classList.remove('mobile-open');
                if (backdrop) backdrop.classList.remove('active');
                document.body.style.overflow = '';
            }

            if (menuBtn) menuBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);

            // Also close if any link inside sidebar is clicked on mobile
            if (sidebar) {
                var links = sidebar.querySelectorAll('a');
                links.forEach(function(l) {
                    l.addEventListener('click', function() {
                        if (window.innerWidth <= 900) {
                            closeSidebar();
                        }
                    });
                });
            }
        });
    </script>

        <!-- Mobile Native App Bottom Navigation Dock -->
        <nav class="mobile-app-dock" aria-label="Mobile Bottom Navigation">
            @if ($role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="mobile-app-dock-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    <span>{{ __('Dashboard') }}</span>
                </a>
                <a href="{{ route('admin.applications.index') }}" class="mobile-app-dock-item {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <span>{{ __('Verify') }}</span>
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="mobile-app-dock-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/></svg>
                    <span>{{ __('Bookings') }}</span>
                </a>
                <a href="{{ route('admin.payments.index') }}" class="mobile-app-dock-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    <span>{{ __('Finance') }}</span>
                </a>
                <a href="{{ route('admin.support.tickets') }}" class="mobile-app-dock-item {{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span>{{ __('Tickets') }}</span>
                </a>
            @elseif ($role === 'caregiver')
                <a href="{{ route('caregiver.dashboard') }}" class="mobile-app-dock-item {{ request()->routeIs('caregiver.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    <span>{{ __('Home') }}</span>
                </a>
                <a href="{{ route('caregiver.requests.index') }}" class="mobile-app-dock-item {{ request()->routeIs('caregiver.requests.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 14 14"/></svg>
                    <span>{{ __('Invites') }}</span>
                </a>
                <a href="{{ route('caregiver.jobs.index') }}" class="mobile-app-dock-item {{ request()->routeIs('caregiver.jobs.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    <span>{{ __('My Jobs') }}</span>
                </a>
                <a href="{{ route('caregiver.earnings') }}" class="mobile-app-dock-item {{ request()->routeIs('caregiver.earnings*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    <span>{{ __('Earnings') }}</span>
                </a>
                <a href="{{ route('caregiver.profile') }}" class="mobile-app-dock-item {{ request()->routeIs('caregiver.profile*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span>{{ __('Profile') }}</span>
                </a>
            @else
                <a href="{{ route('client.dashboard') }}" class="mobile-app-dock-item {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    <span>{{ __('Home') }}</span>
                </a>
                <a href="{{ route('marketplace.index') }}" class="mobile-app-dock-item {{ request()->routeIs('marketplace.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <span>{{ __('Find Care') }}</span>
                </a>
                <a href="{{ route('client.requests.index') }}" class="mobile-app-dock-item {{ request()->routeIs('client.requests.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 14 14"/></svg>
                    <span>{{ __('Requests') }}</span>
                </a>
                <a href="{{ route('client.bookings.index') }}" class="mobile-app-dock-item {{ request()->routeIs('client.bookings.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/></svg>
                    <span>{{ __('Bookings') }}</span>
                </a>
                <a href="{{ route('client.support.index') }}" class="mobile-app-dock-item {{ request()->routeIs('client.support.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span>{{ __('Support') }}</span>
                </a>
            @endif
        </nav>

    <!-- Auto-hide mobile dock when keyboard opens -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dock = document.querySelector('.mobile-app-dock');
            if (!dock) return;

            const isTextInput = function(el) {
                if (!el) return false;
                const tag = el.tagName;
                if (tag === 'TEXTAREA' || tag === 'SELECT') return true;
                if (tag === 'INPUT') {
                    const type = (el.type || 'text').toLowerCase();
                    return !['checkbox', 'radio', 'button', 'submit', 'reset', 'file'].includes(type);
                }
                return false;
            };

            document.addEventListener('focusin', function(e) {
                if (isTextInput(e.target)) {
                    dock.classList.add('dock-keyboard-hidden');
                    dock.style.setProperty('display', 'none', 'important');
                }
            });

            document.addEventListener('focusout', function(e) {
                if (isTextInput(e.target)) {
                    setTimeout(function() {
                        if (!isTextInput(document.activeElement)) {
                            dock.classList.remove('dock-keyboard-hidden');
                            dock.style.removeProperty('display');
                        }
                    }, 120);
                }
            });

            if (window.visualViewport) {
                var initialH = window.visualViewport.height;
                window.visualViewport.addEventListener('resize', function() {
                    if (window.visualViewport.height < initialH - 120) {
                        dock.classList.add('dock-keyboard-hidden');
                        dock.style.setProperty('display', 'none', 'important');
                    } else if (!isTextInput(document.activeElement)) {
                        dock.classList.remove('dock-keyboard-hidden');
                        dock.style.removeProperty('display');
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
