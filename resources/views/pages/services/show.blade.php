<x-layouts.guest>
    <x-slot:title>{{ $service->name }} in Bangladesh — CareMate BD</x-slot:title>
    <x-slot:description>{{ Str::limit($service->description, 160) }}</x-slot:description>

    <div class="container" style="padding: 3rem 1.25rem 5rem 1.25rem;">
        <!-- Service Hero Banner -->
        <div class="glass-card service-hero-card">
            <div class="service-hero-content">
                <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                    <a href="{{ route('services.index') }}" style="font-size: 0.85rem; color: var(--text-muted);">Services</a>
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
                    <div style="font-size: 0.82rem; font-weight: 700; color: #0a394a; text-transform: uppercase;">Standard Platform Baseline Rate</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-top: 0.25rem;">
                        ৳{{ number_format($service->base_rate_daily) }} <span style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">per day (negotiable depending on condition)</span>
                    </div>
                </div>

                <div class="service-hero-actions">
                    <a href="{{ route('marketplace.index', ['service' => $service->slug]) }}" class="btn btn-primary btn-lg">
                        Find {{ $service->name }} Staff
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-secondary btn-lg">
                        Talk to Care Manager
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
                        Verified {{ $service->name }} Caregivers
                    </h2>
                    <p style="font-size: 0.95rem; color: var(--text-secondary);">
                        All caregivers below are qualified, background-checked, and ready to serve your family.
                    </p>
                </div>
                <span class="service-staff-count">
                    {{ $caregivers->total() }} Caregivers Available
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
                                        <x-badge tone="success">Verified</x-badge>
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
                                    <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Rate</div>
                                    <div style="font-size: 1.1rem; font-weight: 800; color: #0f172a;">
                                        ৳{{ number_format($caregiver->daily_rate) }}<span style="font-size: 0.75rem; font-weight: 500; color: var(--text-muted);">/day</span>
                                    </div>
                                </div>
                                <a href="{{ route('marketplace.show', $caregiver->slug) }}" class="btn btn-primary btn-sm">
                                    View & Request
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
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">Currently Matching New Caregivers</h3>
                    <p style="color: var(--text-secondary); max-width: 500px; margin: 0 auto 1.5rem auto;">
                        Our care managers are constantly onboarding and verifying specialized caregivers for this service. Contact us directly to arrange a dedicated match.
                    </p>
                    <a href="{{ route('contact') }}" class="btn btn-primary">Contact Care Desk</a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.guest>
