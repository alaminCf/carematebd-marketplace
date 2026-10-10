<x-layouts.guest>
    <x-slot:title>CareMate BD — Trusted Caregivers for Elderly, Child, & Nursing Care in Bangladesh</x-slot:title>
    <x-slot:description>Find background-verified caregivers in Dhaka and across Bangladesh. Admin-coordinated care for your loved ones with total safety, privacy, and peace of mind.</x-slot:description>

    @push('schema')
    @if (isset($faqs) && $faqs->isNotEmpty())
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faqs->map(function ($faq) {
            return [
                '@type' => 'Question',
                'name' => $faq->question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags($faq->answer),
                ],
            ];
        })->values()->all(),
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @endif
    @endpush

    @push('styles')
    <style>
        .hero-section {
            padding-top: 0.75rem !important;
        }
        @media (max-width: 900px) {
            .hero-section {
                padding-top: 0.25rem !important;
            }
        }
        @media (max-width: 768px) {
            .hero-section {
                padding-top: 0.15rem !important;
            }
            .hero-find-care-col,
            .pathao-find-card {
                margin-top: 0 !important;
            }
        }
        @media (max-width: 480px) {
            .hero-section {
                padding-top: 0.05rem !important;
            }
        }
    </style>
    @endpush

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-grid">
                <!-- Left Column on Desktop / Reordered via display:contents on Mobile -->
                <div class="hero-left-col">
                    <!-- 1. Text Content (Headline, Subtitle) - Desktop Top / Mobile Order 3 -->
                    <div class="hero-text-content">
                        <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(10, 57, 74, 0.08); border: 1px solid rgba(10, 57, 74, 0.2); padding: 0.4rem 0.9rem; border-radius: var(--radius-pill); font-size: 0.82rem; font-weight: 700; color: #0a394a; margin-bottom: 1.25rem;">
                            <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #10b981; box-shadow: 0 0 8px #10b981;"></span>
                            {{ __("Bangladesh's #1 Verified Caregiver Marketplace") }}
                        </div>

                        <h1 style="font-size: clamp(2.2rem, 3.5vw, 3.25rem); font-weight: 800; letter-spacing: -0.03em; line-height: 1.15; margin-bottom: 1.25rem; color: #092632;">
                            {{ __('Care that feels like family, found in minutes.') }}
                        </h1>

                        <p style="font-size: 1.15rem; color: var(--text-secondary); line-height: 1.65; margin-bottom: 2rem;">
                            {{ __('Compassionate, government NID & background-checked caregivers for your parents, children, and patients. Safe, verified, and coordinated end-to-end by CareMate Care Managers.') }}
                        </p>
                    </div>

                    <!-- 2. Pathao-Style Find Care Card - Desktop Below Headline / Mobile Order 1 at very top! -->
                    <div class="hero-find-care-col">
                        <div class="pathao-find-card">
                            <form action="{{ route('marketplace.index') }}" method="GET" id="heroFindCareForm">
                                <input type="hidden" name="service" id="heroSelectedService" value="">
                                
                                <!-- Location Row (Auto-detected / Click to Change) -->
                                <div class="pathao-location-row" id="pathaoLocationTrigger" onclick="toggleLocationPicker()">
                                    <div class="pathao-loc-left">
                                        <div class="pathao-loc-pin" id="pathaoPinIcon" title="{{ __('Current Detected Location') }}">
                                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        </div>
                                        <div class="pathao-loc-details">
                                            <div class="pathao-loc-sub">
                                                <span class="pathao-loc-label">{{ __('Care Location') }}</span>
                                                <span id="pathaoLocStatus" class="pathao-status-tag">
                                                    <span class="pathao-status-dot"></span>
                                                    <span class="pathao-status-text">{{ __('Detecting...') }}</span>
                                                </span>
                                            </div>
                                            <div class="pathao-loc-name" id="pathaoLocationName">{{ __('Dhaka, Bangladesh') }}</div>
                                            <input type="hidden" name="search" id="heroLocationInput" value="Dhaka">
                                        </div>
                                    </div>
                                    <div class="pathao-loc-actions" onclick="event.stopPropagation()">
                                        <button type="button" class="pathao-gps-btn" onclick="detectCurrentLocation(true)" title="{{ __('Use GPS Location') }}" id="pathaoGpsBtn">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                                        </button>
                                        <button type="button" class="pathao-change-btn" onclick="toggleLocationPicker()">
                                            {{ __('Change') }}
                                        </button>
                                    </div>
                                </div>

                                <!-- Popular Care Zones Quick Chips -->
                                <div class="pathao-quick-zones">
                                    <span class="pathao-zone-label">{{ __('Popular:') }}</span>
                                    @foreach($popularAreas as $area)
                                        <button type="button" class="pathao-zone-chip" onclick="selectAreaZone('{{ $area }}', this)">
                                            {{ $area }}
                                        </button>
                                    @endforeach
                                </div>

                                <!-- Service Grid Select Chips (5 Chips in one neat row) -->
                                <div class="pathao-services-grid">
                                    @php
                                        $serviceIcons = [
                                            'elderly-care' => '👴',
                                            'child-care' => '👶',
                                            'nursing-care' => '🩺',
                                            'medical-transportation' => '🚑',
                                        ];
                                    @endphp
                                    <div class="pathao-service-chip active" data-slug="" onclick="selectHeroServiceChip('', this)">
                                        <span class="chip-icon">✨</span>
                                        <span class="chip-text">{{ __('All Services') }}</span>
                                    </div>
                                    @foreach ($services as $service)
                                        <div class="pathao-service-chip" data-slug="{{ $service->slug }}" onclick="selectHeroServiceChip('{{ $service->slug }}', this)">
                                            <span class="chip-icon">{{ $serviceIcons[$service->slug] ?? '🤝' }}</span>
                                            <span class="chip-text">
                                                @if($service->slug === 'medical-transportation')
                                                    <span class="d-none d-sm-inline">{{ __($service->name) }}</span>
                                                    <span class="d-inline d-sm-none">{{ __('Transport') }}</span>
                                                @else
                                                    {{ __($service->name) }}
                                                @endif
                                            </span>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Submit Button -->
                                <div>
                                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.8rem 1.4rem; font-size: 0.98rem; font-weight: 700; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; gap: 0.65rem; box-shadow: 0 4px 14px rgba(10, 57, 74, 0.25);">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                        <span id="findCareBtnText">{{ __('Find Caregiver Near You') }}</span>
                                    </button>
                                </div>

                                <!-- Dropdown Location Picker Modal -->
                                <div class="location-picker-modal" id="locationPickerModal">
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.85rem;">
                                        <div style="font-weight: 800; font-size: 0.95rem; color: #0f172a; display: flex; align-items: center; gap: 0.4rem;">
                                            <span>📍</span>
                                            <span>{{ __('Select Your Care Location') }}</span>
                                        </div>
                                        <button type="button" onclick="toggleLocationPicker(false)" style="background: rgba(10, 57, 74, 0.06); border: none; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; line-height: 1; cursor: pointer; color: var(--text-muted); transition: background 0.15s ease;" onmouseover="this.style.background='rgba(10, 57, 74, 0.15)'" onmouseout="this.style.background='rgba(10, 57, 74, 0.06)'">&times;</button>
                                    </div>
                                    <div style="position: relative; margin-bottom: 0.85rem;">
                                        <input type="text" id="manualLocationInput" placeholder="{{ __('Type area e.g. Banani, Uttara, Mirpur...') }}" class="glass-input" style="width: 100%; padding: 0.65rem 0.85rem; font-size: 0.9rem;" onkeyup="handleManualLocationKey(event)">
                                        <button type="button" class="btn btn-primary btn-sm" style="position: absolute; right: 5px; top: 5px; bottom: 5px; padding: 0 0.85rem; border-radius: 6px;" onclick="applyManualLocation()">
                                            {{ __('Set') }}
                                        </button>
                                    </div>
                                    <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.04em;">
                                        {{ __('Popular Care Locations') }}
                                    </div>
                                    <div style="display: flex; flex-wrap: wrap; gap: 0.4rem; max-height: 160px; overflow-y: auto; padding-right: 2px;">
                                        @foreach($popularAreas as $area)
                                            <button type="button" class="pathao-zone-chip" onclick="applyQuickLocation('{{ $area }}')">
                                                📍 {{ $area }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- 3. Trust Points & WhatsApp Link - Desktop Bottom / Mobile Order 4 -->
                    <div class="hero-trust-content">
                        <!-- Trust Points -->
                        <div style="display: flex; align-items: center; gap: 1.5rem; font-size: 0.88rem; color: var(--text-secondary); flex-wrap: wrap; margin-bottom: 1.25rem;">
                            <div style="display: flex; align-items: center; gap: 0.4rem;">
                                <span style="color: #059669; font-weight: 800;">✓</span> {{ __('NID & Police Verified') }}
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.4rem;">
                                <span style="color: #059669; font-weight: 800;">✓</span> {{ __('Zero Phone Leaks (Privacy)') }}
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.4rem;">
                                <span style="color: #059669; font-weight: 800;">✓</span> {{ __('bKash / Nagad Escrow') }}
                            </div>
                        </div>

                        <!-- Quick WhatsApp Direct Connect CTA -->
                        <div style="display: inline-flex; align-items: center; gap: 0.85rem; background: rgba(37, 211, 102, 0.12); border: 1px solid rgba(37, 211, 102, 0.35); padding: 0.6rem 1.15rem; border-radius: var(--radius-pill); max-width: 100%;">
                            <span style="display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: #25D366; color: #fff; flex-shrink: 0;">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M12.031 2C6.516 2 2.031 6.484 2.031 12C2.031 13.805 2.508 15.5 3.336 16.969L2 22L7.172 20.688C8.594 21.461 10.258 21.906 12.031 21.906C17.547 21.906 22.031 17.422 22.031 12C22.031 6.484 17.547 2 12.031 2ZM12.031 20.156C10.453 20.156 8.969 19.719 7.688 18.969L7.375 18.781L4.312 19.562L5.125 16.578L4.922 16.25C4.109 14.953 3.672 13.5 3.672 12C3.672 7.391 7.422 3.641 12.031 3.641C16.641 3.641 20.391 7.391 20.391 12C20.391 16.609 16.641 20.156 12.031 20.156ZM16.609 14.547C16.359 14.422 15.125 13.812 14.891 13.734C14.656 13.656 14.484 13.609 14.312 13.859C14.141 14.109 13.656 14.688 13.5 14.859C13.344 15.031 13.188 15.047 12.938 14.922C12.688 14.797 11.875 14.531 10.922 13.68C10.172 13.008 9.672 12.18 9.516 11.93C9.359 11.68 9.5 11.539 9.625 11.414C9.734 11.305 9.875 11.125 10 10.984C10.125 10.844 10.172 10.734 10.25 10.578C10.328 10.422 10.281 10.281 10.219 10.156C10.156 10.031 9.656 8.812 9.453 8.312C9.25 7.828 9.047 7.891 8.891 7.891C8.75 7.891 8.578 7.875 8.406 7.875C8.234 7.875 7.953 7.938 7.719 8.188C7.484 8.438 6.828 9.047 6.828 10.281C6.828 11.516 7.734 12.703 7.859 12.875C7.984 13.047 9.641 15.609 12.188 16.703C12.797 16.969 13.266 17.125 13.641 17.25C14.25 17.438 14.812 17.406 15.25 17.344C15.75 17.266 16.781 16.719 17 16.109C17.219 15.5 17.219 14.984 17.156 14.859C17.094 14.734 16.859 14.672 16.609 14.547Z"/></svg>
                            </span>
                            <div style="font-size: 0.88rem; color: #064e3b;">
                                {{ __('Prefer to talk directly?') }} <a href="https://wa.me/8801610296460?text=Hello%20CareMate%20BD%2C%20I%20need%20quick%20caregiver%20assistance." target="_blank" rel="noopener noreferrer" style="color: #075E54; font-weight: 800; text-decoration: underline; margin-left: 0.25rem;">{{ __('Chat on WhatsApp with Care Coordinator →') }}</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Interactive Service Slider Showcase (Identical Frame to Original) -->
                <div class="hero-slider-container" style="position: relative;">
                    <div class="hero-slider-wrapper" id="heroServiceSlider">
                        <div class="hero-slider-track" id="heroSliderTrack">
                            @foreach ($services as $index => $service)
                                <div class="hero-slide" data-index="{{ $index }}" data-slug="{{ $service->slug }}">
                                    <img src="{{ $service->imageUrl() }}" alt="{{ $service->name }} in Bangladesh" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                                    <div class="hero-slide-overlay"></div>
                                    <div class="hero-slide-caption">
                                        <div class="hero-slide-title">Care Coordinator Verified • {{ __($service->name) }}</div>
                                        <div class="hero-slide-desc">{{ __($service->short_description ?? $service->description) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Slider Navigation Buttons -->
                        <button type="button" class="hero-slider-nav-btn hero-slider-prev" onclick="prevHeroSlide()" aria-label="Previous Slide">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                        </button>
                        <button type="button" class="hero-slider-nav-btn hero-slider-next" onclick="nextHeroSlide()" aria-label="Next Slide">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>

                        <!-- Slider Pagination Dots -->
                        <div class="hero-slider-dots" id="heroSliderDots">
                            @foreach ($services as $index => $service)
                                <button type="button" class="hero-slider-dot {{ $index === 0 ? 'active' : '' }}" onclick="goToHeroSlide({{ $index }})" aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Floating Badge Card 1 -->
                    <div class="glass-card" style="position: absolute; top: -15px; right: -15px; padding: 0.85rem 1.25rem; display: flex; align-items: center; gap: 0.75rem; border-radius: var(--radius-md); box-shadow: var(--glass-shadow-lg); z-index: 15;">
                        <div style="width: 38px; height: 38px; border-radius: 50%; background: #dcfce7; display: flex; align-items: center; justify-content: center; color: #15803d; font-size: 1.2rem;">
                            🛡️
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 800; color: #0f172a;">100% Verified</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">CareMate Safety Stamp</div>
                        </div>
                    </div>

                    <!-- Floating Badge Card 2 -->
                    <div class="glass-card" style="position: absolute; bottom: -20px; left: -20px; padding: 0.85rem 1.25rem; display: flex; align-items: center; gap: 0.75rem; border-radius: var(--radius-md); box-shadow: var(--glass-shadow-lg); z-index: 15;">
                        <div style="display: flex; gap: 2px;">
                            @for ($i = 0; $i < 5; $i++)
                                <span style="color: #f59e0b; font-size: 0.9rem;">★</span>
                            @endfor
                        </div>
                        <div style="font-size: 0.85rem; font-weight: 800; color: #0f172a;">
                            {{ $stats['satisfaction_rate'] }}/5.0 <span style="font-weight: 500; font-size: 0.75rem; color: var(--text-muted);">Family Rating</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Bar -->
    <section style="margin-bottom: 4rem; position: relative; z-index: 1;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem;" class="stats-grid">
                <div class="glass-card" style="text-align: center; padding: 1.5rem;">
                    <div style="font-family: var(--font-heading); font-size: 2.3rem; font-weight: 800; color: #0a394a; line-height: 1;">
                        {{ $stats['caregivers_count'] }}+
                    </div>
                    <div style="font-size: 0.88rem; font-weight: 600; color: var(--text-secondary); margin-top: 0.35rem;">
                        Verified Caregivers
                    </div>
                </div>

                <div class="glass-card" style="text-align: center; padding: 1.5rem;">
                    <div style="font-family: var(--font-heading); font-size: 2.3rem; font-weight: 800; color: #059669; line-height: 1;">
                        {{ $stats['clients_count'] }}+
                    </div>
                    <div style="font-size: 0.88rem; font-weight: 600; color: var(--text-secondary); margin-top: 0.35rem;">
                        Happy Families
                    </div>
                </div>

                <div class="glass-card" style="text-align: center; padding: 1.5rem;">
                    <div style="font-family: var(--font-heading); font-size: 2.3rem; font-weight: 800; color: #0d9488; line-height: 1;">
                        {{ $stats['completed_bookings'] }}+
                    </div>
                    <div style="font-size: 0.88rem; font-weight: 600; color: var(--text-secondary); margin-top: 0.35rem;">
                        Care Days Delivered
                    </div>
                </div>

                <div class="glass-card" style="text-align: center; padding: 1.5rem;">
                    <div style="font-family: var(--font-heading); font-size: 2.3rem; font-weight: 800; color: #e11d48; line-height: 1;">
                        100%
                    </div>
                    <div style="font-size: 0.88rem; font-weight: 600; color: var(--text-secondary); margin-top: 0.35rem;">
                        Admin Mediated
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Services -->
    <section style="margin-bottom: 5rem;" id="services">
        <div class="container">
            <div style="text-align: center; max-width: 680px; margin: 0 auto 3rem auto;">
                <div style="display: inline-block; font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--brand-primary); margin-bottom: 0.5rem;">
                    Specialized Care Options
                </div>
                <h2 style="font-size: 2.4rem; font-weight: 800; color: #0f172a; margin-bottom: 0.75rem;">
                    Tailored care for every stage of life.
                </h2>
                <p style="color: var(--text-secondary); font-size: 1.05rem;">
                    Whether continuous companionship, post-operative nursing, or attentive child supervision, our vetted professionals are here for your family.
                </p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.75rem;" class="services-grid">
                @foreach ($services as $service)
                    <div class="glass-card glass-card-hover" style="display: flex; flex-direction: column; overflow: hidden; padding: 0;">
                        <div style="height: 180px; overflow: hidden; position: relative;">
                            <img src="{{ $service->imageUrl() }}" alt="{{ $service->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                            <div style="position: absolute; top: 0.75rem; right: 0.75rem;">
                                <x-badge tone="primary">From ৳{{ number_format($service->base_rate_daily) }}/day</x-badge>
                            </div>
                        </div>
                        <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; color: #0f172a;">
                                {{ $service->name }}
                            </h3>
                            <p style="font-size: 0.88rem; color: var(--text-secondary); line-height: 1.55; margin-bottom: 1.25rem; flex: 1;">
                                {{ Str::limit($service->description, 100) }}
                            </p>
                            <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid rgba(226, 232, 240, 0.7); padding-top: 1rem;">
                                <a href="{{ route('services.show', $service->slug) }}" style="font-size: 0.88rem; font-weight: 700; color: var(--brand-primary); display: flex; align-items: center; gap: 0.25rem;">
                                    <span>Learn Details</span>
                                    <span>→</span>
                                </a>
                                <a href="{{ route('marketplace.index', ['service' => $service->slug]) }}" class="btn btn-secondary btn-sm" style="font-size: 0.8rem;">
                                    View Staff
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Why CareMate / The Admin-Mediated Difference -->
    <section style="margin-bottom: 5rem;">
        <div class="container">
            <div class="glass-card" style="padding: 3rem; border-radius: var(--radius-xl); background: linear-gradient(135deg, rgba(255, 255, 255, 0.88) 0%, rgba(240, 253, 250, 0.88) 100%);">
                <div style="text-align: center; max-width: 720px; margin: 0 auto 3rem auto;">
                    <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #059669;">
                        The Safe Choice for Bangladesh
                    </span>
                    <h2 style="font-size: 2.3rem; font-weight: 800; margin-top: 0.5rem; color: #0f172a;">
                        Why CareMate BD is unlike traditional classifieds.
                    </h2>
                    <p style="color: var(--text-secondary); font-size: 1.05rem; margin-top: 0.5rem;">
                        Direct open contact leaves families vulnerable to scams, no-shows, and privacy loss. CareMate mediates the entire journey so your safety is never compromised.
                    </p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;" class="why-grid">
                    <div style="background: rgba(255, 255, 255, 0.75); border: 1px solid rgba(226, 232, 240, 0.8); border-radius: var(--radius-lg); padding: 1.75rem;">
                        <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(10, 57, 74, 0.12); display: flex; align-items: center; justify-content: center; color: #0a394a; margin-bottom: 1.25rem;">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                        <h4 style="font-size: 1.2rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">
                            Strict Identity Verification
                        </h4>
                        <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">
                            Every caregiver submits national NID, verified police clearance record, and clinical certifications inspected manually by CareMate Admin before going live.
                        </p>
                    </div>

                    <div style="background: rgba(255, 255, 255, 0.75); border: 1px solid rgba(226, 232, 240, 0.8); border-radius: var(--radius-lg); padding: 1.75rem;">
                        <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(16, 185, 129, 0.15); display: flex; align-items: center; justify-content: center; color: #059669; margin-bottom: 1.25rem;">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <h4 style="font-size: 1.2rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">
                            Zero Contact Harassment
                        </h4>
                        <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">
                            Clients and caregivers never see each other's phone numbers or personal emails. CareMate Support oversees all scheduling, dispatch, and communication.
                        </p>
                    </div>

                    <div style="background: rgba(255, 255, 255, 0.75); border: 1px solid rgba(226, 232, 240, 0.8); border-radius: var(--radius-lg); padding: 1.75rem;">
                        <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(245, 158, 11, 0.15); display: flex; align-items: center; justify-content: center; color: #d97706; margin-bottom: 1.25rem;">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><line x1="12" y1="6" x2="12" y2="8"/><line x1="12" y1="16" x2="12" y2="18"/></svg>
                        </div>
                        <h4 style="font-size: 1.2rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">
                            Escrow Protection
                        </h4>
                        <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">
                            Payments are held in platform escrow via bKash, Nagad, or Bank deposit. Caregivers are paid only after care hours have been verified and validated.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Verified Caregivers -->
    <section style="margin-bottom: 5rem;">
        <div class="container">
            <div style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--brand-primary);">
                        Available In Dhaka & Nationwide
                    </span>
                    <h2 style="font-size: 2.2rem; font-weight: 800; color: #0f172a; margin-top: 0.35rem;">
                        Meet Top-Rated Verified Caregivers
                    </h2>
                </div>
                <a href="{{ route('marketplace.index') }}" class="btn btn-secondary" style="font-size: 0.92rem;">
                    <span>View All Caregivers</span>
                    <span>→</span>
                </a>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;" class="caregivers-grid">
                @forelse ($featuredCaregivers as $caregiver)
                    <div class="glass-card glass-card-hover" style="display: flex; flex-direction: column;">
                        <div style="display: flex; align-items: flex-start; gap: 1.25rem; margin-bottom: 1.25rem;">
                            <div style="position: relative;">
                                <img src="{{ $caregiver->avatarUrl() }}" alt="{{ $caregiver->user->name }}" style="width: 72px; height: 72px; border-radius: 50%; object-fit: cover; border: 3px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
                                <div style="position: absolute; bottom: 0; right: 0; width: 22px; height: 22px; border-radius: 50%; background: #10b981; border: 2px solid #fff; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 11px;" title="Verified Caregiver">
                                    ✓
                                </div>
                            </div>
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <h4 style="font-size: 1.15rem; font-weight: 700; color: #0f172a;">{{ $caregiver->user->name }}</h4>
                                    <x-badge tone="success">Verified</x-badge>
                                </div>
                                <div style="font-size: 0.82rem; color: var(--text-muted); margin-top: 0.2rem;">
                                    📍 {{ $caregiver->area?->name ?? $caregiver->city }}, {{ $caregiver->district?->name }}
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.4rem;">
                                    <x-star-rating :rating="$caregiver->rating_avg" />
                                    <span style="font-size: 0.8rem; font-weight: 700; color: #0f172a;">{{ number_format($caregiver->rating_avg, 1) }}</span>
                                    <span style="font-size: 0.75rem; color: var(--text-muted);">({{ $caregiver->rating_count }} reviews)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Services & Experience Tags -->
                        <div style="display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 1.25rem;">
                            @foreach ($caregiver->services->take(2) as $s)
                                <span style="font-size: 0.75rem; background: rgba(10, 57, 74, 0.07); color: #0a394a; padding: 0.2rem 0.55rem; border-radius: var(--radius-sm); font-weight: 600;">
                                    {{ $s->name }}
                                </span>
                            @endforeach
                            <span style="font-size: 0.75rem; background: rgba(16, 185, 129, 0.08); color: #065f46; padding: 0.2rem 0.55rem; border-radius: var(--radius-sm); font-weight: 600;">
                                {{ $caregiver->years_experience }} Yrs Experience
                            </span>
                        </div>

                        <p style="font-size: 0.88rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 1.5rem; flex: 1;">
                            {{ Str::limit($caregiver->about, 90) }}
                        </p>

                        <!-- Rates & Action -->
                        <div style="border-top: 1px solid rgba(226, 232, 240, 0.7); padding-top: 1rem; display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Daily Rate</div>
                                <div style="font-size: 1.15rem; font-weight: 800; color: #0f172a;">
                                    ৳{{ number_format($caregiver->daily_rate) }}<span style="font-size: 0.75rem; font-weight: 500; color: var(--text-muted);">/day</span>
                                </div>
                            </div>
                            <a href="{{ route('marketplace.show', $caregiver->slug) }}" class="btn btn-primary btn-sm">
                                View Profile
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: span 3; text-align: center; padding: 3rem;">
                        <p style="color: var(--text-muted);">No verified caregivers available currently.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- How It Works 4-Step -->
    <section style="margin-bottom: 5rem; background: rgba(255, 255, 255, 0.5); padding: 4rem 0; border-top: 1px solid rgba(226, 232, 240, 0.6); border-bottom: 1px solid rgba(226, 232, 240, 0.6);">
        <div class="container">
            <div style="text-align: center; max-width: 650px; margin: 0 auto 3.5rem auto;">
                <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--brand-primary);">
                    Effortless Process
                </span>
                <h2 style="font-size: 2.2rem; font-weight: 800; color: #0f172a; margin-top: 0.35rem;">
                    How CareMate Protects Your Family
                </h2>
                <p style="color: var(--text-secondary); font-size: 1rem; margin-top: 0.5rem;">
                    Four simple steps to match, book, and enjoy peace of mind with 24/7 care coordination.
                </p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem;" class="steps-grid">
                <div class="glass-card" style="position: relative;">
                    <div style="font-size: 2rem; font-weight: 800; color: rgba(10, 57, 74, 0.25); font-family: var(--font-heading); margin-bottom: 0.5rem;">01</div>
                    <h4 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; color: #0f172a;">Request Care Online</h4>
                    <p style="font-size: 0.86rem; color: var(--text-secondary); line-height: 1.55;">
                        Browse verified caregiver profiles and submit your service requirements, schedule, and patient details.
                    </p>
                </div>

                <div class="glass-card" style="position: relative;">
                    <div style="font-size: 2rem; font-weight: 800; color: rgba(16, 185, 129, 0.25); font-family: var(--font-heading); margin-bottom: 0.5rem;">02</div>
                    <h4 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; color: #0f172a;">Admin Triage & Review</h4>
                    <p style="font-size: 0.86rem; color: var(--text-secondary); line-height: 1.55;">
                        CareMate Care Manager validates schedules, confirms caregiver availability, and ensures safety matching.
                    </p>
                </div>

                <div class="glass-card" style="position: relative;">
                    <div style="font-size: 2rem; font-weight: 800; color: rgba(245, 158, 11, 0.25); font-family: var(--font-heading); margin-bottom: 0.5rem;">03</div>
                    <h4 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; color: #0f172a;">Escrow Payment</h4>
                    <p style="font-size: 0.86rem; color: var(--text-secondary); line-height: 1.55;">
                        Lock the booking with secure bKash, Nagad, or Bank deposit held safely until service is completed.
                    </p>
                </div>

                <div class="glass-card" style="position: relative;">
                    <div style="font-size: 2rem; font-weight: 800; color: rgba(13, 148, 136, 0.25); font-family: var(--font-heading); margin-bottom: 0.5rem;">04</div>
                    <h4 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; color: #0f172a;">Supervised Care</h4>
                    <p style="font-size: 0.86rem; color: var(--text-secondary); line-height: 1.55;">
                        The verified caregiver reports on time. CareMate Support stays on standby 24/7 for regular check-ins.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    @if ($testimonials->isNotEmpty())
        <section style="margin-bottom: 5rem;">
            <div class="container">
                <div style="text-align: center; max-width: 600px; margin: 0 auto 3rem auto;">
                    <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--brand-primary);">
                        Real Stories
                    </span>
                    <h2 style="font-size: 2.2rem; font-weight: 800; color: #0f172a; margin-top: 0.35rem;">
                        Trusted by Bangladeshi Families
                    </h2>
                </div>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;" class="testimonials-grid">
                    @foreach ($testimonials->take(3) as $review)
                        <div class="glass-card" style="display: flex; flex-direction: column;">
                            <div style="display: flex; align-items: center; gap: 0.25rem; margin-bottom: 1rem;">
                                <x-star-rating :rating="$review->rating" />
                            </div>
                            <p style="font-size: 0.95rem; color: var(--text-secondary); font-style: italic; line-height: 1.6; margin-bottom: 1.5rem; flex: 1;">
                                "{{ $review->comment }}"
                            </p>
                            <div style="border-top: 1px solid rgba(226, 232, 240, 0.7); padding-top: 1rem; display: flex; align-items: center; justify-content: space-between;">
                                <div>
                                    <div style="font-weight: 700; font-size: 0.9rem; color: #0f172a;">{{ $review->client->user->name ?? 'Verified Family' }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">Care recipient family</div>
                                </div>
                                <span style="font-size: 0.75rem; color: #059669; font-weight: 700; background: rgba(16, 185, 129, 0.1); padding: 0.25rem 0.5rem; border-radius: var(--radius-pill);">
                                    ✓ Verified Care
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Become a Caregiver CTA Banner -->
    <section style="margin-bottom: 5rem;">
        <div class="container">
            <div class="glass-card" style="padding: 0; overflow: hidden; border-radius: var(--radius-xl); background: linear-gradient(135deg, #0a394a 0%, #15798e 100%); color: #fff; box-shadow: var(--glass-shadow-lg);">
                <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; align-items: center;" class="cta-grid">
                    <div style="padding: 3.5rem;">
                        <span style="display: inline-block; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); padding: 0.35rem 0.85rem; border-radius: var(--radius-pill); font-size: 0.82rem; font-weight: 700; margin-bottom: 1.25rem;">
                            Caregiver Careers
                        </span>
                        <h2 style="font-size: 2.4rem; font-weight: 800; line-height: 1.2; margin-bottom: 1rem; color: #fff;">
                            Are you a professional nurse, nanny, or caregiver?
                        </h2>
                        <p style="font-size: 1.05rem; opacity: 0.95; line-height: 1.6; margin-bottom: 2rem;">
                            Join Bangladesh's premier verified care network. Guaranteed timely payouts via bKash, dignity, fair compensation, and full mediation protection.
                        </p>
                        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <a href="{{ route('caregiver.register') }}" class="btn" style="background: #fff; color: #0a394a !important; font-weight: 700; padding: 0.85rem 1.8rem;">
                                Apply as Caregiver →
                            </a>
                            <a href="{{ route('how-it-works') }}" class="btn btn-secondary" style="background: rgba(255, 255, 255, 0.15); color: #fff !important; border-color: rgba(255, 255, 255, 0.3);">
                                Learn How It Works
                            </a>
                        </div>
                    </div>
                    <div style="height: 100%; min-height: 380px;">
                        <img src="{{ asset('images/become_caregiver.jpg') }}" alt="Professional Bangladeshi female nurse" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Preview Section -->
    <section style="margin-bottom: 5rem;">
        <div class="container container-narrow">
            <div style="text-align: center; margin-bottom: 3rem;">
                <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--brand-primary);">
                    Questions & Answers
                </span>
                <h2 style="font-size: 2.2rem; font-weight: 800; color: #0f172a; margin-top: 0.35rem;">
                    Frequently Asked Questions
                </h2>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach ($faqs as $faq)
                    <div class="glass-card" style="padding: 1.5rem;">
                        <h4 style="font-size: 1.05rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">
                            {{ $faq->question }}
                        </h4>
                        <p style="font-size: 0.92rem; color: var(--text-secondary); line-height: 1.6;">
                            {{ $faq->answer }}
                        </p>
                    </div>
                @endforeach
            </div>

            <div style="text-align: center; margin-top: 2rem;">
                <a href="{{ route('faq') }}" class="btn btn-secondary btn-sm">
                    View All Frequently Asked Questions →
                </a>
            </div>
        </div>
    </section>

    <!-- AI Overview & Caregiver Guide: Authoritative Quick Answers & Local Pricing -->
    <section style="margin-bottom: 5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding: 4rem 0;">
        <div class="container">
            <div style="max-width: 860px; margin: 0 auto;">
                <div style="text-align: center; margin-bottom: 2.5rem;">
                    <span style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--brand-primary); background: rgba(10,57,74,0.08); padding: 0.35rem 0.85rem; border-radius: var(--radius-pill);">
                        {{ __('Caregiver Guide & Quick Facts') }}
                    </span>
                    <h2 style="font-size: 2.2rem; font-weight: 800; color: #0f172a; margin-top: 0.75rem;">
                        {{ __('Hiring a Verified Caregiver in Bangladesh — What You Need to Know') }}
                    </h2>
                    <p style="font-size: 1.05rem; color: var(--text-secondary); margin-top: 0.5rem;">
                        {{ __('Key answers to common questions about caregiver safety, standard pricing, shift types, and hiring procedures.') }}
                    </p>
                </div>

                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <article class="glass-card" style="padding: 1.75rem; background: #fff; border: 1px solid rgba(226, 232, 240, 0.9);">
                        <h3 style="font-size: 1.2rem; font-weight: 700; color: #0a394a; margin-bottom: 0.65rem;">
                            {{ __('How does CareMate BD verify caregivers in Bangladesh?') }}
                        </h3>
                        <p style="font-size: 0.95rem; color: #334155; line-height: 1.7; margin: 0;">
                            {{ __('Every caregiver on CareMate BD undergoes strict multi-step vetting before their profile is published. This includes government National ID (NID) digital authentication, permanent address physical verification, police background check clearance, clinical qualification review for nurses, and an in-person interview with a Care Coordinator. Family members can review verified badges and certifications directly on each caregiver profile.') }}
                        </p>
                    </article>

                    <article class="glass-card" style="padding: 1.75rem; background: #fff; border: 1px solid rgba(226, 232, 240, 0.9);">
                        <h3 style="font-size: 1.2rem; font-weight: 700; color: #0a394a; margin-bottom: 0.65rem;">
                            {{ __('What are the standard caregiver rates and shifts in Dhaka?') }}
                        </h3>
                        <p style="font-size: 0.95rem; color: #334155; line-height: 1.7; margin-bottom: 0.85rem;">
                            {{ __('Caregiver charges in Dhaka depend on the level of care required (basic elderly assistance, child supervision, or specialized post-surgery nursing) and shift duration:') }}
                        </p>
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; text-align: left;">
                                <thead>
                                    <tr style="border-bottom: 2px solid #e2e8f0; background: #f1f5f9;">
                                        <th style="padding: 0.75rem 1rem; color: #0a394a; font-weight: 700;">{{ __('Service Shift') }}</th>
                                        <th style="padding: 0.75rem 1rem; color: #0a394a; font-weight: 700;">{{ __('Duration') }}</th>
                                        <th style="padding: 0.75rem 1rem; color: #0a394a; font-weight: 700;">{{ __('Typical Rate Range') }}</th>
                                        <th style="padding: 0.75rem 1rem; color: #0a394a; font-weight: 700;">{{ __('Best Suited For') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="border-bottom: 1px solid #e2e8f0;">
                                        <td style="padding: 0.75rem 1rem; font-weight: 600;">{{ __('Hourly / Short Visit') }}</td>
                                        <td style="padding: 0.75rem 1rem;">2 – 6 {{ __('Hours') }}</td>
                                        <td style="padding: 0.75rem 1rem; color: #0a394a; font-weight: 700;">৳150 – ৳350 / {{ __('hr') }}</td>
                                        <td style="padding: 0.75rem 1rem;">{{ __('Doctor visits, wound dressing, companion walk') }}</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #e2e8f0; background: #fafafa;">
                                        <td style="padding: 0.75rem 1rem; font-weight: 600;">{{ __('Day Shift / Night Shift') }}</td>
                                        <td style="padding: 0.75rem 1rem;">10 – 12 {{ __('Hours') }}</td>
                                        <td style="padding: 0.75rem 1rem; color: #0a394a; font-weight: 700;">৳800 – ৳1,800 / {{ __('day') }}</td>
                                        <td style="padding: 0.75rem 1rem;">{{ __('Working parents, post-discharge hospital recovery') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0.75rem 1rem; font-weight: 600;">{{ __('24/7 Live-In (Monthly)') }}</td>
                                        <td style="padding: 0.75rem 1rem;">{{ __('Full Time Monthly') }}</td>
                                        <td style="padding: 0.75rem 1rem; color: #0a394a; font-weight: 700;">৳18,000 – ৳35,000 / {{ __('mo') }}</td>
                                        <td style="padding: 0.75rem 1rem;">{{ __('Bedridden seniors, Alzheimer\'s, continuous patient care') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </article>

                    <article class="glass-card" style="padding: 1.75rem; background: #fff; border: 1px solid rgba(226, 232, 240, 0.9);">
                        <h3 style="font-size: 1.2rem; font-weight: 700; color: #0a394a; margin-bottom: 0.65rem;">
                            {{ __('What happens if a caregiver falls sick or is unavailable?') }}
                        </h3>
                        <p style="font-size: 0.95rem; color: #334155; line-height: 1.7; margin: 0;">
                            {{ __('CareMate BD operates with an active admin-mediated Care Coordination Desk. If your assigned caregiver faces an unexpected emergency, our Care Managers immediately arrange an equivalent background-verified substitute caregiver at no extra coordination charge, ensuring uninterrupted continuity of care for your loved one.') }}
                        </p>
                    </article>

                    <article class="glass-card" style="padding: 1.75rem; background: #fff; border: 1px solid rgba(226, 232, 240, 0.9);">
                        <h3 style="font-size: 1.2rem; font-weight: 700; color: #0a394a; margin-bottom: 0.65rem;">
                            {{ __('Which areas in Bangladesh are covered by CareMate BD?') }}
                        </h3>
                        <p style="font-size: 0.95rem; color: #334155; line-height: 1.7; margin: 0;">
                            {{ __('We provide complete caregiver coverage across all major areas of Dhaka city, including Gulshan, Banani, Baridhara, Dhanmondi, Uttara, Bashundhara R/A, Mirpur, Mohammadpur, Badda, Khilgaon, and Old Dhaka. We also coordinate nursing and elderly home care in Chattogram and Sylhet metropolitan areas.') }}
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        // ==========================================
        // 1. Hero Service Carousel / Slider Engine
        // ==========================================
        let currentHeroSlide = 0;
        let heroSlideTimer = null;
        const heroSliderTrack = document.getElementById('heroSliderTrack');
        const heroSlides = document.querySelectorAll('#heroSliderTrack .hero-slide');
        const heroDots = document.querySelectorAll('#heroSliderDots .hero-slider-dot');
        const totalHeroSlides = heroSlides.length;

        function goToHeroSlide(index) {
            if (!heroSliderTrack || totalHeroSlides === 0) return;
            currentHeroSlide = (index + totalHeroSlides) % totalHeroSlides;
            heroSliderTrack.style.transform = `translateX(-${currentHeroSlide * 100}%)`;
            
            heroDots.forEach((dot, idx) => {
                dot.classList.toggle('active', idx === currentHeroSlide);
            });
        }

        function nextHeroSlide() {
            goToHeroSlide(currentHeroSlide + 1);
        }

        function prevHeroSlide() {
            goToHeroSlide(currentHeroSlide - 1);
        }

        function startHeroSlideTimer() {
            stopHeroSlideTimer();
            if (totalHeroSlides > 1) {
                heroSlideTimer = setInterval(nextHeroSlide, 5000);
            }
        }

        function stopHeroSlideTimer() {
            if (heroSlideTimer) {
                clearInterval(heroSlideTimer);
                heroSlideTimer = null;
            }
        }

        // Touch & Swipe Support for Hero Slider
        const sliderContainer = document.getElementById('heroServiceSlider');
        if (sliderContainer) {
            let touchStartX = 0;
            let touchStartY = 0;
            sliderContainer.addEventListener('mouseenter', stopHeroSlideTimer);
            sliderContainer.addEventListener('mouseleave', startHeroSlideTimer);

            sliderContainer.addEventListener('touchstart', (e) => {
                stopHeroSlideTimer();
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
            }, { passive: true });

            sliderContainer.addEventListener('touchend', (e) => {
                const deltaX = e.changedTouches[0].clientX - touchStartX;
                const deltaY = e.changedTouches[0].clientY - touchStartY;
                if (Math.abs(deltaX) > 40 && Math.abs(deltaX) > Math.abs(deltaY)) {
                    if (deltaX < 0) {
                        nextHeroSlide();
                    } else {
                        prevHeroSlide();
                    }
                }
                startHeroSlideTimer();
            }, { passive: true });

            startHeroSlideTimer();
        }

        // ==========================================
        // 2. Service Chip Selection
        // ==========================================
        function selectHeroServiceChip(slug, element) {
            document.querySelectorAll('.pathao-service-chip').forEach(chip => chip.classList.remove('active'));
            if (element) {
                element.classList.add('active');
            }
            const hiddenInput = document.getElementById('heroSelectedService');
            if (hiddenInput) {
                hiddenInput.value = slug;
            }

            // Sync with slider if matching slide exists
            if (slug && heroSlides.length) {
                heroSlides.forEach((slide, idx) => {
                    if (slide.dataset.slug === slug) {
                        goToHeroSlide(idx);
                        stopHeroSlideTimer();
                    }
                });
            }
        }

        // ==========================================
        // 3. Pathao-Style Location Management
        // ==========================================
        function toggleLocationPicker(forceState) {
            const modal = document.getElementById('locationPickerModal');
            if (!modal) return;
            const isOpen = typeof forceState === 'boolean' ? forceState : !modal.classList.contains('open');
            modal.classList.toggle('open', isOpen);
            if (isOpen) {
                const input = document.getElementById('manualLocationInput');
                if (input) {
                    setTimeout(() => input.focus(), 100);
                }
            }
        }

        // Close location picker when clicking outside
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('locationPickerModal');
            const trigger = document.getElementById('pathaoLocationTrigger');
            if (modal && modal.classList.contains('open')) {
                if (!modal.contains(e.target) && !trigger.contains(e.target)) {
                    modal.classList.remove('open');
                }
            }
        });

        function selectAreaZone(areaName, element) {
            document.querySelectorAll('.pathao-quick-zones .pathao-zone-chip').forEach(chip => {
                chip.classList.toggle('selected', chip.textContent.trim().toLowerCase() === areaName.toLowerCase());
            });

            applyLocationValue(areaName, `${areaName}, Dhaka`, true);
        }

        function applyQuickLocation(areaName) {
            selectAreaZone(areaName);
            toggleLocationPicker(false);
        }

        function applyManualLocation() {
            const input = document.getElementById('manualLocationInput');
            if (!input) return;
            const val = input.value.trim();
            if (val) {
                applyLocationValue(val, val, true);
                toggleLocationPicker(false);
            }
        }

        function handleManualLocationKey(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyManualLocation();
            }
        }

        function updateLocationStatus(text) {
            const locStatus = document.getElementById('pathaoLocStatus');
            if (!locStatus) return;
            const textEl = locStatus.querySelector('.pathao-status-text') || locStatus;
            textEl.textContent = text;
        }

        function applyLocationValue(searchVal, displayName, isManual = false) {
            const locNameEl = document.getElementById('pathaoLocationName');
            const locInputEl = document.getElementById('heroLocationInput');
            const btnTextEl = document.getElementById('findCareBtnText');

            if (locNameEl) locNameEl.textContent = displayName;
            if (locInputEl) locInputEl.value = searchVal;
            if (isManual) updateLocationStatus('{{ __("Selected") }}');
            if (btnTextEl) btnTextEl.textContent = `{{ __("Find Caregiver in") }} ${searchVal}`;

            // Sync quick zone chip selection
            document.querySelectorAll('.pathao-quick-zones .pathao-zone-chip').forEach(chip => {
                chip.classList.toggle('selected', chip.textContent.trim().toLowerCase() === searchVal.toLowerCase());
            });

            try {
                localStorage.setItem('caremate_user_location', JSON.stringify({
                    search: searchVal,
                    name: displayName,
                    isManual: isManual,
                    timestamp: Date.now()
                }));
            } catch (err) {}
        }

        async function detectLocationApi(lat = null, lon = null) {
            let url = '{{ route("api.detect-location") }}';
            if (lat !== null && lon !== null) {
                url += `?lat=${encodeURIComponent(lat)}&lon=${encodeURIComponent(lon)}`;
            }
            const response = await fetch(url, {
                headers: { 'Accept': 'application/json' }
            });
            if (!response.ok) throw new Error('Location detection failed');
            return await response.json();
        }

        async function fallbackToNetworkLocation() {
            try {
                const data = await detectLocationApi();
                if (data && data.success) {
                    applyLocationValue(data.area, data.display, false);
                    updateLocationStatus(data.source === 'ip' ? '{{ __("Network") }}' : '{{ __("Ready") }}');
                }
            } catch (e) {
                updateLocationStatus('{{ __("Ready") }}');
            }
        }

        async function detectCurrentLocation(userInitiated = false) {
            const pinIcon = document.getElementById('pathaoPinIcon');
            const gpsBtn = document.getElementById('pathaoGpsBtn');

            if (pinIcon) pinIcon.classList.add('locating');
            if (gpsBtn) gpsBtn.disabled = true;
            updateLocationStatus('{{ __("Detecting...") }}');

            if (!navigator.geolocation) {
                if (userInitiated) {
                    alert('{{ __("Geolocation is not supported by your browser. Please select or type your area.") }}');
                }
                await fallbackToNetworkLocation();
                if (pinIcon) pinIcon.classList.remove('locating');
                if (gpsBtn) gpsBtn.disabled = false;
                return;
            }

            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    try {
                        const lat = position.coords.latitude;
                        const lon = position.coords.longitude;
                        const data = await detectLocationApi(lat, lon);
                        if (data && data.success) {
                            applyLocationValue(data.area, data.display, false);
                            updateLocationStatus('{{ __("GPS Detected") }}');
                        } else {
                            await fallbackToNetworkLocation();
                        }
                    } catch (err) {
                        await fallbackToNetworkLocation();
                    } finally {
                        if (pinIcon) pinIcon.classList.remove('locating');
                        if (gpsBtn) gpsBtn.disabled = false;
                    }
                },
                async (error) => {
                    if (userInitiated) {
                        if (error.code === error.PERMISSION_DENIED) {
                            alert('{{ __("Location permission was denied. Please allow location access in your browser settings or select an area manually.") }}');
                        } else {
                            alert('{{ __("Could not detect GPS location. Falling back to network location.") }}');
                        }
                    }
                    await fallbackToNetworkLocation();
                    if (pinIcon) pinIcon.classList.remove('locating');
                    if (gpsBtn) gpsBtn.disabled = false;
                },
                { timeout: 7000, enableHighAccuracy: true, maximumAge: 60000 }
            );
        }

        document.addEventListener('DOMContentLoaded', function() {
            try {
                const saved = localStorage.getItem('caremate_user_location');
                if (saved) {
                    const data = JSON.parse(saved);
                    if (data && data.search && data.isManual) {
                        applyLocationValue(data.search, data.name || data.search, true);
                        updateLocationStatus('{{ __("Saved") }}');
                        return;
                    }
                }
            } catch (e) {}

            detectCurrentLocation(false);
        });
    </script>
    @endpush
</x-layouts.guest>
