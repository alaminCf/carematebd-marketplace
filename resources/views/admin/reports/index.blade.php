<x-layouts.dashboard>
    <x-slot:title>Reports & KPIs — Admin | CareMate BD</x-slot:title>
    <x-slot:header>Operations & Financial Intelligence</x-slot:header>
    <x-slot:subheading>Comprehensive metrics on gross platform volume, completed care shifts, service demand, and caregiver geographic distribution.</x-slot:subheading>

    <!-- Date Range Filter Bar -->
    <div class="glass-card" style="padding: 1.25rem 1.75rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
        <form method="GET" action="{{ route('admin.reports.index') }}" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">
                    📅 Reporting Period:
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <label style="font-size: 0.8rem; color: var(--text-muted);">From:</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="glass-input" style="font-size: 0.85rem; padding: 0.35rem 0.65rem;">
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <label style="font-size: 0.8rem; color: var(--text-muted);">To:</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="glass-input" style="font-size: 0.85rem; padding: 0.35rem 0.65rem;">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Filter Metrics</button>
            </div>

            <button type="button" onclick="window.print()" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 0.4rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Print Report
            </button>
        </form>
    </div>

    <!-- Website Traffic & Visitor Analytics Section -->
    @if (isset($traffic))
        <div style="margin-bottom: 2.25rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                <h3 style="font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.82rem; color: var(--text-muted); margin: 0;">
                    🌐 Website Traffic & Visitor Analytics ({{ $startDate }} to {{ $endDate }})
                </h3>
                <a href="{{ route('admin.settings.index') }}" style="font-size: 0.8rem; font-weight: 700; color: #0d9488; text-decoration: none;">
                    Configure Google Analytics (GA4) & Meta Pixel →
                </a>
            </div>

            <!-- 4 Traffic KPI Cards -->
            <div class="stats-grid" style="margin-bottom: 1.25rem !important;">
                <div class="glass-card stat-card" style="border-left: 4px solid #10b981;">
                    <span class="stat-label">Active Now (Last 15m)</span>
                    <div class="stat-value" style="color: #059669;">{{ number_format($traffic['live_now']) }}</div>
                    <span class="stat-hint">Real-time active visitors</span>
                </div>

                <div class="glass-card stat-card" style="border-left: 4px solid #0284c7;">
                    <span class="stat-label">Today's Traffic</span>
                    <div class="stat-value" style="color: #0284c7;">{{ number_format($traffic['today_unique']) }}</div>
                    <span class="stat-hint">{{ number_format($traffic['today_views']) }} pageviews today</span>
                </div>

                <div class="glass-card stat-card" style="border-left: 4px solid #0a394a;">
                    <span class="stat-label">Period Unique Visitors</span>
                    <div class="stat-value" style="color: #0a394a;">{{ number_format($traffic['period_unique']) }}</div>
                    <span class="stat-hint">Distinct visitors in window</span>
                </div>

                <div class="glass-card stat-card" style="border-left: 4px solid #8b5cf6;">
                    <span class="stat-label">Period Pageviews</span>
                    <div class="stat-value" style="color: #6d28d9;">{{ number_format($traffic['period_views']) }}</div>
                    <span class="stat-hint">All-time: {{ number_format($traffic['total_views']) }} views</span>
                </div>
            </div>

            <!-- 7-Day Trend + Top Pages + Sources/Devices -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.25rem;">
                <!-- Top Visited Pages -->
                <div class="glass-card" style="padding: 1.35rem; border-radius: var(--radius-xl);">
                    <h4 style="font-size: 0.98rem; font-weight: 800; color: #0f172a; margin: 0 0 0.85rem 0;">
                        🔥 Most Visited Pages
                    </h4>
                    <div style="display: flex; flex-direction: column; gap: 0.55rem;">
                        @forelse ($traffic['top_pages'] as $tp)
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.55rem 0.75rem; background: #f8fafc; border: 1px solid rgba(226, 232, 240, 0.9); border-radius: 10px; gap: 0.5rem;">
                                <span style="font-weight: 700; font-size: 0.82rem; color: #0a394a; word-break: break-all;">{{ $tp['path'] }}</span>
                                <span style="font-size: 0.78rem; font-weight: 800; color: #0f172a; white-space: nowrap;">
                                    {{ number_format($tp['views']) }} views <span style="color: #64748b; font-weight: 600;">({{ number_format($tp['unique_visitors']) }} uniq)</span>
                                </span>
                            </div>
                        @empty
                            <div style="padding: 1rem; text-align: center; color: var(--text-muted); font-size: 0.84rem;">
                                Visitor pageviews will appear here as traffic arrives.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Traffic Sources & Device Breakdown -->
                <div class="glass-card" style="padding: 1.35rem; border-radius: var(--radius-xl);">
                    <h4 style="font-size: 0.98rem; font-weight: 800; color: #0f172a; margin: 0 0 0.85rem 0;">
                        📡 Traffic Sources & Devices
                    </h4>
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.74rem; font-weight: 800; text-transform: uppercase; color: #64748b; margin-bottom: 0.5rem;">Acquisition Channels</div>
                        <div style="display: flex; flex-direction: column; gap: 0.45rem;">
                            @foreach ($traffic['sources'] as $srcName => $srcData)
                                <div>
                                    <div style="display: flex; justify-content: space-between; font-size: 0.78rem; font-weight: 700; margin-bottom: 0.18rem;">
                                        <span>{{ $srcName }}</span>
                                        <span>{{ number_format($srcData['count']) }} ({{ $srcData['percentage'] }}%)</span>
                                    </div>
                                    <div style="width: 100%; height: 6px; background: #e2e8f0; border-radius: 999px; overflow: hidden;">
                                        <div style="width: {{ $srcData['percentage'] }}%; height: 100%; background: #0d9488; border-radius: 999px;"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div style="padding-top: 0.75rem; border-top: 1px solid rgba(226, 232, 240, 0.8);">
                        <div style="font-size: 0.74rem; font-weight: 800; text-transform: uppercase; color: #64748b; margin-bottom: 0.5rem;">Visitor Devices</div>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem;">
                            <div style="background: #f8fafc; border: 1px solid rgba(226, 232, 240, 0.9); border-radius: 10px; padding: 0.55rem; text-align: center;">
                                <div style="font-size: 0.72rem; color: #64748b; font-weight: 700;">📱 Mobile</div>
                                <div style="font-size: 1rem; font-weight: 800; color: #0f172a;">{{ $traffic['devices']['mobile']['percentage'] ?? 0 }}%</div>
                                <div style="font-size: 0.68rem; color: #94a3b8;">{{ $traffic['devices']['mobile']['count'] ?? 0 }} visits</div>
                            </div>
                            <div style="background: #f8fafc; border: 1px solid rgba(226, 232, 240, 0.9); border-radius: 10px; padding: 0.55rem; text-align: center;">
                                <div style="font-size: 0.72rem; color: #64748b; font-weight: 700;">💻 Desktop</div>
                                <div style="font-size: 1rem; font-weight: 800; color: #0f172a;">{{ $traffic['devices']['desktop']['percentage'] ?? 0 }}%</div>
                                <div style="font-size: 0.68rem; color: #94a3b8;">{{ $traffic['devices']['desktop']['count'] ?? 0 }} visits</div>
                            </div>
                            <div style="background: #f8fafc; border: 1px solid rgba(226, 232, 240, 0.9); border-radius: 10px; padding: 0.55rem; text-align: center;">
                                <div style="font-size: 0.72rem; color: #64748b; font-weight: 700;">📟 Tablet</div>
                                <div style="font-size: 1rem; font-weight: 800; color: #0f172a;">{{ $traffic['devices']['tablet']['percentage'] ?? 0 }}%</div>
                                <div style="font-size: 0.68rem; color: #94a3b8;">{{ $traffic['devices']['tablet']['count'] ?? 0 }} visits</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Last 7 Days Daily Traffic Trend -->
                <div class="glass-card" style="padding: 1.35rem; border-radius: var(--radius-xl);">
                    <h4 style="font-size: 0.98rem; font-weight: 800; color: #0f172a; margin: 0 0 0.85rem 0;">
                        📅 Last 7 Days Daily Traffic
                    </h4>
                    @php
                        $maxDayViews = max(1, collect($traffic['daily_trend'])->max('views') ?? 1);
                    @endphp
                    <div style="display: flex; flex-direction: column; gap: 0.55rem;">
                        @foreach ($traffic['daily_trend'] as $day)
                            <div>
                                <div style="display: flex; justify-content: space-between; font-size: 0.77rem; font-weight: 700; margin-bottom: 0.15rem;">
                                    <span style="color: #334155;">{{ $day['label'] }}</span>
                                    <span style="color: #0a394a;">{{ number_format($day['views']) }} views ({{ number_format($day['unique']) }} uniq)</span>
                                </div>
                                <div style="width: 100%; height: 7px; background: #e2e8f0; border-radius: 999px; overflow: hidden;">
                                    <div style="width: {{ min(100, (int) round(($day['views'] / $maxDayViews) * 100)) }}%; height: 100%; background: linear-gradient(90deg, #0a394a, #0d9488); border-radius: 999px;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Visitor Countries, Regions/Cities & Live Visitor Feed -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.25rem; margin-top: 1.25rem;">
                <!-- Top Countries -->
                <div class="glass-card" style="padding: 1.35rem; border-radius: var(--radius-xl);">
                    <h4 style="font-size: 0.98rem; font-weight: 800; color: #0f172a; margin: 0 0 0.85rem 0;">
                        🌍 Visitors by Country
                    </h4>
                    <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                        @forelse ($traffic['countries'] ?? [] as $c)
                            <div>
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.82rem; font-weight: 700; margin-bottom: 0.2rem;">
                                    <span style="color: #0f172a;">{{ $c['flag'] }} {{ $c['country'] }}</span>
                                    <span style="color: #0a394a;">{{ number_format($c['views']) }} views <span style="color: #64748b; font-weight: 600;">({{ $c['unique_visitors'] }} uniq • {{ $c['percentage'] }}%)</span></span>
                                </div>
                                <div style="width: 100%; height: 6px; background: #e2e8f0; border-radius: 999px; overflow: hidden;">
                                    <div style="width: {{ max(4, $c['percentage']) }}%; height: 100%; background: #0284c7; border-radius: 999px;"></div>
                                </div>
                            </div>
                        @empty
                            <div style="padding: 1rem; text-align: center; color: var(--text-muted); font-size: 0.84rem;">
                                Country data will appear as visitors browse.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Top Regions / Cities -->
                <div class="glass-card" style="padding: 1.35rem; border-radius: var(--radius-xl);">
                    <h4 style="font-size: 0.98rem; font-weight: 800; color: #0f172a; margin: 0 0 0.85rem 0;">
                        📍 Visitors by Region / City
                    </h4>
                    <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                        @forelse ($traffic['regions'] ?? [] as $r)
                            <div>
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.82rem; font-weight: 700; margin-bottom: 0.2rem;">
                                    <span style="color: #0f172a;">{{ $r['flag'] }} {{ $r['region'] }} <span style="color: #64748b; font-weight: 600; font-size: 0.74rem;">({{ $r['country'] }})</span></span>
                                    <span style="color: #059669;">{{ number_format($r['views']) }} views <span style="color: #64748b; font-weight: 600;">({{ $r['unique_visitors'] }} uniq • {{ $r['percentage'] }}%)</span></span>
                                </div>
                                <div style="width: 100%; height: 6px; background: #e2e8f0; border-radius: 999px; overflow: hidden;">
                                    <div style="width: {{ max(4, $r['percentage']) }}%; height: 100%; background: #10b981; border-radius: 999px;"></div>
                                </div>
                            </div>
                        @empty
                            <div style="padding: 1rem; text-align: center; color: var(--text-muted); font-size: 0.84rem;">
                                Region & city data will appear as visitors browse.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Live Visitors Activity Feed -->
                <div class="glass-card" style="padding: 1.35rem; border-radius: var(--radius-xl);">
                    <h4 style="font-size: 0.98rem; font-weight: 800; color: #0f172a; margin: 0 0 0.85rem 0;">
                        🕒 Recent Visitor Stream
                    </h4>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        @forelse (collect($traffic['recent_visits'] ?? [])->take(6) as $rv)
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; padding: 0.5rem 0.7rem; background: #f8fafc; border: 1px solid rgba(226, 232, 240, 0.9); border-radius: 10px; font-size: 0.78rem;">
                                <div style="min-width: 0;">
                                    <div style="font-weight: 800; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $rv['flag'] }} {{ $rv['region'] }}, {{ $rv['country'] }}
                                    </div>
                                    <div style="color: #64748b; font-size: 0.72rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        Page: <strong style="color: #0a394a;">{{ $rv['path'] }}</strong> • {{ ucfirst($rv['device_type']) }} • {{ $rv['referrer_source'] }}
                                    </div>
                                </div>
                                <span style="font-size: 0.7rem; color: #64748b; font-weight: 600; white-space: nowrap; flex-shrink: 0;">{{ $rv['time_ago'] }}</span>
                            </div>
                        @empty
                            <div style="padding: 1rem; text-align: center; color: var(--text-muted); font-size: 0.84rem;">
                                No recent visits logged yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Financial Performance Cards -->
    <div style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.82rem; color: var(--text-muted);">
            Financial Throughput (Selected Window)
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;">
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); border-left: 4px solid #0a394a;">
                <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); margin-bottom: 0.35rem;">Gross Merchandise Value (GMV)</div>
                <div style="font-size: 2rem; font-weight: 800; color: #0f172a;">৳{{ number_format($metrics['gross_revenue'], 0) }}</div>
                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.35rem;">Completed booking volume</div>
            </div>

            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); border-left: 4px solid #10b981;">
                <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); margin-bottom: 0.35rem;">Platform Revenue (15%)</div>
                <div style="font-size: 2rem; font-weight: 800; color: #059669;">৳{{ number_format($metrics['platform_commission'], 0) }}</div>
                <div style="font-size: 0.8rem; color: #059669; font-weight: 600; margin-top: 0.35rem;">CareMate commission earned</div>
            </div>

            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); border-left: 4px solid #8b5cf6;">
                <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); margin-bottom: 0.35rem;">Caregiver Payouts Disbursed</div>
                <div style="font-size: 2rem; font-weight: 800; color: #6d28d9;">৳{{ number_format($metrics['caregiver_payouts'], 0) }}</div>
                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.35rem;">Net earnings settled</div>
            </div>
        </div>
    </div>

    <!-- Marketplace Operational KPIs -->
    <div style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.82rem; color: var(--text-muted);">
            Operational Volume & Demand
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem;">
            <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
                <div style="font-size: 0.78rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted);">Hiring Requests</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-top: 0.25rem;">{{ $metrics['hiring_requests'] }}</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">Incoming demand</div>
            </div>

            <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
                <div style="font-size: 0.78rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted);">Completed Shifts</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: #059669; margin-top: 0.25rem;">{{ $metrics['completed_bookings'] }}</div>
                <div style="font-size: 0.75rem; color: #059669; font-weight: 600;">100% fulfillment</div>
            </div>

            <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
                <div style="font-size: 0.78rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted);">Cancellations</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: #ef4444; margin-top: 0.25rem;">{{ $metrics['cancelled_bookings'] }}</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">Disputed or cancelled</div>
            </div>

            <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
                <div style="font-size: 0.78rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted);">Verified Caregivers</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: #0a394a; margin-top: 0.25rem;">{{ $metrics['verified_caregivers'] }}</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">of {{ $metrics['total_caregivers'] }} total fleet</div>
            </div>

            <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
                <div style="font-size: 0.78rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted);">Client Families</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-top: 0.25rem;">{{ $metrics['total_clients'] }}</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">Registered accounts</div>
            </div>
        </div>
    </div>

    <!-- Dual Breakdown Matrices -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(420px, 1fr)); gap: 2rem;">
        <!-- Service Breakdown -->
        <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl);">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem;">
                Service Demand Matrix
            </h3>

            <div class="table-container">
                <table class="glass-table">
                    <thead>
                        <tr>
                            <th>Service Category</th>
                            <th style="text-align: right;">Bookings in Window</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($serviceBreakdown as $srv)
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: #0f172a;">{{ $srv->name }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $srv->short_description }}</div>
                                </td>
                                <td style="text-align: right; font-weight: 800; color: #0a394a; font-size: 1.1rem;">
                                    {{ $srv->bookings_count }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">No service bookings found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Geographic Caregiver Coverage -->
        <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl);">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem;">
                Caregiver Fleet by Division
            </h3>

            <div class="table-container">
                <table class="glass-table">
                    <thead>
                        <tr>
                            <th>Division</th>
                            <th style="text-align: right;">Verified Caregivers</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($locationBreakdown as $loc)
                            <tr>
                                <td style="font-weight: 700; color: #0f172a;">
                                    📍 {{ $loc->name }} Division
                                </td>
                                <td style="text-align: right; font-weight: 800; color: #059669; font-size: 1.1rem;">
                                    {{ $loc->caregivers_count }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">No locations configured.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
