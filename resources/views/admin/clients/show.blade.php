<x-layouts.dashboard>
    <x-slot:title>{{ $client->user->name }} — Client Profile | Admin CareMate BD</x-slot:title>
    <x-slot:header>{{ $client->user->name }}</x-slot:header>
    <x-slot:subheading>Client Member #CL-{{ str_pad($client->id, 5, '0', STR_PAD_LEFT) }} • Joined {{ $client->created_at->format('M d, Y') }}</x-slot:subheading>

    <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
        <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 0.4rem;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            Back to Clients List
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 2rem; align-items: flex-start;" class="admin-detail-layout">
        <!-- Main Content -->
        <div>
            <!-- Overview Card -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 1.25rem;">
                        <img src="{{ $client->user->avatarUrl() }}" alt="" style="width: 72px; height: 72px; border-radius: 50%; object-fit: cover; border: 3px solid rgba(10, 57, 74, 0.2);">
                        <div>
                            <h2 style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin: 0;">{{ $client->user->name }}</h2>
                            <div style="font-size: 0.9rem; color: var(--text-muted); margin-top: 0.2rem;">{{ $client->user->email }} • <span style="font-family: monospace; font-weight: 600;">{{ $client->user->phone }}</span></div>
                            <div style="margin-top: 0.5rem; display: flex; gap: 0.5rem; align-items: center;">
                                <x-badge :tone="$client->user->isSuspended() ? 'danger' : 'success'">
                                    {{ $client->user->isSuspended() ? 'Account Suspended' : 'Verified Active Client' }}
                                </x-badge>
                                <span style="font-size: 0.8rem; color: var(--text-muted);">Joined {{ $client->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($client->user->isSuspended())
                    <div style="background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 1.5rem;">
                        <div style="font-weight: 700; color: #dc2626; display: flex; align-items: center; gap: 0.5rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            Account Suspended on {{ $client->user->suspended_at?->format('M d, Y h:i A') }}
                        </div>
                        <div style="margin-top: 0.4rem; font-size: 0.9rem; color: #7f1d1d;">
                            <strong>Reason:</strong> {{ $client->user->suspension_reason ?? 'Administrative policy violation' }}
                        </div>
                    </div>
                @endif

                <!-- Contact & Address Grid -->
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                    <div>
                        <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); margin-bottom: 0.25rem;">Care Location</div>
                        <div style="font-size: 1rem; font-weight: 700; color: #0f172a;">{{ $client->address ?? 'Address not specified' }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.2rem;">
                            {{ $client->area?->name ?? $client->city }}, {{ $client->district?->name }}, {{ $client->division?->name }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); margin-bottom: 0.25rem;">Emergency Contact</div>
                        @if ($client->emergency_contact_name)
                            <div style="font-size: 1rem; font-weight: 700; color: #0f172a;">{{ $client->emergency_contact_name }}</div>
                            <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.2rem;">
                                Relationship: <strong>{{ $client->emergency_contact_relationship ?? 'Family' }}</strong> • Phone: <span style="font-family: monospace;">{{ $client->emergency_contact_phone }}</span>
                            </div>
                        @else
                            <div style="font-size: 0.9rem; color: var(--text-muted);">No emergency contact provided yet.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Hiring Requests History -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
                    <span>Hiring Requests History ({{ $client->hiringRequests->count() }})</span>
                </h3>

                <div class="table-container">
                    <table class="glass-table">
                        <thead>
                            <tr>
                                <th>Ref #</th>
                                <th>Service</th>
                                <th>Patient</th>
                                <th>Schedule</th>
                                <th>Status</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($client->hiringRequests as $req)
                                <tr>
                                    <td style="font-family: monospace; font-weight: 700;">#{{ $req->request_reference }}</td>
                                    <td>{{ $req->service->name }}</td>
                                    <td>
                                        {{ $req->care_recipient_name }}
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $req->care_recipient_age }} yrs • {{ ucfirst($req->care_recipient_relationship) }}</div>
                                    </td>
                                    <td style="font-size: 0.82rem;">
                                        {{ $req->start_date->format('M d') }} - {{ $req->end_date->format('M d, Y') }}
                                        <span style="color: var(--text-muted);">({{ $req->total_days }}d)</span>
                                    </td>
                                    <td>
                                        <x-badge :tone="$req->status->badgeTone()">
                                            {{ $req->status->label() }}
                                        </x-badge>
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="{{ route('admin.hiring-requests.show', $req->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.78rem;">
                                            Triage →
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 2rem;">No hiring requests placed.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Bookings Supervision -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem;">
                    Confirmed Care Bookings ({{ $client->bookings->count() }})
                </h3>

                <div class="table-container">
                    <table class="glass-table">
                        <thead>
                            <tr>
                                <th>Booking Ref</th>
                                <th>Service</th>
                                <th>Caregiver</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($client->bookings as $b)
                                <tr>
                                    <td style="font-family: monospace; font-weight: 700;">#{{ $b->booking_reference }}</td>
                                    <td>{{ $b->service->name }}</td>
                                    <td>{{ $b->caregiver->user->name }}</td>
                                    <td style="font-weight: 700; color: #0f172a;">৳{{ number_format($b->total_amount, 0) }}</td>
                                    <td>
                                        <x-badge :tone="$b->status->badgeTone()">
                                            {{ $b->status->label() }}
                                        </x-badge>
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="{{ route('admin.bookings.show', $b->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.78rem;">
                                            Inspect →
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 2rem;">No confirmed bookings on record.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Reviews Placed -->
            @if ($client->reviews->isNotEmpty())
                <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl);">
                    <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem;">
                        Caregiver Feedback & Reviews ({{ $client->reviews->count() }})
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        @foreach ($client->reviews as $rev)
                            <div style="padding: 1rem; border-radius: var(--radius-lg); background: rgba(255, 255, 255, 0.6); border: 1px solid rgba(226, 232, 240, 0.8);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                    <x-star-rating :rating="$rev->rating" />
                                    <span style="font-size: 0.78rem; color: var(--text-muted);">{{ $rev->created_at->format('M d, Y') }}</span>
                                </div>
                                <div style="font-size: 0.92rem; color: #1e293b; line-height: 1.5;">
                                    "{{ $rev->comment }}"
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar / Actions -->
        <div>
            <!-- Account Moderation Card -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); margin-bottom: 1.5rem;">
                <h4 style="font-size: 0.8rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 1rem;">
                    Account Security & Moderation
                </h4>

                @if ($client->user->isSuspended())
                    <p style="font-size: 0.88rem; color: var(--text-secondary); margin-bottom: 1.25rem; line-height: 1.5;">
                        This client account is currently suspended from placing new requests and accessing caregiver services.
                    </p>
                    <form method="POST" action="{{ route('admin.clients.reactivate', $client->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;" onclick="return confirm('Reactivate this client account?')">
                            Reactivate Account
                        </button>
                    </form>
                @else
                    <p style="font-size: 0.88rem; color: var(--text-secondary); margin-bottom: 1.25rem; line-height: 1.5;">
                        Suspension immediately revokes the client's ability to request caregivers or initiate bookings.
                    </p>
                    <form method="POST" action="{{ route('admin.clients.suspend', $client->id) }}">
                        @csrf
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #0f172a; margin-bottom: 0.4rem;">Suspension Reason</label>
                            <textarea name="reason" rows="3" class="glass-textarea" placeholder="Provide reason for policy enforcement..." required style="width: 100%; font-size: 0.85rem;"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger" style="width: 100%; justify-content: center;" onclick="return confirm('Are you sure you want to suspend this client?')">
                            Suspend Client Account
                        </button>
                    </form>
                @endif
            </div>

            <!-- Platform Stats -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
                <h4 style="font-size: 0.8rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 1rem;">
                    Lifetime Activity Summary
                </h4>

                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.75rem; color: var(--text-secondary);">
                    <span>Total Requests Placed:</span>
                    <strong style="color: #0f172a;">{{ $client->hiringRequests->count() }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.75rem; color: var(--text-secondary);">
                    <span>Confirmed Bookings:</span>
                    <strong style="color: #0f172a;">{{ $client->bookings->count() }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.75rem; color: var(--text-secondary);">
                    <span>Completed Shifts:</span>
                    <strong style="color: #059669;">{{ $client->bookings->where('status.value', 'completed')->count() }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; color: var(--text-secondary);">
                    <span>Reviews Submitted:</span>
                    <strong style="color: #0f172a;">{{ $client->reviews->count() }}</strong>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
