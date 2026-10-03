<x-layouts.dashboard>
    <x-slot:title>File Dispute — Booking #{{ $booking->booking_reference }} | CareMate BD</x-slot:title>
    <x-slot:header>File a Service Dispute</x-slot:header>
    <x-slot:subheading>CareMate Support Mediation Chamber for Booking #{{ $booking->booking_reference }}</x-slot:subheading>

    <div style="max-width: 680px; margin: 0 auto;">
        <div class="glass-card" style="padding: 2.25rem; border-radius: var(--radius-xl);">
            <div style="background: rgba(244, 63, 94, 0.08); border: 1px solid rgba(244, 63, 94, 0.25); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 2rem;">
                <div style="font-weight: 700; color: #9f1239; margin-bottom: 0.25rem;">⚠️ CareDesk Priority Resolution Protocol</div>
                <div style="font-size: 0.88rem; color: #881337; line-height: 1.55;">
                    Submitting a dispute immediately freezes further payout releases from escrow to the caregiver. A senior care supervisor will review this matter within 2 business hours.
                </div>
            </div>

            <form method="POST" action="{{ route('client.disputes.store', $booking->id) }}" enctype="multipart/form-data">
                @csrf

                <div style="margin-bottom: 1.25rem;">
                    <label class="form-label">Booking Summary</label>
                    <div style="background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(226, 232, 240, 0.8); padding: 0.85rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem;">
                        <strong>{{ $booking->service->name }}</strong> with caregiver <strong>{{ $booking->caregiver->user->name }}</strong> (Ref: #{{ $booking->booking_reference }})
                    </div>
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label class="form-label">Dispute Reason / Category <span style="color: #ef4444;">*</span></label>
                    <select name="category" required class="glass-input">
                        <option value="no_show">Caregiver No-Show / Absent Without Notice</option>
                        <option value="misconduct">Unprofessional Conduct or Attitude</option>
                        <option value="poor_service">Inadequate Medical / Physical Care</option>
                        <option value="billing">Rate or Shift Discrepancy</option>
                        <option value="damage">Property Damage or Safety Issue</option>
                        <option value="emergency">Patient Medical Emergency Protocol Ignored</option>
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label class="form-label">Detailed Description of Incident <span style="color: #ef4444;">*</span></label>
                    <textarea name="reason" rows="5" required placeholder="Please describe dates, times, and exactly what occurred..." class="glass-input"></textarea>
                </div>

                <div style="margin-bottom: 2rem;">
                    <label class="form-label">Supporting Evidence (Photos, WhatsApp screenshot, medical slip)</label>
                    <input type="file" name="evidence_file" class="glass-input">
                </div>

                <div style="display: flex; gap: 1rem;">
                    <a href="{{ route('client.bookings.show', $booking->id) }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-danger" style="flex: 1;">
                        Submit Formal Dispute to CareDesk
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
