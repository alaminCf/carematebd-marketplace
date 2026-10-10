<x-layouts.guest>
    <x-slot:title>{{ $service->name }} in Bangladesh — CareMate BD</x-slot:title>
    <x-slot:description>{{ Str::limit($service->description, 160) }}</x-slot:description>

    @push('schema')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Home',
                        'item' => route('home'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => 'Services',
                        'item' => route('services.index'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $service->name,
                        'item' => route('services.show', $service->slug),
                    ],
                ],
            ],
            [
                '@type' => 'Service',
                'name' => $service->name,
                'serviceType' => 'Caregiver & Home Healthcare Service',
                'description' => $service->description,
                'provider' => [
                    '@type' => 'Organization',
                    '@id' => url('/') . '/#organization',
                    'name' => 'CareMate BD',
                ],
                'areaServed' => [
                    ['@type' => 'City', 'name' => 'Dhaka'],
                    ['@type' => 'Country', 'name' => 'Bangladesh'],
                ],
                'offers' => [
                    '@type' => 'Offer',
                    'priceCurrency' => 'BDT',
                    'price' => (string) $service->base_rate_daily,
                    'priceSpecification' => [
                        '@type' => 'UnitPriceSpecification',
                        'price' => (string) $service->base_rate_daily,
                        'priceCurrency' => 'BDT',
                        'unitCode' => 'DAY',
                    ],
                    'availability' => 'https://schema.org/InStock',
                ],
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @endpush

    <div class="container" style="padding: 3rem 1.25rem 5rem 1.25rem;">
        <!-- Service Hero Banner -->
        <div class="glass-card service-hero-card">
            <div class="service-hero-content">
                <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                    <a href="{{ route('services.index') }}" style="font-size: 0.85rem; color: var(--text-muted);">{{ __('Services') }}</a>
                    <span style="color: var(--text-muted);">/</span>
                    <span style="font-size: 0.85rem; color: var(--brand-primary); font-weight: 700;">{{ $service->name }}</span>
                </div>

                <h1 class="service-hero-title">
                    {{ $service->name }}
                </h1>

                <p class="service-hero-desc">
                    {{ $service->description }}
                </p>

                <div class="service-rate-banner">
                    <div style="font-size: 0.82rem; font-weight: 700; color: #0a394a; text-transform: uppercase;">{{ __('Standard Platform Baseline Rate') }}</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-top: 0.25rem;">
                        ৳{{ number_format($service->base_rate_daily) }} <span style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">{{ __('per day (negotiable depending on condition)') }}</span>
                    </div>
                </div>

                <div class="service-hero-actions">
                    <a href="{{ route('marketplace.index', ['service' => $service->slug]) }}" class="btn btn-primary btn-lg">
                        {{ __('Find :service Staff', ['service' => $service->name]) }}
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-secondary btn-lg">
                        {{ __('Talk to Care Manager') }}
                    </a>
                </div>
            </div>

            <div class="service-hero-media">
                <img src="{{ $service->imageUrl() }}" alt="{{ $service->name }}">
            </div>
        </div>

        <!-- Available Staff Providing This Service -->
        <div>
            <div class="service-staff-header">
                <div>
                    <h2 style="font-size: 1.85rem; font-weight: 800; color: #0f172a;">
                        {{ __('Verified :service Caregivers', ['service' => $service->name]) }}
                    </h2>
                    <p style="font-size: 0.95rem; color: var(--text-secondary);">
                        {{ __('All caregivers below are qualified, background-checked, and ready to serve your family.') }}
                    </p>
                </div>
                <span class="service-staff-count">
                    {{ __(':count Caregivers Available', ['count' => $caregivers->total()]) }}
                </span>
            </div>

            @if ($caregivers->isNotEmpty())
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.75rem; margin-bottom: 2.5rem;" class="caregivers-grid">
                    @foreach ($caregivers as $caregiver)
                        <div class="glass-card glass-card-hover" style="display: flex; flex-direction: column;">
                            <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1rem;">
                                <img src="{{ $caregiver->avatarUrl() }}" alt="{{ $caregiver->user->name }}" style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <h4 style="font-size: 1.1rem; font-weight: 700; color: #0f172a;">{{ $caregiver->user->name }}</h4>
                                        <x-badge tone="success">{{ __('Verified') }}</x-badge>
                                    </div>
                                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.2rem;">
                                        📍 {{ $caregiver->area?->name ?? $caregiver->city }}, {{ $caregiver->district?->name }}
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.4rem; margin-top: 0.35rem;">
                                        <x-star-rating :rating="$caregiver->rating_avg" />
                                        <span style="font-size: 0.8rem; font-weight: 700;">{{ number_format($caregiver->rating_avg, 1) }}</span>
                                    </div>
                                </div>
                            </div>

                            <p style="font-size: 0.86rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 1.25rem; flex: 1;">
                                {{ Str::limit($caregiver->about, 90) }}
                            </p>

                            <div style="border-top: 1px solid rgba(226, 232, 240, 0.7); padding-top: 1rem; display: flex; align-items: center; justify-content: space-between;">
                                <div>
                                    <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">{{ __('Rate') }}</div>
                                    <div style="font-size: 1.1rem; font-weight: 800; color: #0f172a;">
                                        ৳{{ number_format($caregiver->daily_rate) }}<span style="font-size: 0.75rem; font-weight: 500; color: var(--text-muted);">{{ __('/day') }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('marketplace.show', $caregiver->slug) }}" class="btn btn-primary btn-sm">
                                    {{ __('View & Request') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div>
                    {{ $caregivers->links() }}
                </div>
            @else
                <div class="glass-card" style="text-align: center; padding: 4rem 2rem;">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem;">🩺</div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">{{ __('Currently Matching New Caregivers') }}</h3>
                    <p style="color: var(--text-secondary); max-width: 500px; margin: 0 auto 1.5rem auto;">
                        {{ __('Our care managers are constantly onboarding and verifying specialized caregivers for this service. Contact us directly to arrange a dedicated match.') }}
                    </p>
                    <a href="{{ route('contact') }}" class="btn btn-primary">{{ __('Contact Care Desk') }}</a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.guest>
