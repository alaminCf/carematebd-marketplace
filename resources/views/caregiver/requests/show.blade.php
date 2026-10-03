<x-layouts.dashboard>
    <x-slot:title>Care Request #{{ $request->request_reference }} — CareMate BD</x-slot:title>
    <x-slot:header>Care Request #{{ $request->request_reference }}</x-slot:header>
    <x-slot:subheading>Review patient requirements and respond to this job invitation.</x-slot:subheading>

    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 2rem; align-items: flex-start;" class="request-layout">
        <!-- Main Job Brief -->
        <div>
            <!-- Status Card -->
            <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl); margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span style="font-size: 0.78rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted);">Invitation Status</span>
                    <div style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-top: 0.25rem;">
                        {{ $request->status->label() }}
                    </div>
                </div>
                <x-badge :tone="$request->status->badgeTone()" style="font-size: 0.88rem; padding: 0.4rem 1rem;">
                    {{ $request->status->label() }}
                </x-badge>
            </div>

            <!-- Patient Profile Brief -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                    Patient Medical & Physical Care Brief
                </h3>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; margin-bottom: 1.5rem;">
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Patient Name / Age</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $request->care_recipient_name }} ({{ $request->care_recipient_age }} yrs, {{ ucfirst($request->care_recipient_gender) }})</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Relationship to Family</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $request->care_recipient_relationship }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Mobility Level</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $request->mobility_status }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Care Area</div>
                        <div style="font-weight: 700; color: #0f172a;">📍 {{ $request->area?->name ?? 'Dhaka Area' }}, {{ $request->district?->name }}</div>
                    </div>
                </div>

                @if ($request->health_conditions)
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Reported Health Conditions</div>
                        <div style="color: var(--text-secondary); font-size: 0.95rem; margin-top: 0.25rem;">
                            {{ $request->health_conditions }}
                        </div>
                    </div>
                @endif

                @if ($request->special_instructions)
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Special Instructions & Routine</div>
                        <div style="color: var(--text-secondary); font-size: 0.95rem; margin-top: 0.25rem; background: rgba(241, 245, 249, 0.7); padding: 0.75rem 1rem; border-radius: var(--radius-md);">
                            {{ $request->special_instructions }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Shift & Schedule Specifications -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl);">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                    Schedule & Shift Specifications
                </h3>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Service</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $request->service->name }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Shift Arrangement</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ ucfirst(str_replace('_', ' ', $request->shift_type)) }} ({{ $request->daily_hours }} hrs/day)</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Duty Duration</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $request->start_date->format('M d, Y') }} — {{ $request->end_date->format('M d, Y') }} ({{ $request->total_days }} days)</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Total Service Days</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $request->total_days }} Days</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar: Pay and Response Actions -->
        <div>
            <!-- Financial Payout Card -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); margin-bottom: 1.5rem;">
                <h4 style="font-size: 0.8rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 1rem;">
                    Your Net Earnings For This Job
                </h4>

                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem; color: var(--text-secondary);">
                    <span>Service Days:</span>
                    <span>{{ $request->total_days }} Days</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem; color: var(--text-secondary);">
                    <span>Daily Payout:</span>
                    <span>৳{{ number_format($request->caregiver_net_amount / max(1, $request->total_days)) }}/day</span>
                </div>
                <div style="border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 0.75rem; margin-top: 0.75rem; display: flex; justify-content: space-between; font-size: 1.35rem; font-weight: 800; color: #059669;">
                    <span>Total Net Pay:</span>
                    <span>৳{{ number_format($request->caregiver_net_amount) }}</span>
                </div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.35rem;">
                    Platform fee already deducted. You receive 100% of this net amount via bKash/Bank.
                </div>
            </div>

            <!-- Action Buttons if Pending Caregiver Response -->
            @if (in_array($request->status->value, ['pending', 'admin_approved']))
                <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">
                        Respond to Job Invitation
                    </h4>

                    <!-- Accept Form -->
                    <form method="POST" action="{{ route('caregiver.requests.accept', $request->id) }}" style="margin-bottom: 1rem;">
                        @csrf
                        <button type="submit" class="btn btn-mint btn-lg" style="width: 100%;">
                            ✓ Accept This Care Assignment
                        </button>
                    </form>

                    <!-- Decline Form -->
                    <form method="POST" action="{{ route('caregiver.requests.decline', $request->id) }}">
                        @csrf
                        <div style="margin-bottom: 0.75rem;">
                            <input type="text" name="decline_reason" required placeholder="Reason (e.g. conflicting schedule)..." class="glass-input" style="font-size: 0.82rem; padding: 0.45rem 0.75rem;">
                        </div>
                        <button type="submit" class="btn btn-secondary btn-sm" style="width: 100%; color: #e11d48 !important;">
                            ✕ Decline Assignment
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-layouts.dashboard>
