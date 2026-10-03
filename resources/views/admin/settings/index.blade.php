<x-layouts.dashboard>
    <x-slot:title>Platform Settings — Admin | CareMate BD</x-slot:title>
    <x-slot:header>Platform Configuration & Economics</x-slot:header>
    <x-slot:subheading>Manage commission structure, payout parameters, public contact lines, and administrative operations desk.</x-slot:subheading>

    <div style="max-width: 860px;">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf

            <!-- Identity & Operations -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-bottom: 0.35rem;">
                    Platform Identity & Physical Headquarters
                </h3>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                    Official registered name and address displayed on invoices and official client care contracts.
                </p>

                <div style="display: grid; grid-template-columns: 1fr; gap: 1.25rem;">
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">Platform / Business Name *</label>
                        <input type="text" name="platform_name" required value="{{ old('platform_name', $settings['platform_name']->value ?? 'CareMate BD') }}" class="glass-input" style="width: 100%;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">Operations Center Office Address *</label>
                        <input type="text" name="office_address" required value="{{ old('office_address', $settings['office_address']->value ?? 'E-14/X, ICT Tower (14th Floor), Agargaon, Dhaka-1207, Bangladesh') }}" class="glass-input" style="width: 100%;">
                    </div>
                </div>
            </div>

            <!-- Financial & Revenue Architecture -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-bottom: 0.35rem;">
                    Marketplace Financial Architecture
                </h3>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                    CareMate BD fee deductions and caregiver payout withdrawal thresholds.
                </p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">CareMate Commission Rate (%) *</label>
                        <input type="number" step="0.1" name="platform_commission_rate" required value="{{ old('platform_commission_rate', $settings['platform_commission_rate']->value ?? 15.0) }}" class="glass-input" style="width: 100%;">
                        <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">Default standard: 15% platform mediation fee.</span>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">Minimum Caregiver Payout (৳ BDT) *</label>
                        <input type="number" name="min_payout_amount" required value="{{ old('min_payout_amount', $settings['min_payout_amount']->value ?? 1000) }}" class="glass-input" style="width: 100%;">
                        <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">Minimum threshold to request withdrawal (bKash/Nagad/Bank).</span>
                    </div>
                </div>
            </div>

            <!-- Support Lines & Emergency Desk -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-bottom: 0.35rem;">
                    Emergency Hotline & Communications Desk
                </h3>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                    24/7 Rapid Response hotline and official support dispatch channels.
                </p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">24/7 Emergency Hotline *</label>
                        <input type="text" name="emergency_hotline" required value="{{ old('emergency_hotline', $settings['emergency_hotline']->value ?? '+880 1610-296460') }}" class="glass-input" style="width: 100%;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">Support Phone Desk *</label>
                        <input type="text" name="contact_phone" required value="{{ old('contact_phone', $settings['contact_phone']->value ?? '+880 1610-296460') }}" class="glass-input" style="width: 100%;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">Official Support Email *</label>
                        <input type="email" name="contact_email" required value="{{ old('contact_email', $settings['contact_email']->value ?? 'contact@carematebd.com') }}" class="glass-input" style="width: 100%;">
                    </div>
                </div>
            </div>

            <!-- Privacy & Mediation Policy Notice -->
            <div style="background: rgba(10, 57, 74, 0.05); border: 1px solid rgba(10, 57, 74, 0.2); border-radius: var(--radius-xl); padding: 1.5rem; margin-bottom: 2rem;">
                <div style="display: flex; gap: 0.75rem;">
                    <div style="color: #0a394a; flex-shrink: 0;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div>
                        <h4 style="font-size: 0.95rem; font-weight: 800; color: #0a394a; margin: 0 0 0.25rem 0;">Zero Direct Contact Leakage Policy</h4>
                        <p style="font-size: 0.85rem; color: #062531; line-height: 1.5; margin: 0;">
                            CareMate BD enforces strict privacy shielding. Clients and Caregivers cannot communicate directly off-platform or inspect each other's phone/email prior to administrator verification and contractual dispatch.
                        </p>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 1rem;">
                    Save Platform Settings
                </button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>
