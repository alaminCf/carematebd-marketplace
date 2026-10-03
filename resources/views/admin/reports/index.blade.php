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
