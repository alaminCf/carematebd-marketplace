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

                    <!-- WhatsApp Quick Inquire CTA -->
                    <div style="margin-top: 0.85rem;">
                        <a href="https://wa.me/8801610296460?text={{ urlencode('Hello CareMate BD, I would like to inquire about caregiver: ' . $caregiver->user->name . ' (' . $caregiver->slug . ')') }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="btn" 
                           style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem; background: rgba(37, 211, 102, 0.12); color: #0d873d; border: 1.5px solid rgba(37, 211, 102, 0.4); font-weight: 700; font-size: 0.92rem; border-radius: var(--radius-md); padding: 0.75rem 1rem;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.031 2C6.516 2 2.031 6.484 2.031 12C2.031 13.805 2.508 15.5 3.336 16.969L2 22L7.172 20.688C8.594 21.461 10.258 21.906 12.031 21.906C17.547 21.906 22.031 17.422 22.031 12C22.031 6.484 17.547 2 12.031 2ZM12.031 20.156C10.453 20.156 8.969 19.719 7.688 18.969L7.375 18.781L4.312 19.562L5.125 16.578L4.922 16.25C4.109 14.953 3.672 13.5 3.672 12C3.672 7.391 7.422 3.641 12.031 3.641C16.641 3.641 20.391 7.391 20.391 12C20.391 16.609 16.641 20.156 12.031 20.156ZM16.609 14.547C16.359 14.422 15.125 13.812 14.891 13.734C14.656 13.656 14.484 13.609 14.312 13.859C14.141 14.109 13.656 14.688 13.5 14.859C13.344 15.031 13.188 15.047 12.938 14.922C12.688 14.797 11.875 14.531 10.922 13.68C10.172 13.008 9.672 12.18 9.516 11.93C9.359 11.68 9.5 11.539 9.625 11.414C9.734 11.305 9.875 11.125 10 10.984C10.125 10.844 10.172 10.734 10.25 10.578C10.328 10.422 10.281 10.281 10.219 10.156C10.156 10.031 9.656 8.812 9.453 8.312C9.25 7.828 9.047 7.891 8.891 7.891C8.75 7.891 8.578 7.875 8.406 7.875C8.234 7.875 7.953 7.938 7.719 8.188C7.484 8.438 6.828 9.047 6.828 10.281C6.828 11.516 7.734 12.703 7.859 12.875C7.984 13.047 9.641 15.609 12.188 16.703C12.797 16.969 13.266 17.125 13.641 17.25C14.25 17.438 14.812 17.406 15.25 17.344C15.75 17.266 16.781 16.719 17 16.109C17.219 15.5 17.219 14.984 17.156 14.859C17.094 14.734 16.859 14.672 16.609 14.547Z"/></svg>
                            <span>Inquire via WhatsApp</span>
                        </a>
                    </div>

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
