<x-layouts.guest>
    <x-slot:title>Care Services — CareMate BD | Elderly, Child, Nursing, Medical Transport</x-slot:title>
    <x-slot:description>Explore specialized in-home caregiving services across Bangladesh. From elderly companionship to critical nursing care.</x-slot:description>

    <div class="container" style="padding: 3rem 1.25rem 5rem 1.25rem;">
        <div style="text-align: center; max-width: 720px; margin: 0 auto 3.5rem auto;">
            <div style="display: inline-block; font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--brand-primary); margin-bottom: 0.5rem;">
                Professional Care Catalog
            </div>
            <h1 style="font-size: 2.8rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">
                Specialized In-Home Care Services
            </h1>
            <p style="color: var(--text-secondary); font-size: 1.1rem; line-height: 1.6;">
                Every service is backed by background-checked caregivers, managed shift scheduling, and dedicated CareMate Care Managers.
            </p>
        </div>

        <div style="display: flex; flex-direction: column; gap: 2.5rem;">
            @foreach ($services as $service)
                <div class="glass-card" style="padding: 0; overflow: hidden; display: grid; grid-template-columns: 380px 1fr; gap: 2rem; border-radius: var(--radius-xl);">
                    <div style="height: 100%; min-height: 280px; position: relative; overflow: hidden;">
                        <img src="{{ $service->imageUrl() }}" alt="{{ $service->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        <div style="position: absolute; top: 1rem; left: 1rem;">
                            <x-badge tone="primary">{{ $service->name }}</x-badge>
                        </div>
                    </div>

                    <div style="padding: 2.25rem 2.25rem 2.25rem 0; display: flex; flex-direction: column; justify-content: center;">
                        <div style="display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
                            <h2 style="font-size: 1.85rem; font-weight: 800; color: #0f172a;">
                                {{ $service->name }}
                            </h2>
                            <div style="font-size: 1.15rem; font-weight: 800; color: #0a394a;">
                                Starting ৳{{ number_format($service->base_rate_daily) }}<span style="font-size: 0.8rem; font-weight: 500; color: var(--text-muted);">/day</span>
                            </div>
                        </div>

                        <p style="font-size: 1rem; color: var(--text-secondary); line-height: 1.65; margin-bottom: 1.5rem;">
                            {{ $service->description }}
                        </p>

                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; margin-bottom: 2rem; font-size: 0.88rem; color: var(--text-primary);">
                            <div style="display: flex; align-items: center; gap: 0.4rem;">
                                <span style="color: #059669; font-weight: 700;">✓</span> Day, Night & 24/7 Live-in Options
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.4rem;">
                                <span style="color: #059669; font-weight: 700;">✓</span> Care Plan Customized per Patient
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.4rem;">
                                <span style="color: #059669; font-weight: 700;">✓</span> Replacement Guarantee within 4h
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.4rem;">
                                <span style="color: #059669; font-weight: 700;">✓</span> Daily Duty Logs for Families
                            </div>
                        </div>

                        <div style="display: flex; gap: 1rem; align-items: center;">
                            <a href="{{ route('services.show', $service->slug) }}" class="btn btn-primary">
                                <span>Service Details & Pricing</span>
                                <span>→</span>
                            </a>
                            <a href="{{ route('marketplace.index', ['service' => $service->slug]) }}" class="btn btn-secondary">
                                Browse {{ $service->name }} Caregivers
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.guest>
