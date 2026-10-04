<x-layouts.guest>
    <x-slot:title>Find Verified Caregivers in Bangladesh — CareMate BD</x-slot:title>
    <x-slot:description>Search and filter background-checked caregivers for elderly care, child care, nursing, and medical transport across Dhaka and Bangladesh.</x-slot:description>

    <div class="container" style="padding: 2.5rem 1.25rem 5rem 1.25rem;">
        <!-- Header -->
        <div style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; font-weight: 700; color: #059669; margin-bottom: 0.35rem;">
                <span>🛡️ CAREMATE VERIFIED NETWORK</span>
            </div>
            <h1 style="font-size: 2.4rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">
                Find Trusted Caregivers in Bangladesh
            </h1>
            <p style="color: var(--text-secondary); font-size: 1.05rem;">
                Browse background-verified professionals. Submit a care request — our Care Managers handle all verification, scheduling, and contract safety.
            </p>
        </div>

        <!-- Mobile Filter Toggle Button -->
        <div class="mobile-filter-trigger-wrap" style="display: none; margin-bottom: 1.25rem;">
            <button type="button" id="mobileFilterToggleBtn" class="btn btn-secondary" style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1.25rem; font-weight: 700;">
                <span style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    <span>Filter & Search Caregivers</span>
                </span>
                <span id="mobileFilterArrow" style="transition: transform 0.2s ease;">▼</span>
            </button>
        </div>

        <div class="marketplace-layout" style="display: grid; gap: 2rem; align-items: flex-start;">
            <!-- Filter Sidebar -->
            <aside id="marketplaceFilterAside" class="glass-card marketplace-filter-aside" style="padding: 1.75rem; position: sticky; top: 90px; max-height: calc(100vh - 110px); overflow-y: auto;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8);">
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: #0f172a;">Filters</h3>
                    <a href="{{ route('marketplace.index') }}" style="font-size: 0.8rem; color: var(--brand-primary); font-weight: 600;">Reset All</a>
                </div>

                <form method="GET" action="{{ route('marketplace.index') }}" id="filterForm">
                    <!-- Keyword Search -->
                    <div style="margin-bottom: 1.25rem;">
                        <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">Keywords / Name</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="e.g. nurse, dementia" class="glass-input" style="font-size: 0.88rem; padding: 0.55rem 0.8rem;">
                    </div>

                    <!-- Service Category -->
                    <div style="margin-bottom: 1.25rem;">
                        <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">Care Specialty</label>
                        <select name="service" class="glass-input" style="font-size: 0.88rem; padding: 0.55rem 0.8rem;">
                            <option value="">All Services</option>
                            @foreach ($services as $s)
                                <option value="{{ $s->slug }}" {{ request('service') == $s->slug ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Division -->
                    <div style="margin-bottom: 1.25rem;">
                        <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">Division</label>
                        <select name="division_id" id="marketDivSelect" class="glass-input" style="font-size: 0.88rem; padding: 0.55rem 0.8rem;">
                            <option value="">All Divisions</option>
                            @foreach ($divisions as $d)
                                <option value="{{ $d->id }}" {{ request('division_id') == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- District -->
                    <div style="margin-bottom: 1.25rem;">
                        <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">District</label>
                        <select name="district_id" id="marketDistSelect" class="glass-input" style="font-size: 0.88rem; padding: 0.55rem 0.8rem;">
                            <option value="">All Districts</option>
                            @foreach ($districts as $dst)
                                <option value="{{ $dst->id }}" data-division-id="{{ $dst->parent_id }}" {{ request('district_id') == $dst->id ? 'selected' : '' }}>
                                    {{ $dst->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Gender -->
                    <div style="margin-bottom: 1.25rem;">
                        <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">Caregiver Gender</label>
                        <select name="gender" class="glass-input" style="font-size: 0.88rem; padding: 0.55rem 0.8rem;">
                            <option value="">Any Gender</option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female Caregiver</option>
                            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male Caregiver</option>
                        </select>
                    </div>

                    <!-- Minimum Experience -->
                    <div style="margin-bottom: 1.25rem;">
                        <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">Minimum Experience</label>
                        <select name="experience" class="glass-input" style="font-size: 0.88rem; padding: 0.55rem 0.8rem;">
                            <option value="">Any Experience</option>
                            <option value="2" {{ request('experience') == '2' ? 'selected' : '' }}>2+ Years</option>
                            <option value="4" {{ request('experience') == '4' ? 'selected' : '' }}>4+ Years</option>
                            <option value="6" {{ request('experience') == '6' ? 'selected' : '' }}>6+ Years</option>
                        </select>
                    </div>

                    <!-- Daily Rate Range -->
                    <div style="margin-bottom: 1.25rem;">
                        <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">Max Daily Rate (৳)</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="e.g. 2500" class="glass-input" style="font-size: 0.88rem; padding: 0.55rem 0.8rem;">
                    </div>

                    <!-- Live Type -->
                    <div style="margin-bottom: 1.25rem;">
                        <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">Arrangement Type</label>
                        <select name="live_type" class="glass-input" style="font-size: 0.88rem; padding: 0.55rem 0.8rem;">
                            <option value="">Any</option>
                            <option value="live_out" {{ request('live_type') == 'live_out' ? 'selected' : '' }}>Day Shift (Live-Out)</option>
                            <option value="live_in" {{ request('live_type') == 'live_in' ? 'selected' : '' }}>24/7 (Live-In)</option>
                            <option value="both" {{ request('live_type') == 'both' ? 'selected' : '' }}>Flexible</option>
                        </select>
                    </div>

                    <!-- Availability -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; cursor: pointer;">
                            <input type="checkbox" name="available_only" value="1" {{ request('available_only') ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: var(--brand-primary);">
                            <span>Currently Available Only</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 0.9rem;">
                        Apply Filters
                    </button>
                </form>
            </aside>

            <!-- Main Results Area -->
            <div>
                <!-- Top controls bar -->
                <div class="glass-card" style="padding: 1rem 1.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <div style="font-size: 0.95rem; font-weight: 600; color: #0f172a;">
                        Showing <span style="color: var(--brand-primary); font-weight: 700;">{{ $caregivers->total() }}</span> Verified Caregivers
                    </div>

                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <label for="sortSelect" style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">Sort By:</label>
                        <select id="sortSelect" onchange="document.getElementById('sortInput').value=this.value; document.getElementById('filterForm').submit();" class="glass-input" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; width: auto;">
                            <option value="recommended" {{ request('sort') == 'recommended' ? 'selected' : '' }}>Recommended</option>
                            <option value="highest_rated" {{ request('sort') == 'highest_rated' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="most_experienced" {{ request('sort') == 'most_experienced' ? 'selected' : '' }}>Most Experienced</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Daily Rate: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Daily Rate: High to Low</option>
                        </select>
                        <input type="hidden" name="sort" id="sortInput" form="filterForm" value="{{ request('sort', 'recommended') }}">
                    </div>
                </div>

                <!-- Grid of Caregivers -->
                @if ($caregivers->isNotEmpty())
                    <div class="caregivers-grid" style="display: grid; gap: 1.5rem; margin-bottom: 2.5rem;">
                        @foreach ($caregivers as $caregiver)
                            <div class="glass-card glass-card-hover" style="display: flex; flex-direction: column; position: relative;">
                                @if ($caregiver->is_featured)
                                    <div style="position: absolute; top: -10px; right: 15px;">
                                        <x-badge tone="warning">★ Featured</x-badge>
                                    </div>
                                @endif

                                <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1rem;">
                                    <div style="position: relative;">
                                        <img src="{{ $caregiver->avatarUrl() }}" alt="{{ $caregiver->user->name }}" style="width: 68px; height: 68px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
                                        <div style="position: absolute; bottom: 0; right: 0; width: 20px; height: 20px; border-radius: 50%; background: #10b981; border: 2px solid #fff; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 10px;" title="Government NID Verified">
                                            ✓
                                        </div>
                                    </div>

                                    <div style="flex: 1;">
                                        <div style="display: flex; align-items: center; justify-content: space-between;">
                                            <h3 style="font-size: 1.15rem; font-weight: 700; color: #0f172a;">{{ $caregiver->user->name }}</h3>
                                        </div>
                                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.2rem;">
                                            📍 {{ $caregiver->area?->name ?? $caregiver->city }}, {{ $caregiver->district?->name }}
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 0.4rem; margin-top: 0.35rem;">
                                            <x-star-rating :rating="$caregiver->rating_avg" />
                                            <span style="font-size: 0.82rem; font-weight: 700; color: #0f172a;">{{ number_format($caregiver->rating_avg, 1) }}</span>
                                            <span style="font-size: 0.75rem; color: var(--text-muted);">({{ $caregiver->rating_count }})</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Services & Details Tags -->
                                <div style="display: flex; flex-wrap: wrap; gap: 0.35rem; margin-bottom: 1rem;">
                                    @foreach ($caregiver->services->take(2) as $s)
                                        <span style="font-size: 0.72rem; background: rgba(10, 57, 74, 0.07); color: #0a394a; padding: 0.2rem 0.5rem; border-radius: var(--radius-sm); font-weight: 600;">
                                            {{ $s->name }}
                                        </span>
                                    @endforeach
                                    <span style="font-size: 0.72rem; background: rgba(16, 185, 129, 0.08); color: #065f46; padding: 0.2rem 0.5rem; border-radius: var(--radius-sm); font-weight: 600;">
                                        {{ $caregiver->years_experience }}y Exp
                                    </span>
                                    @if ($caregiver->employment_type)
                                        <span style="font-size: 0.72rem; background: rgba(148, 163, 184, 0.12); color: #475569; padding: 0.2rem 0.5rem; border-radius: var(--radius-sm); font-weight: 600;">
                                            {{ ucfirst(str_replace('_', ' ', $caregiver->employment_type)) }}
                                        </span>
                                    @endif
                                </div>

                                <p style="font-size: 0.86rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 1.25rem; flex: 1;">
                                    {{ Str::limit($caregiver->about, 95) }}
                                </p>

                                <!-- Rates & CTA Button -->
                                <div style="border-top: 1px solid rgba(226, 232, 240, 0.7); padding-top: 1rem; display: flex; align-items: center; justify-content: space-between;">
                                    <div>
                                        <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Rate</div>
                                        <div style="font-size: 1.15rem; font-weight: 800; color: #0f172a;">
                                            ৳{{ number_format($caregiver->daily_rate) }}<span style="font-size: 0.75rem; font-weight: 500; color: var(--text-muted);">/day</span>
                                        </div>
                                    </div>

                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="{{ route('marketplace.show', $caregiver->slug) }}" class="btn btn-primary btn-sm">
                                            Request Care
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div style="margin-top: 2rem;">
                        {{ $caregivers->links() }}
                    </div>
                @else
                    <div class="glass-card" style="text-align: center; padding: 4rem 2rem;">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">🔍</div>
                        <h3 style="font-size: 1.35rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">No Caregivers Matched</h3>
                        <p style="color: var(--text-secondary); max-width: 480px; margin: 0 auto 1.5rem auto;">
                            We couldn't find caregivers matching all your criteria. Try widening your filters or contact our Care Coordinator directly to source a specialized match.
                        </p>
                        <a href="{{ route('marketplace.index') }}" class="btn btn-secondary">Clear Filters</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const divSel = document.getElementById('marketDivSelect');
        const distSel = document.getElementById('marketDistSelect');

        if (divSel && distSel) {
            const distOptions = Array.from(distSel.querySelectorAll('option')).filter(opt => opt.value !== '');
            const initialDist = "{{ request('district_id') }}";

            function filterDistricts(reset) {
                const divId = divSel.value;
                const curDist = reset ? '' : (distSel.value || initialDist);

                distSel.innerHTML = '<option value="">All Districts</option>';

                let found = false;
                distOptions.forEach(function (opt) {
                    if (!divId || String(opt.getAttribute('data-division-id')) === String(divId)) {
                        const clone = opt.cloneNode(true);
                        if (String(clone.value) === String(curDist)) {
                            clone.selected = true;
                            found = true;
                        }
                        distSel.appendChild(clone);
                    }
                });

                if (!found && !reset && curDist) {
                    distSel.value = '';
                }
            }

            divSel.addEventListener('change', function () {
                filterDistricts(true);
            });

            if (divSel.value) {
                filterDistricts(false);
            }
        }

        // Mobile filter accordion toggle
        const filterToggleBtn = document.getElementById('mobileFilterToggleBtn');
        const filterAside = document.getElementById('marketplaceFilterAside');
        const filterArrow = document.getElementById('mobileFilterArrow');

        if (filterToggleBtn && filterAside) {
            filterToggleBtn.addEventListener('click', function () {
                const isOpen = filterAside.classList.contains('mobile-visible');
                if (isOpen) {
                    filterAside.classList.remove('mobile-visible');
                    if (filterArrow) filterArrow.style.transform = 'rotate(0deg)';
                } else {
                    filterAside.classList.add('mobile-visible');
                    if (filterArrow) filterArrow.style.transform = 'rotate(180deg)';
                }
            });
        }
    });
    </script>
    @endpush
</x-layouts.guest>
