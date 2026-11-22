<x-layouts.dashboard>
    <x-slot:title>Caregiver Overview — CareMate BD</x-slot:title>
    <x-slot:header>Caregiver Overview</x-slot:header>
    <x-slot:subheading>Track your shift invitations, active care bookings, earnings, and verification status.</x-slot:subheading>

    @php
        $user = auth()->user();
        $completion = $caregiver->profileCompletion();
        $pct = $completion['percentage'];
        $progressColor = $pct >= 100 ? '#10b981' : ($pct >= 70 ? '#0d9488' : '#f59e0b');
        $activeBookings = $activeBookings ?? $upcomingJobs ?? $recentJobs ?? collect();
        $assignedRequests = $assignedRequests ?? $recentRequests ?? collect();
        $walletBalanceVal = $availableBalance ?? $caregiver->wallet_balance ?? 0;
        $totalEarningsVal = $totalEarnings ?? $caregiver->total_earnings ?? 0;
        $activeJobsVal = $activeJobsCount ?? $activeBookings->count();
        $completedJobsVal = $completedJobsCount ?? $caregiver->total_bookings_completed ?? 0;
    @endphp

    @push('styles')
    <style>
        .cg-overview-wrap {
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
        }
        /* Top Unified Status & Profile Completion Banner */
        .cg-hero-banner {
            background: linear-gradient(135deg, #0a394a 0%, #0f5268 100%);
            border-radius: 18px;
            padding: 1.05rem 1.2rem;
            color: #ffffff;
            box-shadow: 0 8px 22px rgba(10, 57, 74, 0.14);
        }
        .cg-hero-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.85rem;
            flex-wrap: wrap;
        }
        .cg-hero-profile {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            min-width: 0;
            text-decoration: none;
            color: #ffffff;
        }
        .cg-hero-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            border: 2.5px solid rgba(255, 255, 255, 0.85);
            background: #ffffff;
            flex-shrink: 0;
        }
        .cg-hero-progress-bar {
            margin-top: 0.85rem;
            padding-top: 0.75rem;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.85rem;
            flex-wrap: wrap;
        }
        /* Compact 2x2 / 4-col KPI Grid */
        .cg-kpi-grid {
            display: grid !important;
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            gap: 0.85rem !important;
            margin: 0 !important;
        }
        .cg-kpi-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 16px;
            padding: 0.95rem 1rem;
            box-shadow: 0 2px 10px rgba(10, 57, 74, 0.04);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 0.25rem;
            text-decoration: none;
            transition: transform 0.15s ease, border-color 0.15s ease;
        }
        .cg-kpi-card:hover {
            transform: translateY(-1px);
            border-color: rgba(13, 148, 136, 0.35);
        }
        .cg-kpi-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.4rem;
        }
        .cg-kpi-label {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #64748b;
        }
        .cg-kpi-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        .cg-kpi-val {
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1.15;
            color: #0f172a;
            margin: 0.1rem 0;
        }
        .cg-kpi-sub {
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        /* Quick Action Shortcuts Row */
        .cg-quick-actions {
            display: grid !important;
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            gap: 0.6rem !important;
        }
        .cg-quick-pill {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            padding: 0.6rem 0.65rem;
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.95);
            border-radius: 13px;
            font-size: 0.78rem;
            font-weight: 700;
            color: #0f172a;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(10, 57, 74, 0.03);
            transition: all 0.15s ease;
            white-space: nowrap;
        }
        .cg-quick-pill:hover {
            background: #f0fdfa;
            border-color: rgba(13, 148, 136, 0.35);
            color: #0d9488;
        }
        /* Two-column Section Panels on Desktop, Tight Stack on Mobile */
        .cg-panels-grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 0.9rem !important;
            align-items: stretch !important;
        }
        .cg-panel-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 18px;
            padding: 1.1rem 1.15rem;
            box-shadow: 0 3px 12px rgba(10, 57, 74, 0.04);
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .cg-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            padding-bottom: 0.65rem;
            border-bottom: 1px solid rgba(226, 232, 240, 0.75);
        }
        .cg-empty-compact {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.95rem 1rem;
            background: #f8fafc;
            border: 1px dashed rgba(203, 213, 225, 0.9);
            border-radius: 14px;
        }
        .cg-item-card {
            padding: 0.85rem 0.95rem;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid rgba(226, 232, 240, 0.9);
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }
        @media (max-width: 1100px) {
            .cg-panels-grid {
                grid-template-columns: 1fr !important;
                gap: 0.85rem !important;
            }
        }
        @media (max-width: 900px) {
            .cg-kpi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 0.6rem !important;
            }
            .cg-quick-actions {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 0.5rem !important;
            }
            .cg-hero-banner {
                padding: 0.95rem 1rem;
                border-radius: 16px;
            }
            .cg-panel-card {
                padding: 0.95rem 0.9rem;
                border-radius: 16px;
            }
            .cg-kpi-card {
                padding: 0.8rem 0.85rem;
            }
            .cg-kpi-val {
                font-size: 1.32rem;
            }
        }
    </style>
    @endpush

    <div class="cg-overview-wrap">
        <!-- 1. COMPACT HERO STATUS & PROFILE COMPLETION BANNER -->
        <div class="cg-hero-banner">
            <div class="cg-hero-row">
                <a href="{{ route('caregiver.profile') }}" class="cg-hero-profile" title="Open My Profile & Hub">
                    <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="cg-hero-avatar">
                    <div style="min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap;">
                            <span style="font-size: 1.08rem; font-weight: 800; color: #ffffff; line-height: 1.2;">{{ $user->name }}</span>
                            <span style="font-size: 0.68rem; font-weight: 800; text-transform: uppercase; padding: 0.16rem 0.5rem; border-radius: 999px; background: rgba(255,255,255,0.18); color: #ffffff;">
                                {{ $caregiver->status->label() }}
                            </span>
                        </div>
                        <div style="font-size: 0.78rem; color: rgba(255, 255, 255, 0.82); margin-top: 0.15rem;">
                            {{ $caregiver->qualification_summary ?: 'Verified Care Provider' }} • ৳{{ number_format((float)$caregiver->daily_rate) }}/day
                        </div>
                    </div>
                </a>

                <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                    <a href="{{ route('caregiver.availability') }}" style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.42rem 0.8rem; border-radius: 999px; border: 1px solid rgba(255,255,255,0.25); background: {{ $caregiver->is_available ? 'rgba(16, 185, 129, 0.25)' : 'rgba(244, 63, 94, 0.25)' }}; color: #ffffff; font-size: 0.76rem; font-weight: 700; text-decoration: none;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $caregiver->is_available ? '#34d399' : '#fb7185' }};"></span>
                        <span>{{ $caregiver->is_available ? 'Available for Shifts' : 'Currently Offline' }}</span>
                    </a>
                </div>
            </div>

            <!-- Integrated Profile Completion / Onboarding Status Bar -->
            <div class="cg-hero-progress-bar">
                <div style="flex: 1; min-width: 200px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.76rem; font-weight: 700; margin-bottom: 0.3rem;">
                        <span style="color: rgba(255,255,255,0.9);">
                            @if ($caregiver->status->value === 'draft')
                                ⚠️ Application Incomplete — Submit 9-step onboarding
                            @elseif ($caregiver->status->value === 'changes_required')
                                ⚠️ Action Required: {{ Str::limit($caregiver->rejection_reason ?: 'Please update your verification details.', 55) }}
                            @elseif ($pct < 100)
                                Profile Completion ({{ $completion['completed_count'] }}/{{ $completion['total_count'] }} sections)
                            @else
                                ✓ Profile 100% Complete & Optimized
                            @endif
                        </span>
                        <span style="color: {{ $pct >= 100 ? '#6ee7b7' : '#fde047' }}; font-weight: 800;">{{ $pct }}%</span>
                    </div>
                    <div style="width: 100%; height: 7px; background: rgba(255, 255, 255, 0.18); border-radius: 999px; overflow: hidden;">
                        <div style="width: {{ $pct }}%; height: 100%; background: {{ $progressColor }}; border-radius: 999px;"></div>
                    </div>
                </div>

                @if (in_array($caregiver->status->value, ['draft', 'changes_required']))
                    <a href="{{ route('caregiver.register') }}" class="btn btn-mint btn-sm" style="padding: 0.38rem 0.85rem; font-size: 0.76rem; font-weight: 800; min-height: 34px;">
                        Complete Verification →
                    </a>
                @elseif ($pct < 100)
                    <a href="{{ route('caregiver.profile') }}" class="btn btn-mint btn-sm" style="padding: 0.38rem 0.85rem; font-size: 0.76rem; font-weight: 800; min-height: 34px;">
                        Complete Profile →
                    </a>
                @else
                    <a href="{{ route('caregiver.profile') }}" style="font-size: 0.76rem; font-weight: 700; color: #5eead4; text-decoration: none; white-space: nowrap;">
                        Manage Profile →
                    </a>
                @endif
            </div>
        </div>

        <!-- 2. COMPACT 2x2 KPI GRID -->
        <div class="cg-kpi-grid">
            <a href="{{ route('caregiver.earnings') }}" class="cg-kpi-card">
                <div class="cg-kpi-top">
                    <span class="cg-kpi-label">Wallet Balance</span>
                    <span class="cg-kpi-icon" style="background: rgba(16, 185, 129, 0.12); color: #059669;">৳</span>
                </div>
                <div class="cg-kpi-val" style="color: #059669;">৳{{ number_format((float)$walletBalanceVal) }}</div>
                <div class="cg-kpi-sub">Total: ৳{{ number_format((float)$totalEarningsVal) }}</div>
            </a>

            <a href="{{ route('caregiver.jobs.index') }}" class="cg-kpi-card">
                <div class="cg-kpi-top">
                    <span class="cg-kpi-label">Active Jobs</span>
                    <span class="cg-kpi-icon" style="background: rgba(14, 165, 233, 0.12); color: #0284c7;">💼</span>
                </div>
                <div class="cg-kpi-val" style="color: #0284c7;">{{ $activeJobsVal }}</div>
                <div class="cg-kpi-sub">{{ $completedJobsVal }} completed shifts</div>
            </a>

            <a href="{{ route('caregiver.requests.index') }}" class="cg-kpi-card">
                <div class="cg-kpi-top">
                    <span class="cg-kpi-label">Care Invites</span>
                    <span class="cg-kpi-icon" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">📩</span>
                </div>
                <div class="cg-kpi-val" style="color: #d97706;">{{ $assignedRequests->count() }}</div>
                <div class="cg-kpi-sub">Awaiting response</div>
            </a>

            <a href="{{ route('caregiver.profile') }}" class="cg-kpi-card">
                <div class="cg-kpi-top">
                    <span class="cg-kpi-label">Rating Score</span>
                    <span class="cg-kpi-icon" style="background: rgba(139, 92, 246, 0.12); color: #7c3aed;">★</span>
                </div>
                <div class="cg-kpi-val" style="color: #0f172a;">
                    {{ number_format((float)$caregiver->rating_average, 1) }} <span style="color: #f59e0b; font-size: 1.05rem;">★</span>
                </div>
                <div class="cg-kpi-sub">{{ $caregiver->rating_count }} family reviews</div>
            </a>
        </div>

        <!-- 3. QUICK ACTION SHORTCUT PILLS -->
        <div class="cg-quick-actions">
            <a href="{{ route('caregiver.availability') }}" class="cg-quick-pill">
                <span>📅</span>
                <span>Schedule</span>
            </a>
            <a href="{{ route('caregiver.documents') }}" class="cg-quick-pill">
                <span>🪪</span>
                <span>NID & Docs</span>
            </a>
            <a href="{{ route('caregiver.earnings') }}" class="cg-quick-pill">
                <span>💰</span>
                <span>Payouts</span>
            </a>
            <a href="{{ route('caregiver.profile') }}" class="cg-quick-pill">
                <span>⚙️</span>
                <span>Profile Hub</span>
            </a>
        </div>

        <!-- 4. ASSIGNED CARE REQUESTS & ACTIVE JOBS PANELS -->
        <div class="cg-panels-grid">
            <!-- Panel A: Assigned Care Requests -->
            <div class="cg-panel-card">
                <div class="cg-panel-header">
                    <div>
                        <h2 style="font-size: 0.98rem; font-weight: 800; color: #0f172a; margin: 0;">Assigned Care Requests</h2>
                        <p style="font-size: 0.74rem; color: #64748b; margin: 0.1rem 0 0 0;">Direct family invitations & coordinator matches</p>
                    </div>
                    <a href="{{ route('caregiver.requests.index') }}" style="font-size: 0.76rem; font-weight: 700; color: #0d9488; text-decoration: none; white-space: nowrap;">
                        View All ({{ $assignedRequests->count() }}) →
                    </a>
                </div>

                @if ($assignedRequests->isEmpty())
                    <div class="cg-empty-compact">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(13, 148, 136, 0.1); color: #0d9488; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">📩</div>
                        <div style="min-width: 0; flex: 1;">
                            <div style="font-weight: 800; font-size: 0.85rem; color: #0f172a;">No pending care invitations</div>
                            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.1rem;">Keep your availability online and profile 100% complete to receive priority matches.</div>
                        </div>
                    </div>
                @else
                    <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                        @foreach ($assignedRequests->take(4) as $req)
                            <div class="cg-item-card">
                                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.5rem;">
                                    <div style="min-width: 0;">
                                        <div style="display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap;">
                                            <span style="font-size: 0.7rem; font-weight: 800; color: #0284c7; background: rgba(14,165,233,0.1); padding: 0.12rem 0.45rem; border-radius: 6px;">#{{ $req->request_reference ?? $req->reference_code }}</span>
                                            <x-badge :tone="method_exists($req->status, 'badgeTone') ? $req->status->badgeTone() : $req->status->color()">{{ $req->status->label() }}</x-badge>
                                        </div>
                                        <div style="font-weight: 800; font-size: 0.9rem; color: #0f172a; margin-top: 0.3rem;">
                                            {{ $req->care_recipient_name ?? $req->patient_name ?? 'Care Recipient' }} ({{ $req->care_recipient_age ?? $req->patient_age }} yrs) — {{ $req->service?->name ?? $req->serviceCategory?->name }}
                                        </div>
                                        <div style="font-size: 0.76rem; color: #64748b; margin-top: 0.15rem;">
                                            📍 {{ $req->area?->name }}, {{ $req->district?->name }} • 🗓️ {{ ($req->start_date ?? $req->preferred_start_date)?->format('d M Y') }} • <strong style="color: #059669;">৳{{ number_format((float)($req->caregiver_net_amount ?? 0)) }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.45rem; padding-top: 0.45rem; border-top: 1px solid rgba(226, 232, 240, 0.7);">
                                    <a href="{{ route('caregiver.requests.show', $req) }}" class="btn btn-secondary btn-sm" style="padding: 0.32rem 0.7rem; font-size: 0.75rem; min-height: 32px;">Review Details</a>
                                    @if (in_array($req->status->value, ['submitted', 'under_review', 'caregiver_assigned', 'caregiver_notified']))
                                        <form method="POST" action="{{ route('caregiver.requests.accept', $req) }}" style="margin: 0;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm" style="padding: 0.32rem 0.75rem; font-size: 0.75rem; min-height: 32px;">Accept</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Panel B: Active & Upcoming Jobs -->
            <div class="cg-panel-card">
                <div class="cg-panel-header">
                    <div>
                        <h2 style="font-size: 0.98rem; font-weight: 800; color: #0f172a; margin: 0;">Active & Upcoming Jobs</h2>
                        <p style="font-size: 0.74rem; color: #64748b; margin: 0.1rem 0 0 0;">Confirmed shift assignments & ongoing care</p>
                    </div>
                    <a href="{{ route('caregiver.jobs.index') }}" style="font-size: 0.76rem; font-weight: 700; color: #0d9488; text-decoration: none; white-space: nowrap;">
                        All Jobs ({{ $activeBookings->count() }}) →
                    </a>
                </div>

                @if ($activeBookings->isEmpty())
                    <div class="cg-empty-compact">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(14, 165, 233, 0.1); color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">💼</div>
                        <div style="min-width: 0; flex: 1;">
                            <div style="font-weight: 800; font-size: 0.85rem; color: #0f172a;">No active shift bookings right now</div>
                            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.1rem;">When you accept a family care request and it is confirmed, your job schedule appears here.</div>
                        </div>
                    </div>
                @else
                    <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                        @foreach ($activeBookings->take(4) as $booking)
                            <div class="cg-item-card">
                                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.5rem;">
                                    <div style="min-width: 0;">
                                        <div style="display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap;">
                                            <span style="font-size: 0.7rem; font-weight: 800; color: #0d9488; background: rgba(13,148,136,0.1); padding: 0.12rem 0.45rem; border-radius: 6px;">#{{ $booking->booking_reference ?? $booking->booking_number }}</span>
                                            <x-badge :tone="method_exists($booking->status, 'badgeTone') ? $booking->status->badgeTone() : $booking->status->color()">{{ $booking->status->label() }}</x-badge>
                                        </div>
                                        <div style="font-weight: 800; font-size: 0.9rem; color: #0f172a; margin-top: 0.3rem;">
                                            {{ $booking->care_recipient_name ?? $booking->hiringRequest?->patient_name ?? $booking->client?->user?->name }} — {{ $booking->service?->name ?? $booking->serviceCategory?->name }}
                                        </div>
                                        <div style="font-size: 0.76rem; color: #64748b; margin-top: 0.15rem;">
                                            🗓️ {{ $booking->start_date?->format('d M Y') }} – {{ $booking->end_date?->format('d M Y') }} • <strong style="color: #059669;">৳{{ number_format((float)($booking->caregiver_net_pay ?? $booking->caregiver_earnings ?? 0)) }}</strong>
                                        </div>
                                    </div>
                                    <a href="{{ route('caregiver.jobs.show', $booking) }}" class="btn btn-primary btn-sm" style="padding: 0.32rem 0.75rem; font-size: 0.75rem; min-height: 32px; flex-shrink: 0;">
                                        Manage →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.dashboard>
