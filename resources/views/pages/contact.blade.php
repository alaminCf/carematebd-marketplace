<x-layouts.guest>
    <x-slot:title>Contact Care Coordination Desk — CareMate BD</x-slot:title>
    <x-slot:description>Contact CareMate BD at ICT Tower, Agargaon, Dhaka. 24/7 care hotline, emergency caregiver dispatch, and customer support.</x-slot:description>

    <div class="container" style="padding: 3rem 1.25rem 5rem 1.25rem;">
        <div style="text-align: center; max-width: 680px; margin: 0 auto 3.5rem auto;">
            <div style="display: inline-block; font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--brand-primary); margin-bottom: 0.5rem;">
                We Are Here 24/7
            </div>
            <h1 style="font-size: 2.8rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">
                Care Coordination Desk
            </h1>
            <p style="color: var(--text-secondary); font-size: 1.1rem; line-height: 1.6;">
                Have an urgent care emergency, need help matching a specialized nurse, or have questions? Send us a message or call our direct hotline.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 3rem;" class="contact-grid">
            <!-- Left Info Panel -->
            <div>
                <div class="glass-card" style="padding: 2.25rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                    <div style="font-size: 0.8rem; font-weight: 700; color: #15798e; text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.04em;">
                        A Concern of Techboloy
                    </div>
                    <h3 style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem;">
                        Operations Center
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <div style="display: flex; gap: 1rem; align-items: flex-start;">
                            <div style="width: 42px; height: 42px; border-radius: 12px; background: rgba(10, 57, 74, 0.12); display: flex; align-items: center; justify-content: center; color: #0a394a; flex-shrink: 0;">
                                📍
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">Dhaka Headquarters</div>
                                <div style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.5; margin-top: 0.2rem;">
                                    E-14/X, ICT Tower (14th Floor), Agargaon, Dhaka-1207, Bangladesh
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: 1rem; align-items: flex-start;">
                            <div style="width: 42px; height: 42px; border-radius: 12px; background: rgba(21, 121, 142, 0.15); display: flex; align-items: center; justify-content: center; color: #15798e; flex-shrink: 0;">
                                📞
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">Emergency Care Hotline</div>
                                <div style="font-size: 1.1rem; font-weight: 800; color: #0a394a; margin-top: 0.2rem;">
                                    <a href="tel:+8801610296460" style="color: inherit; text-decoration: none;">+880 1610-296460</a>
                                </div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">24/7 direct caregiver dispatch across Dhaka</div>
                            </div>
                        </div>

                        <div style="display: flex; gap: 1rem; align-items: flex-start;">
                            <div style="width: 42px; height: 42px; border-radius: 12px; background: rgba(245, 158, 11, 0.12); display: flex; align-items: center; justify-content: center; color: #d97706; flex-shrink: 0;">
                                ✉️
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">Official Inquiries</div>
                                <div style="font-size: 0.9rem; color: var(--text-secondary); margin-top: 0.2rem;">
                                    <a href="mailto:contact@carematebd.com" style="color: inherit; text-decoration: none;">contact@carematebd.com</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Banner -->
                <div style="background: rgba(244, 63, 94, 0.08); border: 1px solid rgba(244, 63, 94, 0.2); border-radius: var(--radius-lg); padding: 1.5rem; color: #9f1239; font-size: 0.88rem; line-height: 1.6;">
                    <strong>🚨 Medical Emergency Protocol:</strong>
                    CareMate caregivers deliver non-emergency nursing, assisted living, and convalescence. For acute trauma, stroke, or heart attacks, please call National Emergency <strong>999</strong> immediately.
                </div>
            </div>

            <!-- Right Contact Form -->
            <div class="glass-card" style="padding: 2.5rem; border-radius: var(--radius-xl);">
                <h3 style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">
                    Send a Message to Care Desk
                </h3>
                <p style="font-size: 0.92rem; color: var(--text-secondary); margin-bottom: 2rem;">
                    Our senior care coordinator responds within 30 minutes during active hours.
                </p>

                <form method="POST" action="{{ route('contact.submit') }}">
                    @csrf

                    <div style="margin-bottom: 1.25rem;">
                        <label class="form-label">Your Full Name <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Asif Chowdhury" class="glass-input">
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                        <div>
                            <label class="form-label">Email Address <span style="color: #ef4444;">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="asif@example.com" class="glass-input">
                            @error('email')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Contact Phone</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+880 1712-345678" class="glass-input">
                            @error('phone')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label class="form-label">Subject / Service Needed <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required placeholder="e.g. Urgent post-surgery nurse needed in Dhanmondi" class="glass-input">
                        @error('subject')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label class="form-label">Detailed Message <span style="color: #ef4444;">*</span></label>
                        <textarea name="message" rows="5" required placeholder="Describe patient age, mobility status, daily hours needed, and area in Bangladesh..." class="glass-input" style="resize: vertical;">{{ old('message') }}</textarea>
                        @error('message')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                        Submit Inquiry to Care Coordinator
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.guest>
