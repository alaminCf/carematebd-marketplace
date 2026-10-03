<x-layouts.dashboard>
    <x-slot:title>Saved Caregivers — CareMate BD</x-slot:title>
    <x-slot:header>Saved Caregivers</x-slot:header>
    <x-slot:subheading>Your family's favorite verified caregivers for quick recurring care requests.</x-slot:subheading>

    <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
        @if ($favorites->isNotEmpty())
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;" class="caregivers-grid">
                @foreach ($favorites as $cg)
                    <div class="glass-card glass-card-hover" style="display: flex; flex-direction: column;">
                        <div style="display: flex; gap: 1rem; align-items: flex-start; margin-bottom: 1rem;">
                            <img src="{{ $cg->avatarUrl() }}" alt="{{ $cg->user->name }}" style="width: 58px; height: 58px; border-radius: 50%; object-fit: cover;">
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <h4 style="font-size: 1.1rem; font-weight: 800; color: #0f172a;">{{ $cg->user->name }}</h4>
                                    <form action="{{ route('client.favorites.toggle', $cg->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" title="Remove from favorites" style="background: none; border: none; cursor: pointer; color: #e11d48; font-size: 1.2rem;">
                                            ♥
                                        </button>
                                    </form>
                                </div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">
                                    📍 {{ $cg->area?->name ?? $cg->city }}
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.35rem; margin-top: 0.25rem;">
                                    <x-star-rating :rating="$cg->rating_avg" />
                                    <span style="font-size: 0.8rem; font-weight: 700;">{{ number_format($cg->rating_avg, 1) }}</span>
                                </div>
                            </div>
                        </div>

                        <div style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 1rem; flex: 1;">
                            {{ Str::limit($cg->about, 80) }}
                        </div>

                        <div style="border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 0.75rem; display: flex; align-items: center; justify-content: space-between;">
                            <div style="font-weight: 800; color: #0f172a;">
                                ৳{{ number_format($cg->daily_rate) }}<span style="font-size: 0.75rem; color: var(--text-muted);">/day</span>
                            </div>
                            <a href="{{ route('client.requests.create', $cg->id) }}" class="btn btn-primary btn-sm">
                                Request Care
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 4rem 1rem; color: var(--text-muted);">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">♥</div>
                <h4 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">No Saved Caregivers</h4>
                <p style="margin-bottom: 1.5rem;">Browse caregiver profiles and click the heart icon to save them to your list.</p>
                <a href="{{ route('marketplace.index') }}" class="btn btn-primary">Browse Marketplace</a>
            </div>
        @endif
    </div>
</x-layouts.dashboard>
