<x-layouts.dashboard>
    <x-slot:title>Booking #{{ $booking->booking_reference }} — CareMate BD</x-slot:title>
    <x-slot:header>Booking #{{ $booking->booking_reference }}</x-slot:header>
    <x-slot:subheading>{{ $booking->service->name }} • {{ $booking->start_date->format('M d') }} to {{ $booking->end_date->format('M d, Y') }}</x-slot:subheading>

    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 2rem; align-items: flex-start;" class="booking-layout">
        <!-- Main Info -->
        <div>
            <!-- Top Status Strip -->
            <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl); margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span style="font-size: 0.78rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted);">Booking Status</span>
                    <div style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-top: 0.25rem;">
                        {{ $booking->status->label() }}
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <x-badge :tone="$booking->status->badgeTone()" style="font-size: 0.88rem; padding: 0.4rem 1rem;">
                        {{ $booking->status->label() }}
                    </x-badge>
                    @if ($booking->payment_status->value === 'paid')
                        <x-badge tone="success">Escrow Protected</x-badge>
                    @endif
                </div>
            </div>

            <!-- Assigned Caregiver (Privacy Safeguarded) -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                    Assigned Caregiver
                </h3>

                <div style="display: flex; gap: 1.5rem; align-items: flex-start; flex-wrap: wrap;">
                    <img src="{{ $booking->caregiver->avatarUrl() }}" alt="{{ $caregiverProfile['name'] }}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <h4 style="font-size: 1.25rem; font-weight: 800; color: #0f172a;">{{ $caregiverProfile['name'] }}</h4>
                            <x-badge tone="success">Verified Care Staff</x-badge>
                        </div>
                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.2rem;">
                            📍 {{ $caregiverProfile['location']['city'] ?? 'Dhaka' }}, Bangladesh
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.4rem; margin-top: 0.4rem;">
                            <x-star-rating :rating="$caregiverProfile['ratings']['average']" />
                            <span style="font-size: 0.85rem; font-weight: 700;">{{ number_format($caregiverProfile['ratings']['average'], 1) }}</span>
                            <span style="font-size: 0.8rem; color: var(--text-muted);">({{ $caregiverProfile['ratings']['count'] }} reviews)</span>
                        </div>
                    </div>
                </div>

                <!-- Admin mediation privacy reminder -->
                <div style="margin-top: 1.5rem; background: rgba(10, 57, 74, 0.06); border: 1px solid rgba(10, 57, 74, 0.18); border-radius: var(--radius-md); padding: 1rem; font-size: 0.85rem; color: var(--text-secondary); line-height: 1.55;">
                    🛡️ <strong>Safety Protected:</strong> CareMate coordinates all shift arrival notices and instructions. If you need any schedule adjustment or special guidance, contact your CareDesk Coordinator below.
                </div>
            </div>

            <!-- Care Recipient & Location -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                    Patient & Service Address
                </h3>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; margin-bottom: 1rem;">
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Patient Name</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $booking->care_recipient_name }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Relationship</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $booking->care_recipient_relationship }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Shift Schedule</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ ucfirst(str_replace('_', ' ', $booking->shift_type)) }} ({{ $booking->daily_hours }} hrs/day)</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Total Duration</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $booking->total_days }} Days</div>
                    </div>
                </div>

                <div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Service Address</div>
                    <div style="font-weight: 600; color: #0f172a; margin-top: 0.25rem;">
                        📍 {{ $booking->care_address }}
                    </div>
                </div>
            </div>

            <!-- Review Section (If Completed) -->
            @if ($booking->status->value === 'completed')
                <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">
                        Caregiver Review & Rating
                    </h3>

                    @if ($booking->review)
                        <div style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.2); padding: 1.25rem; border-radius: var(--radius-md);">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <x-star-rating :rating="$booking->review->rating" />
                                    <span style="font-weight: 800;">{{ $booking->review->rating }}.0</span>
                                </div>
                                <span style="font-size: 0.8rem; color: var(--text-muted);">Submitted {{ $booking->review->created_at->format('M d, Y') }}</span>
                            </div>
                            <p style="font-size: 0.95rem; color: var(--text-secondary); line-height: 1.6;">
                                "{{ $booking->review->comment }}"
                            </p>
                        </div>
                    @else
                        <form method="POST" action="{{ route('client.bookings.review', $booking->id) }}">
                            @csrf
                            <div style="margin-bottom: 1rem;">
                                <label class="form-label">Rating <span style="color: #ef4444;">*</span></label>
                                <select name="rating" required class="glass-input" style="max-width: 200px;">
                                    <option value="5">★★★★★ (5 - Excellent Care)</option>
                                    <option value="4">★★★★☆ (4 - Very Good)</option>
                                    <option value="3">★★★☆☆ (3 - Satisfactory)</option>
                                    <option value="2">★★☆☆☆ (2 - Needs Improvement)</option>
                                    <option value="1">★☆☆☆☆ (1 - Disappointed)</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 1.25rem;">
                                <label class="form-label">Your Feedback / Review <span style="color: #ef4444;">*</span></label>
                                <textarea name="comment" rows="3" required placeholder="How was the caregiver's punctuality, compassion, and professional care?" class="glass-input"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                Submit Review
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar Actions & Financial Summary -->
        <div>
            <!-- Financial Card -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); margin-bottom: 1.5rem;">
                <h4 style="font-size: 0.85rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 1rem;">
                    Financial Summary
                </h4>

                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem; color: var(--text-secondary);">
                    <span>Service Duration:</span>
                    <span>{{ $booking->total_days }} days</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem; color: var(--text-secondary);">
                    <span>Daily Rate:</span>
                    <span>৳{{ number_format($booking->caregiver_rate_daily) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem; color: var(--text-secondary);">
                    <span>Payment Status:</span>
                    <strong style="color: #059669;">{{ ucfirst($booking->payment_status->value) }}</strong>
                </div>

                <div style="border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 0.75rem; margin-top: 0.75rem; display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: 800; color: #0f172a;">
                    <span>Total Paid:</span>
                    <span>৳{{ number_format($booking->client_total) }}</span>
                </div>
            </div>

            <!-- Support / Dispute Actions -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
                <h4 style="font-size: 0.85rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 1rem;">
                    Assistance & Dispute
                </h4>

                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <a href="{{ route('client.support.index') }}" class="btn btn-secondary btn-sm" style="width: 100%;">
                        💬 Contact CareDesk Support
                    </a>

                    @if (in_array($booking->status->value, ['confirmed', 'in_progress', 'completed']))
                        <a href="{{ route('client.disputes.create', $booking->id) }}" class="btn btn-danger btn-sm" style="width: 100%;">
                            ⚠️ File a Service Dispute
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
