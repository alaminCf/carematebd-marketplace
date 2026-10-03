<x-layouts.dashboard>
    <x-slot:title>Job #{{ $booking->booking_reference }} — CareMate BD</x-slot:title>
    <x-slot:header>Care Job #{{ $booking->booking_reference }}</x-slot:header>
    <x-slot:subheading>{{ $booking->service->name }} • {{ $booking->start_date->format('M d') }} to {{ $booking->end_date->format('M d, Y') }}</x-slot:subheading>

    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 2rem; align-items: flex-start;" class="job-layout">
        <!-- Main Duty Instructions -->
        <div>
            <!-- Top Status Strip -->
            <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl); margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span style="font-size: 0.78rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted);">Shift Status</span>
                    <div style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-top: 0.25rem;">
                        {{ $booking->status->label() }}
                    </div>
                </div>
                <x-badge :tone="$booking->status->badgeTone()" style="font-size: 0.88rem; padding: 0.4rem 1rem;">
                    {{ $booking->status->label() }}
                </x-badge>
            </div>

            <!-- Patient Details & Care Routine -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                    Patient Medical & Mobility Care Requirements
                </h3>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; margin-bottom: 1.5rem;">
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Patient Name / Age</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $booking->care_recipient_name }} ({{ $booking->care_recipient_age }} yrs, {{ ucfirst($booking->care_recipient_gender) }})</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Family Relationship</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $booking->care_recipient_relationship }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Mobility Status</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $booking->mobility_status }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Shift Hours</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ ucfirst(str_replace('_', ' ', $booking->shift_type)) }} ({{ $booking->daily_hours }} hrs/day)</div>
                    </div>
                </div>

                @if ($booking->health_conditions)
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Health & Medical Conditions</div>
                        <div style="color: var(--text-secondary); font-size: 0.95rem; margin-top: 0.25rem;">
                            {{ $booking->health_conditions }}
                        </div>
                    </div>
                @endif

                @if ($booking->special_instructions)
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Special Instructions & Medicine Routine</div>
                        <div style="color: var(--text-secondary); font-size: 0.95rem; margin-top: 0.25rem; background: rgba(241, 245, 249, 0.7); padding: 0.85rem 1rem; border-radius: var(--radius-md);">
                            {{ $booking->special_instructions }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Destination & Duty Location -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl);">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                    Service Duty Location
                </h3>

                <div style="font-size: 1.05rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">
                    📍 {{ $booking->care_address }}
                </div>
                <div style="font-size: 0.88rem; color: var(--text-muted); line-height: 1.6;">
                    Please report at least 15 minutes before your shift starts in professional attire. Confirm your arrival with your CareMate Care Manager.
                </div>
            </div>
        </div>

        <!-- Sidebar: Compensation & Support -->
        <div>
            <!-- Earnings Card -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); margin-bottom: 1.5rem;">
                <h4 style="font-size: 0.8rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 1rem;">
                    Job Earnings Summary
                </h4>

                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem; color: var(--text-secondary);">
                    <span>Total Service Days:</span>
                    <span>{{ $booking->total_days }} days</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem; color: var(--text-secondary);">
                    <span>Daily Net Rate:</span>
                    <span>৳{{ number_format($booking->caregiver_net_pay / max(1, $booking->total_days)) }}</span>
                </div>
                <div style="border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 0.75rem; margin-top: 0.75rem; display: flex; justify-content: space-between; font-size: 1.35rem; font-weight: 800; color: #059669;">
                    <span>Total Net Pay:</span>
                    <span>৳{{ number_format($booking->caregiver_net_pay) }}</span>
                </div>

                <div style="margin-top: 1rem; font-size: 0.78rem; color: var(--text-muted);">
                    @if ($booking->status->value === 'completed')
                        <span style="color: #059669; font-weight: 700;">✓ Completed: Credited to your withdrawal balance.</span>
                    @else
                        Funds locked in client escrow. Released upon service completion.
                    @endif
                </div>
            </div>

            <!-- Support Assistance Card -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
                <h4 style="font-size: 0.85rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 1rem;">
                    CareDesk Coordinator
                </h4>
                <p style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.55; margin-bottom: 1rem;">
                    Need help with directions, client communication, or medical assistance?
                </p>
                <a href="{{ route('caregiver.support.index') }}" class="btn btn-secondary btn-sm" style="width: 100%;">
                    Open CareDesk Ticket
                </a>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
