<x-layouts.dashboard>
    <x-slot:title>Admin Operations Center — CareMate BD</x-slot:title>
    <x-slot:header>Admin Operations Center</x-slot:header>
    <x-slot:subheading>Real-time platform metrics, verification queues, and care mediation workflows.</x-slot:subheading>

    <style>
        .stats-grid {
            display: grid !important;
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            gap: 1.25rem !important;
            margin-bottom: 2.25rem !important;
        }
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 1rem !important;
            }
        }
        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr !important;
                gap: 0.85rem !important;
            }
        }
    </style>

    <!-- Top 4 Metrics -->
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1.25rem; margin-bottom: 2.25rem;">
        <div class="glass-card stat-card">
            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                <span class="stat-label">Platform GMV (Volume)</span>
                <span style="font-size: 1.35rem; line-height: 1;">📊</span>
            </div>
            <div class="stat-value" style="color: #0a394a;">৳{{ number_format($stats['total_volume']) }}</div>
            <span class="stat-hint">Commission: ৳{{ number_format($stats['platform_revenue']) }}</span>
        </div>

        <div class="glass-card stat-card">
            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                <span class="stat-label">Pending Verifications</span>
                <span style="font-size: 1.35rem; line-height: 1;">🛡️</span>
            </div>
            <div class="stat-value" style="color: #d97706;">{{ $stats['pending_caregivers'] }}</div>
            <span class="stat-hint">Caregivers awaiting NID triage</span>
        </div>

        <div class="glass-card stat-card">
            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                <span class="stat-label">Active Care Bookings</span>
                <span style="font-size: 1.35rem; line-height: 1;">🩺</span>
            </div>
            <div class="stat-value" style="color: #059669;">{{ $stats['active_bookings'] }}</div>
            <span class="stat-hint">Shifts supervised right now</span>
        </div>

        <div class="glass-card stat-card">
            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                <span class="stat-label">Open Support Tickets</span>
                <span style="font-size: 1.35rem; line-height: 1;">🎧</span>
            </div>
            <div class="stat-value" style="color: {{ $stats['open_tickets'] > 0 ? '#e11d48' : '#059669' }};">
                {{ $stats['open_tickets'] }}
            </div>
            <span class="stat-hint">{{ $stats['active_disputes'] }} active dispute cases</span>
        </div>
    </div>

    <!-- Live Website Traffic & Visitor Pulse -->
    @if (isset($traffic))
        <div class="glass-card" style="padding: 1.35rem 1.5rem; border-radius: var(--radius-xl); margin-bottom: 2rem; background: linear-gradient(135deg, #0a394a 0%, #0f5268 100%); color: #ffffff;">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.85rem; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.65rem;">
                    <span style="width: 10px; height: 10px; border-radius: 50%; background: #34d399; box-shadow: 0 0 0 4px rgba(52, 211, 153, 0.25); display: inline-block;"></span>
                    <div>
                        <h3 style="font-size: 1.05rem; font-weight: 800; color: #ffffff; margin: 0;">Live Website Traffic & Visitor Analytics</h3>
                        <p style="font-size: 0.78rem; color: rgba(255,255,255,0.8); margin: 0.1rem 0 0 0;">Real-time public & portal visitor tracking across CareMate BD</p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-mint btn-sm" style="font-size: 0.78rem; font-weight: 800;">
                        Full Traffic Report →
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary btn-sm" style="font-size: 0.78rem; background: rgba(255,255,255,0.14); color: #ffffff; border-color: rgba(255,255,255,0.22);">
                        GA4 / Pixel Settings
                    </a>
                </div>
            </div>

            <div class="stats-grid" style="margin-bottom: 0 !important; gap: 0.85rem !important;">
                <div style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.18); border-radius: 14px; padding: 0.85rem 1rem;">
                    <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: rgba(255,255,255,0.75);">Active Visitors (15m)</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #6ee7b7; margin: 0.15rem 0;">{{ number_format($traffic['live_now']) }}</div>
                    <div style="font-size: 0.72rem; color: rgba(255,255,255,0.8);">Currently browsing site</div>
                </div>

                <div style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.18); border-radius: 14px; padding: 0.85rem 1rem;">
                    <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: rgba(255,255,255,0.75);">Today's Traffic</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #ffffff; margin: 0.15rem 0;">{{ number_format($traffic['today_unique']) }} <span style="font-size: 0.82rem; font-weight: 600; color: rgba(255,255,255,0.8);">visitors</span></div>
                    <div style="font-size: 0.72rem; color: rgba(255,255,255,0.8);">{{ number_format($traffic['today_views']) }} total pageviews today</div>
                </div>

                <div style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.18); border-radius: 14px; padding: 0.85rem 1rem;">
                    <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: rgba(255,255,255,0.75);">Last 7 Days</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #fde047; margin: 0.15rem 0;">{{ number_format($traffic['week_unique']) }} <span style="font-size: 0.82rem; font-weight: 600; color: rgba(255,255,255,0.8);">visitors</span></div>
                    <div style="font-size: 0.72rem; color: rgba(255,255,255,0.8);">{{ number_format($traffic['week_views']) }} pageviews in 7 days</div>
                </div>

                <div style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.18); border-radius: 14px; padding: 0.85rem 1rem;">
                    <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: rgba(255,255,255,0.75);">All-Time Volume</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #ffffff; margin: 0.15rem 0;">{{ number_format($traffic['total_views']) }} <span style="font-size: 0.82rem; font-weight: 600; color: rgba(255,255,255,0.8);">views</span></div>
                    <div style="font-size: 0.72rem; color: rgba(255,255,255,0.8);">📱 {{ $traffic['devices']['mobile']['percentage'] ?? 0 }}% Mobile • 💻 {{ $traffic['devices']['desktop']['percentage'] ?? 0 }}% Desktop</div>
                </div>
            </div>

            @if (!empty($traffic['countries']) || !empty($traffic['regions']))
                <div style="margin-top: 0.9rem; padding-top: 0.8rem; border-top: 1px solid rgba(255,255,255,0.16); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.65rem; font-size: 0.78rem;">
                    <div style="display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap;">
                        <span style="font-weight: 800; color: rgba(255,255,255,0.85);">🌍 Top Countries:</span>
                        @foreach (collect($traffic['countries'])->take(4) as $c)
                            <span style="background: rgba(255,255,255,0.14); padding: 0.2rem 0.55rem; border-radius: 999px; font-weight: 700;">
                                {{ $c['flag'] }} {{ $c['country'] }} ({{ $c['percentage'] }}%)
                            </span>
                        @endforeach
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap;">
                        <span style="font-weight: 800; color: rgba(255,255,255,0.85);">📍 Top Regions:</span>
                        @foreach (collect($traffic['regions'])->take(4) as $r)
                            <span style="background: rgba(16, 185, 129, 0.22); border: 1px solid rgba(110, 231, 183, 0.3); padding: 0.2rem 0.55rem; border-radius: 999px; font-weight: 700; color: #ecfdf5;">
                                {{ $r['region'] }} ({{ $r['views'] }})
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Quick Action / Triage Grids (Equal 50/50 Columns) -->
    <div class="dashboard-grid-2">
        <!-- Verification Queue (Pending Caregivers) -->
        <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl); display: flex; flex-direction: column;">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; margin-bottom: 1.15rem; padding-bottom: 0.85rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8);">
                <div style="display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;">
                    <h3 style="font-size: 1.12rem; font-weight: 800; color: #0f172a; margin: 0;">Verification Queue</h3>
                    <span style="display: inline-flex; align-items: center; padding: 0.22rem 0.65rem; border-radius: 999px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.03em; background: #fef3c7; color: #b45309; border: 1px solid rgba(245, 158, 11, 0.3); white-space: nowrap;">
                        {{ $pendingApplications->count() }} Pending
                    </span>
                </div>
                <a href="{{ route('admin.applications.index') }}" style="font-size: 0.82rem; font-weight: 700; color: var(--brand-primary); text-decoration: none; white-space: nowrap; flex-shrink: 0;">View All →</a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.75rem; flex: 1;">
                @forelse ($pendingApplications as $app)
                    <div style="background: #f8fafc; border: 1px solid rgba(226, 232, 240, 0.95); padding: 0.9rem 1.05rem; border-radius: 14px; display: flex; align-items: center; justify-content: space-between; gap: 0.85rem; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 0.8rem; min-width: 0; flex: 1;">
                            <img src="{{ $app->avatarUrl() }}" alt="{{ $app->user->name }}" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 2px solid #ffffff; box-shadow: 0 2px 6px rgba(10,57,74,0.08);">
                            <div style="min-width: 0;">
                                <div style="font-weight: 800; font-size: 0.94rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $app->user->name }}</div>
                                <div style="font-size: 0.78rem; color: #64748b; margin-top: 0.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ ucfirst($app->caregiver_type ?: 'Caregiver') }} • {{ (int) $app->years_experience }}y Exp {{ $app->district?->name ? '• '.$app->district->name : '' }}
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.applications.show', $app->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.78rem; font-weight: 700; padding: 0.42rem 0.9rem; min-height: 36px; white-space: nowrap; flex-shrink: 0;">
                            Inspect Application →
                        </a>
                    </div>
                @empty
                    <div style="text-align: center; padding: 2rem 1rem; color: var(--text-muted); background: #f8fafc; border: 1px dashed rgba(203, 213, 225, 0.9); border-radius: 14px; margin: auto 0;">
                        <p style="margin: 0; font-size: 0.88rem; font-weight: 600;">✓ All caregiver verification applications have been reviewed.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pending Hiring Requests (Client Triage) -->
        <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl); display: flex; flex-direction: column;">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; margin-bottom: 1.15rem; padding-bottom: 0.85rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8);">
                <div style="display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;">
                    <h3 style="font-size: 1.12rem; font-weight: 800; color: #0f172a; margin: 0;">Incoming Care Requests</h3>
                    <span style="display: inline-flex; align-items: center; padding: 0.22rem 0.65rem; border-radius: 999px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.03em; background: rgba(13, 148, 136, 0.12); color: #0f766e; border: 1px solid rgba(13, 148, 136, 0.28); white-space: nowrap;">
                        {{ $pendingRequests->count() }} Incoming
                    </span>
                </div>
                <a href="{{ route('admin.hiring-requests.index') }}" style="font-size: 0.82rem; font-weight: 700; color: var(--brand-primary); text-decoration: none; white-space: nowrap; flex-shrink: 0;">View All →</a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.75rem; flex: 1;">
                @forelse ($pendingRequests as $hr)
                    <div style="background: #f8fafc; border: 1px solid rgba(226, 232, 240, 0.95); padding: 0.9rem 1.05rem; border-radius: 14px; display: flex; align-items: center; justify-content: space-between; gap: 0.85rem; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 0.8rem; min-width: 0; flex: 1;">
                            <div style="width: 42px; height: 42px; border-radius: 12px; background: rgba(10, 57, 74, 0.08); color: #0a394a; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                                🩺
                            </div>
                            <div style="min-width: 0;">
                                <div style="display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap;">
                                    <span style="font-size: 0.7rem; font-weight: 800; color: #0284c7; background: rgba(14, 165, 233, 0.12); padding: 0.1rem 0.45rem; border-radius: 6px;">
                                        #{{ $hr->reference ?: ('REQ-'.$hr->id) }}
                                    </span>
                                    <span style="font-weight: 800; font-size: 0.92rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $hr->service?->name ?? 'Care Service' }}
                                    </span>
                                </div>
                                <div style="font-size: 0.78rem; color: #64748b; margin-top: 0.15rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    Client: <strong style="color: #334155;">{{ $hr->client?->user?->name ?? 'Family Client' }}</strong>
                                    @if ($hr->patient_name)
                                        • Patient: {{ $hr->patient_name }}
                                    @elseif ($hr->district?->name || $hr->area?->name)
                                        • 📍 {{ $hr->area?->name ?? $hr->district?->name }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.hiring-requests.show', $hr->id) }}" class="btn btn-primary btn-sm" style="font-size: 0.78rem; font-weight: 700; padding: 0.42rem 0.95rem; min-height: 36px; white-space: nowrap; flex-shrink: 0;">
                            Triage Request →
                        </a>
                    </div>
                @empty
                    <div style="text-align: center; padding: 2rem 1rem; color: var(--text-muted); background: #f8fafc; border: 1px dashed rgba(203, 213, 225, 0.9); border-radius: 14px; margin: auto 0;">
                        <p style="margin: 0; font-size: 0.88rem; font-weight: 600;">No new hiring requests pending admin action.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Active Recent Bookings Monitor -->
    <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
            <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a;">Active Supervised Bookings</h3>
            <a href="{{ route('admin.bookings.index') }}" style="font-size: 0.82rem; font-weight: 700; color: var(--brand-primary);">View All Bookings →</a>
        </div>

        <div class="table-container">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>Booking Ref</th>
                        <th>Client Family</th>
                        <th>Caregiver</th>
                        <th>Service</th>
                        <th>Dates</th>
                        <th>Total Volume</th>
                        <th>Status</th>
                        <th style="text-align: right;">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentBookings as $rb)
                        <tr>
                            <td style="font-weight: 700; color: var(--brand-primary);">
                                #{{ $rb->booking_reference }}
                            </td>
                            <td>{{ $rb->client->user->name }}</td>
                            <td>{{ $rb->caregiver->user->name }}</td>
                            <td>{{ $rb->service->name }}</td>
                            <td style="font-size: 0.85rem;">{{ $rb->start_date->format('M d') }} - {{ $rb->end_date->format('M d') }}</td>
                            <td style="font-weight: 700;">৳{{ number_format($rb->client_total) }}</td>
                            <td><x-badge :tone="$rb->status->badgeTone()">{{ $rb->status->label() }}</x-badge></td>
                            <td style="text-align: right;">
                                <a href="{{ route('admin.bookings.show', $rb->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.8rem;">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.dashboard>
