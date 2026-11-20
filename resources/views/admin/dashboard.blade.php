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
        </div>
    @endif

    <!-- Quick Action / Triage Grids -->
    <div class="dashboard-grid-2">
        <!-- Verification Queue (Pending Caregivers) -->
        <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a;">Verification Queue</h3>
                    <x-badge tone="warning">{{ $pendingApplications->count() }} Pending</x-badge>
                </div>
                <a href="{{ route('admin.applications.index') }}" style="font-size: 0.82rem; font-weight: 700; color: var(--brand-primary);">View All →</a>
            </div>

            @forelse ($pendingApplications as $app)
                <div style="background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(226, 232, 240, 0.9); padding: 1rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 0.75rem; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 0.85rem;">
                        <img src="{{ $app->avatarUrl() }}" alt="" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover;">
                        <div>
                            <div style="font-weight: 800; font-size: 0.95rem; color: #0f172a;">{{ $app->user->name }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">
                                {{ $app->caregiver_type }} • {{ $app->years_experience }}y Exp
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.applications.show', $app->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.8rem;">
                        Inspect Application →
                    </a>
                </div>
            @empty
                <div style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
                    <p>✓ All caregiver verification applications have been reviewed.</p>
                </div>
            @endforelse
        </div>

        <!-- Pending Hiring Requests (Client Triage) -->
        <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a;">Incoming Care Requests</h3>
                    <x-badge tone="primary">{{ $pendingRequests->count() }} Incoming</x-badge>
                </div>
                <a href="{{ route('admin.hiring-requests.index') }}" style="font-size: 0.82rem; font-weight: 700; color: var(--brand-primary);">View All →</a>
            </div>

            @forelse ($pendingRequests as $hr)
                <div style="background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(226, 232, 240, 0.9); padding: 1rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 0.75rem; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.95rem; color: #0f172a;">
                            #{{ $hr->request_reference }} • {{ $hr->service->name }}
                        </div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">
                            Client: {{ $hr->client->user->name }} • {{ $hr->care_recipient_relationship }}
                        </div>
                    </div>
                    <a href="{{ route('admin.hiring-requests.show', $hr->id) }}" class="btn btn-primary btn-sm" style="font-size: 0.8rem;">
                        Triage Request
                    </a>
                </div>
            @empty
                <div style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
                    <p>No new hiring requests pending admin action.</p>
                </div>
            @endforelse
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
