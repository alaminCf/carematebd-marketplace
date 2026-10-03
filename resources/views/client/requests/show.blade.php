<x-layouts.dashboard>
    <x-slot:title>Hiring Request #{{ $request->request_reference }} — CareMate BD</x-slot:title>
    <x-slot:header>Request #{{ $request->request_reference }}</x-slot:header>
    <x-slot:subheading>Submitted on {{ $request->created_at->format('M d, Y h:i A') }} • Status: {{ $request->status->label() }}</x-slot:subheading>

    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 2rem; align-items: flex-start;" class="request-grid">
        <!-- Main Details -->
        <div>
            <!-- Status Card -->
            <div class="glass-card" style="padding: 1.75rem; margin-bottom: 2rem; border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Current Coordination Stage</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-top: 0.25rem;">
                        {{ $request->status->label() }}
                    </div>
                </div>
                <x-badge :tone="$request->status->badgeTone()" style="font-size: 0.9rem; padding: 0.45rem 1.1rem;">
                    {{ $request->status->label() }}
                </x-badge>
            </div>

            <!-- Patient and Care Plan Card -->
            <div class="glass-card" style="padding: 2rem; margin-bottom: 2rem; border-radius: var(--radius-xl);">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                    Care Recipient (Patient) Profile
                </h3>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; margin-bottom: 1.5rem;">
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Patient Name</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $request->care_recipient_name }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Age & Gender</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $request->care_recipient_age }} Years • {{ ucfirst($request->care_recipient_gender) }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Relationship</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $request->care_recipient_relationship }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Mobility Level</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $request->mobility_status }}</div>
                    </div>
                </div>

                @if ($request->health_conditions)
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Medical Conditions</div>
                        <div style="color: var(--text-secondary); font-size: 0.95rem; margin-top: 0.25rem;">
                            {{ $request->health_conditions }}
                        </div>
                    </div>
                @endif

                @if ($request->special_instructions)
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Special Instructions</div>
                        <div style="color: var(--text-secondary); font-size: 0.95rem; margin-top: 0.25rem; background: rgba(241, 245, 249, 0.7); padding: 0.75rem 1rem; border-radius: var(--radius-md);">
                            {{ $request->special_instructions }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Service Schedule & Address -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl);">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                    Service Schedule & Location
                </h3>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; margin-bottom: 1rem;">
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Care Service</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $request->service->name }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Shift Arrangement</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ ucfirst(str_replace('_', ' ', $request->shift_type)) }} ({{ $request->daily_hours }} hrs/day)</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Service Period</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $request->start_date->format('M d, Y') }} — {{ $request->end_date->format('M d, Y') }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Total Duration</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $request->total_days }} Days</div>
                    </div>
                </div>

                <div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Service Address</div>
                    <div style="font-weight: 600; color: #0f172a; margin-top: 0.25rem;">
                        📍 {{ $request->care_address }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Summary -->
        <div>
            <!-- Caregiver Info (Strictly filtered, no contact info) -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); margin-bottom: 1.5rem;">
                <div style="font-size: 0.78rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 0.75rem;">
                    Assigned Caregiver
                </div>

                @if ($request->caregiver)
                    <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem;">
                        <img src="{{ $request->caregiver->avatarUrl() }}" alt="" style="width: 52px; height: 52px; border-radius: 50%; object-fit: cover;">
                        <div>
                            <div style="font-weight: 800; font-size: 1.05rem; color: #0f172a;">{{ $request->caregiver->user->name }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">📍 {{ $request->caregiver->area?->name ?? $request->caregiver->city }}</div>
                        </div>
                    </div>

                    <a href="{{ route('marketplace.show', $request->caregiver->slug) }}" class="btn btn-secondary btn-sm" style="width: 100%;">
                        View Full Public Profile
                    </a>
                @else
                    <div style="text-align: center; padding: 1rem; color: var(--text-muted);">
                        Admin will match the most suitable verified nurse.
                    </div>
                @endif
            </div>

            <!-- Financial Breakdown -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
                <div style="font-size: 0.78rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 1rem;">
                    Quotation Breakdown
                </div>

                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem; color: var(--text-secondary);">
                    <span>Service Rate:</span>
                    <span>৳{{ number_format($request->client_quoted_amount) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem; color: var(--text-secondary);">
                    <span>Admin Coordination:</span>
                    <span style="color: #059669; font-weight: 700;">Included (Free)</span>
                </div>
                <div style="border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 0.75rem; margin-top: 0.75rem; display: flex; justify-content: space-between; font-size: 1.15rem; font-weight: 800; color: #0f172a;">
                    <span>Estimated Total:</span>
                    <span>৳{{ number_format($request->client_quoted_amount) }}</span>
                </div>

                @if ($request->booking)
                    <div style="margin-top: 1.5rem;">
                        <a href="{{ route('client.bookings.show', $request->booking->id) }}" class="btn btn-mint btn-sm" style="width: 100%;">
                            Go to Confirmed Booking →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.dashboard>
