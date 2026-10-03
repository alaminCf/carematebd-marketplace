<x-layouts.dashboard>
    <x-slot:title>Booking #{{ $booking->booking_reference }} — Admin | CareMate BD</x-slot:title>
    <x-slot:header>Booking #{{ $booking->booking_reference }} Supervision</x-slot:header>
    <x-slot:subheading>{{ $booking->service->name }} • Client: {{ $booking->client->user->name }} • Caregiver: {{ $booking->caregiver->user->name }}</x-slot:subheading>

    <div style="display: grid; grid-template-columns: 1fr 360px; gap: 2rem; align-items: flex-start;" class="booking-admin-layout">
        <!-- Main Details -->
        <div>
            <!-- Top Status Strip -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span style="font-size: 0.78rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted);">Shift Lifecycle Status</span>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-top: 0.25rem;">
                        {{ $booking->status->label() }}
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <x-badge :tone="$booking->status->badgeTone()" style="font-size: 0.9rem; padding: 0.45rem 1.1rem;">
                        {{ $booking->status->label() }}
                    </x-badge>
                    <x-badge :tone="$booking->payment_status->value === 'paid' ? 'success' : 'warning'">
                        Payment: {{ ucfirst($booking->payment_status->value) }}
                    </x-badge>
                </div>
            </div>

            <!-- Patient and Duty Details -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                    Patient & Service Requirements
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
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Schedule Duration</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $booking->start_date->format('M d, Y') }} — {{ $booking->end_date->format('M d, Y') }} ({{ $booking->total_days }} days)</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Shift Arrangement</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ ucfirst(str_replace('_', ' ', $booking->shift_type)) }} ({{ $booking->daily_hours }} hrs/day)</div>
                    </div>
                </div>

                @if ($booking->health_conditions)
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Medical Conditions</div>
                        <div style="color: var(--text-secondary); font-size: 0.95rem; margin-top: 0.25rem;">
                            {{ $booking->health_conditions }}
                        </div>
                    </div>
                @endif

                <div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Duty Address</div>
                    <div style="font-weight: 600; color: #0f172a; margin-top: 0.25rem;">
                        📍 {{ $booking->care_address }}
                    </div>
                </div>
            </div>

            <!-- Lifecycle Status Logs -->
            @if ($booking->statusLogs->isNotEmpty())
                <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl);">
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">
                        Status Transition Audit Trail
                    </h3>

                    @foreach ($booking->statusLogs as $log)
                        <div style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid rgba(226, 232, 240, 0.8); font-size: 0.85rem;">
                            <div>
                                <span style="font-weight: 700; color: #0f172a;">{{ strtoupper($log->from_status ?? 'initial') }} → {{ strtoupper($log->to_status) }}</span>
                                @if ($log->notes)
                                    <span style="display: block; color: var(--text-secondary); margin-top: 0.15rem;">"{{ $log->notes }}"</span>
                                @endif
                            </div>
                            <span style="color: var(--text-muted);">{{ $log->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Sidebar: Financials & Admin Actions -->
        <div>
            <!-- Financial Breakdown -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); margin-bottom: 1.5rem;">
                <h4 style="font-size: 0.8rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 1rem;">
                    Contract Financials
                </h4>

                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem; color: var(--text-secondary);">
                    <span>Client Total Paid:</span>
                    <strong style="color: #0f172a;">৳{{ number_format($booking->client_total) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem; color: var(--text-secondary);">
                    <span>Platform Commission:</span>
                    <strong style="color: #0a394a;">৳{{ number_format($booking->platform_commission) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem; color: var(--text-secondary);">
                    <span>Caregiver Net Pay:</span>
                    <strong style="color: #059669;">৳{{ number_format($booking->caregiver_net_pay) }}</strong>
                </div>
                <div style="border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 0.5rem; margin-top: 0.5rem; font-size: 0.8rem; color: var(--text-muted);">
                    Commission Rate: {{ $booking->commission_rate_percent }}%
                </div>
            </div>

            <!-- Admin Lifecycle Actions -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
                <h4 style="font-size: 0.85rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 1.25rem;">
                    Admin Operations
                </h4>

                @if ($booking->status->value === 'confirmed')
                    <form method="POST" action="{{ route('admin.bookings.start', $booking->id) }}" style="margin-bottom: 1rem;">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            ▶ Mark Shift Started (In Progress)
                        </button>
                    </form>
                @endif

                @if ($booking->status->value === 'in_progress')
                    <form method="POST" action="{{ route('admin.bookings.complete', $booking->id) }}" style="margin-bottom: 1rem;">
                        @csrf
                        <button type="submit" class="btn btn-mint btn-lg" style="width: 100%;" onclick="return confirm('Complete service and release ৳{{ number_format($booking->caregiver_net_pay) }} to caregiver balance?')">
                            ✓ Verify Completion & Release Pay
                        </button>
                    </form>
                @endif

                @if (!in_array($booking->status->value, ['completed', 'cancelled']))
                    <div style="border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 1rem; margin-top: 1rem;">
                        <form method="POST" action="{{ route('admin.bookings.cancel', $booking->id) }}">
                            @csrf
                            <input type="text" name="reason" required placeholder="Reason for cancellation..." class="glass-input" style="font-size: 0.82rem; margin-bottom: 0.5rem;">
                            <button type="submit" class="btn btn-danger btn-sm" style="width: 100%;" onclick="return confirm('Cancel this booking?')">
                                ✕ Cancel Booking
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.dashboard>
