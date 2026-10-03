<x-layouts.guest>
    <x-slot:title>{{ $profile['name'] }} — Verified Caregiver | CareMate BD</x-slot:title>
    <x-slot:description>{{ Str::limit($profile['about'] ?? 'Professional verified caregiver on CareMate BD', 150) }}</x-slot:description>

    <div class="container" style="padding: 2.5rem 1.25rem 5rem 1.25rem;">
        <!-- Breadcrumb -->
        <div style="display: flex; gap: 0.5rem; margin-bottom: 2rem; font-size: 0.85rem; color: var(--text-muted);">
            <a href="{{ route('home') }}" style="color: var(--text-muted);">Home</a>
            <span>/</span>
            <a href="{{ route('marketplace.index') }}" style="color: var(--text-muted);">Caregivers</a>
            <span>/</span>
            <span style="color: var(--brand-primary); font-weight: 700;">{{ $profile['name'] }}</span>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 360px; gap: 2.5rem; align-items: flex-start;" class="profile-layout">
            <!-- Left Main Column -->
            <div>
                <!-- Top Profile Card -->
                <div class="glass-card" style="padding: 2.5rem; margin-bottom: 2rem; border-radius: var(--radius-xl);">
                    <div style="display: flex; gap: 2rem; align-items: flex-start; flex-wrap: wrap;">
                        <div style="position: relative;">
                            <img src="{{ $profile['avatar_url'] }}" alt="{{ $profile['name'] }}" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #fff; box-shadow: 0 8px 24px rgba(0,0,0,0.12);">
                            <div style="position: absolute; bottom: 4px; right: 4px; width: 32px; height: 32px; border-radius: 50%; background: #10b981; border: 3px solid #fff; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 14px; font-weight: 800;" title="CareMate Verified">
                                ✓
                            </div>
                        </div>

                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 0.5rem;">
                                <h1 style="font-size: 2.2rem; font-weight: 800; color: #0f172a; line-height: 1.1;">
                                    {{ $profile['name'] }}
                                </h1>
                                <x-badge tone="success">Verified Caregiver</x-badge>
                            </div>

                            <div style="font-size: 0.95rem; color: var(--text-secondary); margin-bottom: 0.75rem;">
                                📍 Serving: <strong>{{ $profile['location']['area'] ?? $profile['location']['city'] }}, {{ $profile['location']['district'] }}</strong>
                            </div>

                            <div style="display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
                                <div style="display: flex; align-items: center; gap: 0.4rem;">
                                    <x-star-rating :rating="$profile['ratings']['average']" />
                                    <span style="font-weight: 800; font-size: 1rem; color: #0f172a;">{{ number_format($profile['ratings']['average'], 1) }}</span>
                                    <span style="font-size: 0.85rem; color: var(--text-muted);">({{ $profile['ratings']['count'] }} reviews)</span>
                                </div>
                                <div style="color: var(--text-muted);">•</div>
                                <div style="font-size: 0.9rem; color: var(--text-secondary);">
                                    <strong>{{ $profile['years_experience'] }} Years</strong> Experience
                                </div>
                                <div style="color: var(--text-muted);">•</div>
                                <div style="font-size: 0.9rem; color: var(--text-secondary);">
                                    <strong>{{ $profile['completed_jobs_count'] }}</strong> Completed Bookings
                                </div>
                            </div>

                            <!-- Trust Checks -->
                            <div style="display: flex; gap: 1rem; flex-wrap: wrap; font-size: 0.82rem; color: #065f46;">
                                <div style="background: rgba(16, 185, 129, 0.12); padding: 0.3rem 0.75rem; border-radius: var(--radius-pill); font-weight: 700;">
                                    ✓ NID Checked
                                </div>
                                <div style="background: rgba(16, 185, 129, 0.12); padding: 0.3rem 0.75rem; border-radius: var(--radius-pill); font-weight: 700;">
                                    ✓ Police Verification Cleared
                                </div>
                                <div style="background: rgba(16, 185, 129, 0.12); padding: 0.3rem 0.75rem; border-radius: var(--radius-pill); font-weight: 700;">
                                    ✓ Health Screened
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- About & Bio -->
                <div class="glass-card" style="padding: 2rem; margin-bottom: 2rem; border-radius: var(--radius-lg);">
                    <h3 style="font-size: 1.3rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem;">
                        About {{ $profile['name'] }}
                    </h3>
                    <p style="font-size: 1rem; color: var(--text-secondary); line-height: 1.7; white-space: pre-line;">
                        {{ $profile['about'] ?? 'Professional caregiver dedicated to delivering respectful, attentive and compassionate care for elderly family members and patients in Bangladesh.' }}
                    </p>
                </div>

                <!-- Services & Capabilities -->
                <div class="glass-card" style="padding: 2rem; margin-bottom: 2rem; border-radius: var(--radius-lg);">
                    <h3 style="font-size: 1.3rem; font-weight: 700; color: #0f172a; margin-bottom: 1.25rem;">
                        Care Specialties & Services
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                        @foreach ($profile['services'] as $srv)
                            <div style="background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(226, 232, 240, 0.9); padding: 1rem; border-radius: var(--radius-md); display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(10, 57, 74, 0.1); display: flex; align-items: center; justify-content: center; color: #0a394a;">
                                    🩺
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">{{ $srv['name'] }}</div>
                                    <div style="font-size: 0.78rem; color: var(--text-muted);">CareMate Verified Protocol</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Skills & Badges -->
                    @if (!empty($profile['skills']))
                        <div style="margin-top: 1.5rem;">
                            <h4 style="font-size: 0.9rem; font-weight: 700; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.75rem;">Key Skills & Techniques</h4>
                            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                @foreach ($profile['skills'] as $skill)
                                    <span style="font-size: 0.85rem; background: rgba(10, 57, 74, 0.08); color: #0a394a; padding: 0.35rem 0.85rem; border-radius: var(--radius-pill); font-weight: 600;">
                                        {{ $skill }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Verified Past Reviews -->
                <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-lg);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                        <h3 style="font-size: 1.3rem; font-weight: 700; color: #0f172a;">
                            Client Reviews & Ratings ({{ count($reviews) }})
                        </h3>
                        <div style="display: flex; align-items: center; gap: 0.4rem;">
                            <x-star-rating :rating="$profile['ratings']['average']" />
                            <span style="font-weight: 800; font-size: 1.1rem; color: #0f172a;">{{ number_format($profile['ratings']['average'], 1) }}</span>
                        </div>
                    </div>

                    @forelse ($reviews as $review)
                        <div style="border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding: 1.25rem 0;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                                <div>
                                    <div style="font-weight: 700; font-size: 0.95rem; color: #0f172a;">
                                        {{ $review->client->user->name ?? 'CareMate Family' }}
                                    </div>
                                    <div style="font-size: 0.78rem; color: var(--text-muted);">
                                        {{ $review->created_at->format('M d, Y') }} • Verified Booking
                                    </div>
                                </div>
                                <x-star-rating :rating="$review->rating" />
                            </div>
                            <p style="font-size: 0.92rem; color: var(--text-secondary); line-height: 1.6;">
                                "{{ $review->comment }}"
                            </p>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            This caregiver has newly graduated our verification academy. Be among the first families to experience their dedicated care!
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Hiring & Rates Card (Sticky) -->
            <div style="position: sticky; top: 90px;">
                <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); box-shadow: var(--glass-shadow-lg);">
                    <div style="margin-bottom: 1.5rem; text-align: center; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 1.5rem;">
                        <span style="font-size: 0.78rem; text-transform: uppercase; font-weight: 800; letter-spacing: 0.08em; color: var(--brand-primary);">
                            Care Rate
                        </span>
                        <div style="font-size: 2.4rem; font-weight: 800; color: #0f172a; margin-top: 0.25rem; line-height: 1;">
                            ৳{{ number_format($profile['rates']['daily'] ?? $caregiver->daily_rate) }}
                            <span style="font-size: 0.95rem; font-weight: 600; color: var(--text-muted);">/day</span>
                        </div>
                        @if ($profile['rates']['monthly'])
                            <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.5rem;">
                                Monthly Arrangement: <strong>৳{{ number_format($profile['rates']['monthly']) }}/mo</strong>
                            </div>
                        @endif
                    </div>

                    <!-- Safety Notice -->
                    <div style="background: rgba(10, 57, 74, 0.06); border: 1px solid rgba(10, 57, 74, 0.18); border-radius: var(--radius-md); padding: 1rem; margin-bottom: 1.75rem; font-size: 0.85rem; color: var(--text-secondary); line-height: 1.55;">
                        <strong style="color: #0a394a; display: block; margin-bottom: 0.25rem;">🛡️ Admin-Mediated Care</strong>
                        For your protection and privacy, direct contact details are never shared. CareMate Care Coordinators oversee shift scheduling, emergency standby, and escrow payouts.
                    </div>

                    <!-- CTA Actions -->
                    @auth
                        @if (auth()->user()->isClient())
                            <a href="{{ route('client.requests.create', $caregiver->id) }}" class="btn btn-primary btn-lg" style="width: 100%; margin-bottom: 0.75rem; font-size: 1.05rem;">
                                <span>Request Care Booking</span>
                                <span>→</span>
                            </a>
                            <form action="{{ route('client.favorites.toggle', $caregiver->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-secondary" style="width: 100%; font-size: 0.9rem;">
                                    <span>♥ Save Caregiver to Favorites</span>
                                </button>
                            </form>
                        @elseif (auth()->user()->isAdmin())
                            <a href="{{ route('admin.caregivers.index') }}" class="btn btn-secondary" style="width: 100%;">
                                Back to Admin Caregivers
                            </a>
                        @else
                            <div style="font-size: 0.85rem; color: var(--text-muted); text-align: center;">
                                Logged in as Caregiver.
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg" style="width: 100%; margin-bottom: 0.75rem; font-size: 1.05rem;">
                            Login to Book Care
                        </a>
                        <a href="{{ route('register.client') }}" class="btn btn-secondary" style="width: 100%; font-size: 0.9rem;">
                            Create Client Account
                        </a>
                    @endauth

                    <!-- Guarantees list -->
                    <div style="margin-top: 1.75rem; padding-top: 1.5rem; border-top: 1px solid rgba(226, 232, 240, 0.8); display: flex; flex-direction: column; gap: 0.6rem; font-size: 0.82rem; color: var(--text-secondary);">
                        <div style="display: flex; align-items: center; gap: 0.4rem;">
                            <span style="color: #059669; font-weight: 700;">✓</span> 100% Free Replacement if unsatisfied
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.4rem;">
                            <span style="color: #059669; font-weight: 700;">✓</span> Secure bKash / Nagad / Bank Escrow
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.4rem;">
                            <span style="color: #059669; font-weight: 700;">✓</span> 24/7 Dedicated Care Manager
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>
