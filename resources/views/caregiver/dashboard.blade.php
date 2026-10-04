<x-layouts.dashboard>
    <x-slot:title>Caregiver Portal — CareMate BD</x-slot:title>
    <x-slot:header>Welcome, {{ auth()->user()->name }}</x-slot:header>
    <x-slot:subheading>Professional Care Provider Dashboard • CareMate Accredited Network</x-slot:subheading>

    <!-- Verification Alert Banner if not verified -->
    @if (! $caregiver->isVerified())
        <div class="glass-card" style="margin-bottom: 2rem; border-left: 4px solid #f59e0b; background: rgba(254, 243, 199, 0.6); padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="font-weight: 800; color: #92400e; font-size: 1.05rem;">
                    Status: {{ $caregiver->status->label() }}
                </div>
                <div style="font-size: 0.88rem; color: #78350f; margin-top: 0.2rem;">
                    Your profile is undergoing admin identity verification. You will be notified once published to the public marketplace.
                </div>
            </div>
            @if ($caregiver->status->value === 'draft' || $caregiver->status->value === 'changes_required')
                <a href="{{ route('caregiver.register') }}" class="btn btn-warning btn-sm">
                    Complete Onboarding Steps
                </a>
            @endif
        </div>
    @endif

    <!-- Top KPI Stats Grid -->
    <div class="stats-grid">
        <div class="glass-card stat-card">
            <span class="stat-label">Pending Invitations</span>
            <div class="stat-value" style="color: #0a394a;">{{ $stats['pending_requests'] }}</div>
            <span class="stat-hint">CareMate Admin dispatched</span>
        </div>

        <div class="glass-card stat-card">
            <span class="stat-label">Active / Upcoming Jobs</span>
            <div class="stat-value" style="color: #059669;">{{ $stats['active_jobs'] }}</div>
            <span class="stat-hint">Confirmed client duties</span>
        </div>

        <div class="glass-card stat-card">
            <span class="stat-label">Total Net Earnings</span>
            <div class="stat-value" style="color: #0d9488;">৳{{ number_format($stats['total_earnings']) }}</div>
            <span class="stat-hint">Withdrawable via bKash/Bank</span>
        </div>

        <div class="glass-card stat-card">
            <span class="stat-label">Family Rating</span>
            <div class="stat-value" style="color: #f59e0b;">★ {{ number_format($stats['average_rating'], 1) }}</div>
            <span class="stat-hint">Based on client reviews</span>
        </div>
    </div>

    <!-- Assigned Requests & Upcoming Jobs Grid -->
    <div class="dashboard-grid-2">
        <!-- Assigned Requests -->
        <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a;">Assigned Care Requests</h3>
                <a href="{{ route('caregiver.requests.index') }}" style="font-size: 0.82rem; font-weight: 700; color: var(--brand-primary);">View All →</a>
            </div>

            @forelse ($assignedRequests as $req)
                <div style="background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(226, 232, 240, 0.9); padding: 1.25rem; border-radius: var(--radius-md); margin-bottom: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <span style="font-weight: 700; color: var(--brand-primary); font-size: 0.85rem;">#{{ $req->request_reference }}</span>
                        <x-badge :tone="$req->status->badgeTone()">{{ $req->status->label() }}</x-badge>
                    </div>

                    <div style="font-weight: 700; font-size: 1rem; color: #0f172a; margin-bottom: 0.25rem;">
                        {{ $req->service->name }} ({{ $req->total_days }} Days)
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.75rem;">
                        Schedule: {{ $req->start_date->format('M d') }} to {{ $req->end_date->format('M d, Y') }} • {{ ucfirst(str_replace('_', ' ', $req->shift_type)) }}
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 0.75rem;">
                        <div style="font-weight: 800; color: #059669; font-size: 0.95rem;">
                            Net Pay: ৳{{ number_format($req->caregiver_net_amount) }}
                        </div>
                        <a href="{{ route('caregiver.requests.show', $req->id) }}" class="btn btn-primary btn-sm" style="font-size: 0.8rem;">
                            Review & Accept
                        </a>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
                    <p>No new care invitations right now. Keep your schedule updated to receive priority requests.</p>
                </div>
            @endforelse
        </div>

        <!-- Upcoming & Active Jobs -->
        <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a;">Active & Upcoming Jobs</h3>
                <a href="{{ route('caregiver.jobs.index') }}" style="font-size: 0.82rem; font-weight: 700; color: var(--brand-primary);">View All →</a>
            </div>

            @forelse ($upcomingJobs as $job)
                <div style="background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(226, 232, 240, 0.9); padding: 1.25rem; border-radius: var(--radius-md); margin-bottom: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <span style="font-weight: 700; color: var(--brand-primary); font-size: 0.85rem;">Booking #{{ $job->booking_reference }}</span>
                        <x-badge :tone="$job->status->badgeTone()">{{ $job->status->label() }}</x-badge>
                    </div>

                    <div style="font-weight: 700; font-size: 1rem; color: #0f172a;">
                        {{ $job->service->name }}
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.75rem;">
                        {{ $job->start_date->format('M d') }} - {{ $job->end_date->format('M d, Y') }} ({{ $job->total_days }} days)
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 0.75rem;">
                        <div style="font-size: 0.82rem; color: var(--text-muted);">
                            📍 {{ $job->client->city }}
                        </div>
                        <a href="{{ route('caregiver.jobs.show', $job->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.8rem;">
                            Job Details & Duty
                        </a>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
                    <p>No active assignments. Accept pending requests to start earning.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.dashboard>
