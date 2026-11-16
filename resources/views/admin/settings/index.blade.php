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

            <!-- Google Reviews & Live Reputation Integration -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin: 0 0 0.35rem 0;">
                            Google Reviews & Live Reputation Integration
                        </h3>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">
                            Live Google Maps reviews showcase, customer review links, and automated synchronization.
                        </p>
                    </div>

                    <a href="{{ $settings['google_review_url']->value ?? 'https://www.google.com/search?q=caremate+bd' }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm" style="font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                        <span>View Live on Google Maps ↗</span>
                    </a>
                </div>

                <div style="display: grid; grid-template-columns: 1fr; gap: 1.25rem; margin-top: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">Public Google Search / Reviews Link *</label>
                        <input type="text" name="google_review_url" value="{{ old('google_review_url', $settings['google_review_url']->value ?? 'https://www.google.com/search?q=caremate+bd#lrd=0x857e344b95efdf37:0x8355a16d154f0a19,1,,,,') }}" class="glass-input" style="width: 100%;">
                        <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">Used for the "View on Google Maps" button.</span>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">Direct "Write a Review" Modal Link</label>
                        <input type="text" name="google_write_review_url" value="{{ old('google_write_review_url', $settings['google_write_review_url']->value ?? 'https://www.google.com/search?q=caremate+bd#lrd=0x857e344b95efdf37:0x8355a16d154f0a19,3,,,,') }}" class="glass-input" style="width: 100%;">
                        <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">Opens the 5-star review submission popup directly on Google.</span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">Place ID / CID</label>
                            <input type="text" name="google_place_id" value="{{ old('google_place_id', $settings['google_place_id']->value ?? '0x857e344b95efdf37:0x8355a16d154f0a19') }}" class="glass-input" style="width: 100%;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">Display Rating (Stars)</label>
                            <input type="number" step="0.1" min="1" max="5" name="google_rating_avg" value="{{ old('google_rating_avg', $settings['google_rating_avg']->value ?? '5.0') }}" class="glass-input" style="width: 100%;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">Review Count</label>
                            <input type="number" min="0" name="google_reviews_count" value="{{ old('google_reviews_count', $settings['google_reviews_count']->value ?? '5') }}" class="glass-input" style="width: 100%;">
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">Google Maps Platform API Key (Optional for Places API auto-sync)</label>
                        <input type="text" name="google_maps_api_key" placeholder="AIzaSy..." value="{{ old('google_maps_api_key', $settings['google_maps_api_key']->value ?? '') }}" class="glass-input" style="width: 100%;">
                        <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">If provided, allows automated periodic background sync via Google Places API.</span>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">Third-Party Live Widget Code (Optional, e.g. Elfsight / Trustmary embed snippet)</label>
                        <textarea name="google_reviews_embed_code" rows="3" placeholder="&lt;script src='https://static.elfsight.com/...'&gt;&lt;/script&gt;&lt;div class='elfsight-app-...'&gt;&lt;/div&gt;" class="glass-input" style="width: 100%; font-family: monospace; font-size: 0.82rem;">{{ old('google_reviews_embed_code', $settings['google_reviews_embed_code']->value ?? '') }}</textarea>
                        <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">Optional: If pasted, the home page will render this live 24/7 widget instead of database cards.</span>
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

            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <button type="button" onclick="document.getElementById('syncGoogleReviewsForm').submit()" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                    <span>Sync with Google Places Now</span>
                </button>

                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 1rem;">
                    Save Platform Settings
                </button>
            </div>
        </form>

        <form id="syncGoogleReviewsForm" method="POST" action="{{ route('admin.settings.sync-google-reviews') }}" style="display: none;">
            @csrf
        </form>

        <!-- Manage Displayed Google Reviews -->
        <div class="glass-card" style="padding: 2rem; margin-top: 2.5rem; border-radius: var(--radius-xl);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem 0; display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                        <span>Active Google Reviews Shown on Homepage ({{ $googleReviews->count() }})</span>
                    </h3>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">
                        Directly manage and curate the real Google reviews shown in the "Trusted by Bangladeshi Families" section.
                    </p>
                </div>
            </div>

            <!-- List of current reviews -->
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                @forelse ($googleReviews as $gRev)
                    <div style="border: 1px solid rgba(226, 232, 240, 0.9); border-radius: var(--radius-lg); padding: 1.25rem; background: #ffffff;">
                        <form action="{{ route('admin.settings.google-reviews.update', $gRev) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 1rem; align-items: start; margin-bottom: 0.85rem;">
                                <div>
                                    <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 0.25rem;">Author Name</label>
                                    <input type="text" name="author_name" value="{{ $gRev->author_name }}" class="glass-input" style="width: 100%; font-weight: 700;" required>
                                </div>
                                <div>
                                    <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 0.25rem;">Rating (Stars)</label>
                                    <select name="rating" class="glass-input" style="width: 100%;">
                                        @for ($s = 5; $s >= 1; $s--)
                                            <option value="{{ $s }}" {{ $gRev->rating == $s ? 'selected' : '' }}>{{ $s }} Stars ★</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 0.25rem;">Relative Time</label>
                                    <input type="text" name="relative_time" value="{{ $gRev->relative_time }}" placeholder="e.g. 2 months ago" class="glass-input" style="width: 100%;">
                                </div>
                                <div style="display: flex; gap: 0.5rem; align-items: flex-end; padding-top: 1.35rem;">
                                    <button type="submit" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.82rem; font-weight: 700;">
                                        Update
                                    </button>
                                </div>
                            </div>

                            <div style="margin-bottom: 0.85rem;">
                                <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 0.25rem;">Review Comment</label>
                                <textarea name="comment" rows="2" class="glass-input" style="width: 100%; font-size: 0.88rem;" required>{{ $gRev->comment }}</textarea>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 0.75rem;">
                                <label style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; color: #475569; cursor: pointer;">
                                    <input type="checkbox" name="is_published" value="1" {{ $gRev->is_published ? 'checked' : '' }}>
                                    <span>Show on Homepage</span>
                                </label>
                                <span style="font-size: 0.75rem; color: var(--text-muted);">
                                    Review ID: {{ $gRev->google_review_id }}
                                </span>
                            </div>
                        </form>

                        <div style="display: flex; justify-content: flex-end; margin-top: -1.75rem;">
                            <form action="{{ route('admin.settings.google-reviews.destroy', $gRev) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this review?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; font-size: 0.78rem; font-weight: 700; cursor: pointer; text-decoration: underline;">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                        No Google reviews added yet.
                    </div>
                @endforelse
            </div>

            <!-- Add new Google Review form -->
            <div style="margin-top: 2rem; padding: 1.5rem; background: #f8fafc; border-radius: var(--radius-lg); border: 1px dashed rgba(10, 57, 74, 0.25);">
                <h4 style="font-size: 0.95rem; font-weight: 800; color: #0a394a; margin: 0 0 1rem 0;">
                    + Add New Review from Google Maps
                </h4>
                <form action="{{ route('admin.settings.google-reviews.store') }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 0.25rem;">Author Name *</label>
                            <input type="text" name="author_name" placeholder="e.g. Torik Hasan" class="glass-input" style="width: 100%;" required>
                        </div>
                        <div>
                            <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 0.25rem;">Rating *</label>
                            <select name="rating" class="glass-input" style="width: 100%;">
                                <option value="5">5 Stars ★★★★★</option>
                                <option value="4">4 Stars ★★★★☆</option>
                                <option value="3">3 Stars ★★★☆☆</option>
                            </select>
                        </div>
                        <div>
                            <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 0.25rem;">Relative Time</label>
                            <input type="text" name="relative_time" placeholder="e.g. 2 months ago" class="glass-input" style="width: 100%;">
                        </div>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 0.25rem;">Comment Text *</label>
                        <textarea name="comment" rows="2" placeholder="Paste the customer review from Google Maps..." class="glass-input" style="width: 100%;" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="font-size: 0.85rem; font-weight: 700;">
                        Save & Show on Homepage
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
