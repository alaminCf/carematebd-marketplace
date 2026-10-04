<x-layouts.dashboard>
    <x-slot:title>Family Client Portal — CareMate BD</x-slot:title>
    <x-slot:header>Welcome, {{ auth()->user()->name }}</x-slot:header>
    <x-slot:subheading>Manage your family's care requests, active caregiver shifts, and safety coordination.</x-slot:subheading>

    <!-- Top KPI Stats Grid -->
    <div class="stats-grid">
        <div class="glass-card stat-card">
            <span class="stat-label">Active Care Bookings</span>
            <div class="stat-value" style="color: #0a394a;">{{ $stats['active_bookings'] }}</div>
            <span class="stat-hint">In-progress caregiver shifts</span>
        </div>

        <div class="glass-card stat-card">
            <span class="stat-label">Pending Requests</span>
            <div class="stat-value" style="color: #d97706;">{{ $stats['pending_requests'] }}</div>
            <span class="stat-hint">Under Admin triage</span>
        </div>

        <div class="glass-card stat-card">
            <span class="stat-label">Completed Care Days</span>
            <div class="stat-value" style="color: #059669;">{{ $stats['completed_bookings'] }}</div>
            <span class="stat-hint">Successfully verified care</span>
        </div>

        <div class="glass-card stat-card">
            <span class="stat-label">Saved Caregivers</span>
            <div class="stat-value" style="color: #e11d48;">{{ $stats['saved_caregivers'] }}</div>
            <span class="stat-hint">In your family favorites</span>
        </div>
    </div>

    <!-- Active Bookings & Requests Sections -->
    <div class="dashboard-grid-2">
        <!-- Active Care Bookings -->
        <div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a;">Active Care Bookings</h3>
                <a href="{{ route('client.bookings.index') }}" style="font-size: 0.85rem; font-weight: 700; color: var(--brand-primary);">View All Bookings →</a>
            </div>

            @forelse ($activeBookings as $booking)
                <div class="glass-card glass-card-hover" style="margin-bottom: 1rem; padding: 1.25rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                        <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted);">
                            Ref: #{{ $booking->booking_reference }}
                        </span>
                        <x-badge :tone="$booking->status->badgeTone()">{{ $booking->status->label() }}</x-badge>
                    </div>

                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <img src="{{ $booking->caregiver->avatarUrl() }}" alt="{{ $booking->caregiver->user->name }}" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                        <div>
                            <div style="font-weight: 800; font-size: 1.05rem; color: #0f172a;">{{ $booking->caregiver->user->name }}</div>
                            <div style="font-size: 0.85rem; color: var(--text-secondary);">
                                {{ $booking->service->name }} • {{ $booking->start_date->format('M d') }} to {{ $booking->end_date->format('M d, Y') }}
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 0.75rem;">
                        <div style="font-size: 0.85rem; color: var(--text-secondary);">
                            Total: <strong>৳{{ number_format($booking->client_total) }}</strong> (Escrow Protected)
                        </div>
                        <a href="{{ route('client.bookings.show', $booking->id) }}" class="btn btn-secondary btn-sm">
                            Manage Booking
                        </a>
                    </div>
                </div>
            @empty
                <div class="glass-card" style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🩺</div>
                    <p style="margin-bottom: 1rem;">No active bookings at the moment.</p>
                    <a href="{{ route('marketplace.index') }}" class="btn btn-primary btn-sm">Find Caregiver Now</a>
                </div>
            @endforelse
        </div>

        <!-- Recent Hiring Requests -->
        <div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a;">Recent Care Requests</h3>
                <a href="{{ route('client.requests.index') }}" style="font-size: 0.85rem; font-weight: 700; color: var(--brand-primary);">View All →</a>
            </div>

            @forelse ($recentRequests as $req)
                <div class="glass-card" style="margin-bottom: 1rem; padding: 1.25rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted);">#{{ $req->request_reference }}</span>
                        <x-badge :tone="$req->status->badgeTone()">{{ $req->status->label() }}</x-badge>
                    </div>

                    <div style="font-weight: 700; font-size: 0.95rem; color: #0f172a; margin-bottom: 0.25rem;">
                        {{ $req->service->name }} ({{ $req->care_recipient_relationship }})
                    </div>
                    <div style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 0.75rem;">
                        Requested for: {{ $req->caregiver->user->name ?? 'Any qualified caregiver' }}
                    </div>

                    <div style="display: flex; justify-content: flex-end;">
                        <a href="{{ route('client.requests.show', $req->id) }}" style="font-size: 0.82rem; font-weight: 700; color: var(--brand-primary);">
                            View Status & Notes →
                        </a>
                    </div>
                </div>
            @empty
                <div class="glass-card" style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
                    <p>No recent requests pending.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Recommended Top Caregivers in Your Area -->
    <div>
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
            <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a;">Recommended Caregivers Nearby</h3>
            <a href="{{ route('marketplace.index') }}" style="font-size: 0.85rem; font-weight: 700; color: var(--brand-primary);">Browse All Staff →</a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;" class="caregivers-grid">
            @foreach ($recommendedCaregivers as $cg)
                <div class="glass-card glass-card-hover" style="display: flex; flex-direction: column;">
                    <div style="display: flex; gap: 1rem; align-items: flex-start; margin-bottom: 1rem;">
                        <img src="{{ $cg->avatarUrl() }}" alt="{{ $cg->user->name }}" style="width: 56px; height: 56px; border-radius: 50%; object-fit: cover;">
                        <div style="flex: 1;">
                            <div style="font-weight: 800; font-size: 1.05rem; color: #0f172a;">{{ $cg->user->name }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">📍 {{ $cg->area?->name ?? $cg->city }}</div>
                            <div style="display: flex; align-items: center; gap: 0.35rem; margin-top: 0.25rem;">
                                <x-star-rating :rating="$cg->rating_avg" />
                                <span style="font-size: 0.8rem; font-weight: 700;">{{ number_format($cg->rating_avg, 1) }}</span>
                            </div>
                        </div>
                    </div>

                    <div style="border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 0.75rem; display: flex; align-items: center; justify-content: space-between; margin-top: auto;">
                        <div style="font-weight: 800; font-size: 1.05rem; color: #0f172a;">
                            ৳{{ number_format($cg->daily_rate) }}<span style="font-size: 0.75rem; color: var(--text-muted);">/day</span>
                        </div>
                        <a href="{{ route('marketplace.show', $cg->slug) }}" class="btn btn-primary btn-sm">
                            View Profile
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.dashboard>
