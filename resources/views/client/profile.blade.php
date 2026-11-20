<x-layouts.dashboard>
    <x-slot:title>Family Profile & Hub — CareMate BD</x-slot:title>
    <x-slot:header>Account & Family Profile</x-slot:header>
    <x-slot:subheading>Manage your profile completion, quick family portal tools, residence address, and emergency contacts.</x-slot:subheading>

    @php
        $user = auth()->user();
        $completion = $client->profileCompletion();
        $pct = $completion['percentage'];
        $itemsByKey = collect($completion['items'])->keyBy('key');
        $progressColor = $pct >= 100 ? '#10b981' : ($pct >= 70 ? '#0d9488' : '#f59e0b');
    @endphp

    @push('styles')
    <style>
        .client-profile-wrap {
            max-width: 820px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1.15rem;
        }
        .client-hero-card {
            background: linear-gradient(135deg, #0a394a 0%, #0f5268 100%);
            border-radius: 20px;
            padding: 1.35rem 1.4rem;
            color: #ffffff;
            box-shadow: 0 10px 28px rgba(10, 57, 74, 0.16);
        }
        .client-hub-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 18px;
            padding: 1.15rem 1.2rem;
            box-shadow: 0 4px 14px rgba(10, 57, 74, 0.04);
        }
        .client-hub-grid {
            display: grid !important;
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            gap: 0.65rem !important;
        }
        .client-hub-tile {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.75rem 0.8rem;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid rgba(226, 232, 240, 0.9);
            text-decoration: none;
            color: #0f172a;
            transition: all 0.18s ease;
            min-width: 0;
        }
        .client-hub-tile:hover {
            background: #f0fdfa;
            border-color: rgba(13, 148, 136, 0.35);
            transform: translateY(-1px);
        }
        .client-section-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 18px;
            padding: 1.35rem 1.4rem;
            box-shadow: 0 4px 14px rgba(10, 57, 74, 0.04);
            margin-bottom: 1rem;
            scroll-margin-top: 80px;
        }
        @media (max-width: 900px) {
            .client-hub-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 0.55rem !important;
            }
            .client-hero-card,
            .client-section-card {
                padding: 1.1rem 0.95rem;
            }
        }
    </style>
    @endpush

    <div class="client-profile-wrap">
        <!-- 1. PROFILE HERO & COMPLETION BAR -->
        <div class="client-hero-card">
            <div style="display: flex; align-items: center; gap: 0.95rem;">
                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 3px solid rgba(255,255,255,0.85); background: #ffffff; flex-shrink: 0;">
                <div style="min-width: 0;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                        <h2 style="font-size: 1.25rem; font-weight: 800; color: #ffffff; margin: 0;">{{ $user->name }}</h2>
                        <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; padding: 0.2rem 0.55rem; border-radius: 999px; background: rgba(255,255,255,0.18); color: #ffffff;">Family Account</span>
                    </div>
                    <div style="font-size: 0.82rem; color: rgba(255, 255, 255, 0.82); margin-top: 0.2rem;">
                        {{ $user->phone ?: $user->email }}
                    </div>
                </div>
            </div>

            <div style="margin-top: 1.05rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.18); border-radius: 14px; padding: 0.85rem 1rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                    <span style="font-size: 0.84rem; font-weight: 700; color: #ffffff;">Profile Completion</span>
                    <span style="font-size: 0.95rem; font-weight: 800; color: {{ $pct >= 100 ? '#6ee7b7' : '#fde047' }};">{{ $pct }}% Complete</span>
                </div>
                <div style="width: 100%; height: 9px; background: rgba(255, 255, 255, 0.18); border-radius: 999px; overflow: hidden; margin: 0.45rem 0;">
                    <div style="width: {{ $pct }}%; height: 100%; background: {{ $progressColor }}; border-radius: 999px;"></div>
                </div>
                <div style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.85);">
                    @if ($pct >= 100)
                        ✓ Your family profile and emergency details are 100% complete.
                    @else
                        ⚠️ Please complete your residence address and emergency contact below for faster caregiver booking.
                    @endif
                </div>
            </div>
        </div>

        @if (count($completion['missing_items']) > 0)
            <div style="background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 18px; padding: 1rem 1.15rem;">
                <div style="font-weight: 800; font-size: 0.92rem; color: #92400e; margin-bottom: 0.6rem;">
                    ⚡ Complete Your Family Profile ({{ count($completion['missing_items']) }} remaining)
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach ($completion['missing_items'] as $missing)
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; padding: 0.6rem 0.8rem; background: #ffffff; border: 1px solid rgba(251, 191, 36, 0.35); border-radius: 12px; flex-wrap: wrap;">
                            <div>
                                <div style="font-weight: 800; font-size: 0.84rem; color: #0f172a;">{{ $missing['label'] }} <span style="font-size: 0.68rem; color: #b45309; background: #fef3c7; padding: 0.1rem 0.4rem; border-radius: 999px;">+{{ $missing['weight'] }}%</span></div>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $missing['description'] }}</div>
                            </div>
                            <a href="{{ $missing['action_url'] }}" class="btn btn-primary btn-sm" style="padding: 0.38rem 0.8rem; font-size: 0.76rem; font-weight: 700;">{{ $missing['action_label'] }} →</a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 2. FAMILY PORTAL QUICK MENU HUB -->
        <div class="client-hub-card">
            <div style="margin-bottom: 0.85rem;">
                <h3 style="font-size: 0.98rem; font-weight: 800; color: #0f172a; margin: 0;">Family Portal Menu & Tools</h3>
                <p style="font-size: 0.76rem; color: #64748b; margin: 0.1rem 0 0 0;">Quick access to all caregiver requests, bookings, saved profiles, and support.</p>
            </div>

            <div class="client-hub-grid">
                <a href="{{ route('marketplace.index') }}" class="client-hub-tile">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(13, 148, 136, 0.12); display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex-shrink: 0;">🔍</div>
                    <div style="min-width: 0;">
                        <div style="font-weight: 800; font-size: 0.82rem; color: #0f172a;">Find Care</div>
                        <div style="font-size: 0.7rem; color: #64748b;">Browse verified</div>
                    </div>
                </a>
                <a href="{{ route('client.requests.index') }}" class="client-hub-tile">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(14, 165, 233, 0.12); display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex-shrink: 0;">📋</div>
                    <div style="min-width: 0;">
                        <div style="font-weight: 800; font-size: 0.82rem; color: #0f172a;">My Requests</div>
                        <div style="font-size: 0.7rem; color: #64748b;">Care inquiries</div>
                    </div>
                </a>
                <a href="{{ route('client.bookings.index') }}" class="client-hub-tile">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(99, 102, 241, 0.12); display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex-shrink: 0;">📅</div>
                    <div style="min-width: 0;">
                        <div style="font-weight: 800; font-size: 0.82rem; color: #0f172a;">My Bookings</div>
                        <div style="font-size: 0.7rem; color: #64748b;">Active & past</div>
                    </div>
                </a>
                <a href="{{ route('client.favorites') }}" class="client-hub-tile">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(244, 63, 94, 0.12); display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex-shrink: 0;">❤️</div>
                    <div style="min-width: 0;">
                        <div style="font-weight: 800; font-size: 0.82rem; color: #0f172a;">Saved</div>
                        <div style="font-size: 0.7rem; color: #64748b;">Shortlisted</div>
                    </div>
                </a>
                <a href="{{ route('client.support.index') }}" class="client-hub-tile">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(245, 158, 11, 0.12); display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex-shrink: 0;">💬</div>
                    <div style="min-width: 0;">
                        <div style="font-weight: 800; font-size: 0.82rem; color: #0f172a;">Support Desk</div>
                        <div style="font-size: 0.7rem; color: #64748b;">24/7 Care help</div>
                    </div>
                </a>
                <a href="{{ route('password.change') }}" class="client-hub-tile">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(100, 116, 139, 0.12); display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex-shrink: 0;">🔑</div>
                    <div style="min-width: 0;">
                        <div style="font-weight: 800; font-size: 0.82rem; color: #0f172a;">Security</div>
                        <div style="font-size: 0.7rem; color: #64748b;">Password</div>
                    </div>
                </a>
                <a href="{{ route('home') }}" class="client-hub-tile">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(10, 57, 74, 0.1); display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex-shrink: 0;">🌐</div>
                    <div style="min-width: 0;">
                        <div style="font-weight: 800; font-size: 0.82rem; color: #0f172a;">Website</div>
                        <div style="font-size: 0.7rem; color: #64748b;">Public home</div>
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0; display: contents;">
                    @csrf
                    <button type="submit" class="client-hub-tile" style="width: 100%; cursor: pointer; text-align: left; background: rgba(239, 68, 68, 0.05); border-color: rgba(239, 68, 68, 0.2);">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(239, 68, 68, 0.12); display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex-shrink: 0;">🚪</div>
                        <div style="min-width: 0;">
                            <div style="font-weight: 800; font-size: 0.82rem; color: #dc2626;">Logout</div>
                            <div style="font-size: 0.7rem; color: #ef4444;">Sign out</div>
                        </div>
                    </button>
                </form>
            </div>
        </div>

        <!-- 3. PROFILE & ADDRESS FORM -->
        <form method="POST" action="{{ route('client.profile.update') }}" enctype="multipart/form-data">
            @csrf

            <div id="client-personal" class="client-section-card">
                <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.55rem;">
                    1. Personal Profile
                </h3>

                <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1.25rem; flex-wrap: wrap;">
                    <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}" style="width: 68px; height: 68px; border-radius: 50%; object-fit: cover;">
                    <div style="flex: 1; min-width: 200px;">
                        <label class="form-label">Update Profile Picture</label>
                        <input type="file" name="profile_photo" accept="image/*" class="glass-input" style="font-size: 0.82rem;">
                    </div>
                </div>

                <div style="margin-bottom: 0.9rem;">
                    <label class="form-label">Full Name <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="glass-input">
                </div>

                <div class="form-row-2col" style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.9rem;">
                    <div>
                        <label class="form-label">Email Address (Read-only)</label>
                        <input type="email" value="{{ auth()->user()->email }}" disabled class="glass-input" style="opacity: 0.7; cursor: not-allowed;">
                    </div>
                    <div>
                        <label class="form-label">Contact Phone <span style="color: #ef4444;">*</span></label>
                        <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required class="glass-input">
                    </div>
                </div>
            </div>

            <div id="client-address" class="client-section-card">
                <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.55rem;">
                    2. Care Residence Address
                </h3>

                <div style="margin-bottom: 0.9rem;">
                    <label class="form-label">Street Address & Flat # <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="present_address" value="{{ old('present_address', $client->present_address) }}" required class="glass-input">
                </div>

                <div class="form-row-3col" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.9rem;">
                    <div>
                        <label class="form-label">Division</label>
                        <select name="division_id" id="profileDivSelect" class="glass-input">
                            <option value="">Select Division</option>
                            @foreach ($divisions as $div)
                                <option value="{{ $div->id }}" {{ old('division_id', $client->division_id) == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">District</label>
                        <select name="district_id" id="profileDistSelect" class="glass-input">
                            <option value="">Select District</option>
                            @foreach ($districts as $dst)
                                <option value="{{ $dst->id }}" data-division-id="{{ $dst->parent_id }}" {{ old('district_id', $client->district_id) == $dst->id ? 'selected' : '' }}>{{ $dst->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Area / Thana</label>
                        <select name="area_id" id="profileAreaSelect" class="glass-input">
                            <option value="">Select Area</option>
                            @foreach ($areas as $a)
                                <option value="{{ $a->id }}" data-district-id="{{ $a->parent_id }}" {{ old('area_id', $client->area_id) == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div id="client-emergency" class="client-section-card">
                <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.55rem;">
                    3. Emergency Family Contact
                </h3>

                <div class="form-row-3col" style="display: grid; grid-template-columns: 1.2fr 1fr 1fr; gap: 0.9rem; margin-bottom: 1.25rem;">
                    <div>
                        <label class="form-label">Contact Name</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $client->emergency_contact_name) }}" class="glass-input">
                    </div>
                    <div>
                        <label class="form-label">Emergency Phone</label>
                        <input type="tel" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $client->emergency_contact_phone) }}" class="glass-input">
                    </div>
                    <div>
                        <label class="form-label">Relationship</label>
                        <input type="text" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $client->emergency_contact_relationship) }}" class="glass-input">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                    Save Updated Profile
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const divSel = document.getElementById('profileDivSelect');
        const distSel = document.getElementById('profileDistSelect');
        const areaSel = document.getElementById('profileAreaSelect');

        if (divSel && distSel && areaSel) {
            const distOptions = Array.from(distSel.querySelectorAll('option')).filter(opt => opt.value !== '');
            const areaOptions = Array.from(areaSel.querySelectorAll('option')).filter(opt => opt.value !== '');

            const initialDist = "{{ old('district_id', $client->district_id) }}";
            const initialArea = "{{ old('area_id', $client->area_id) }}";

            function filterDistricts(reset) {
                const divId = divSel.value;
                const curDist = reset ? '' : (distSel.value || initialDist);

                distSel.innerHTML = '<option value="">' + (divId ? 'Select District' : 'Select Division First') + '</option>';

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

                filterAreas(reset);
            }

            function filterAreas(reset) {
                const distId = distSel.value;
                const curArea = reset ? '' : (areaSel.value || initialArea);

                areaSel.innerHTML = '<option value="">' + (distId ? 'Select Area' : 'Select District First') + '</option>';

                areaOptions.forEach(function (opt) {
                    if (!distId || String(opt.getAttribute('data-district-id')) === String(distId)) {
                        const clone = opt.cloneNode(true);
                        if (String(clone.value) === String(curArea)) {
                            clone.selected = true;
                        }
                        areaSel.appendChild(clone);
                    }
                });
            }

            divSel.addEventListener('change', function () {
                filterDistricts(true);
            });

            distSel.addEventListener('change', function () {
                filterAreas(true);
            });

            filterDistricts(false);
        }
    });
    </script>
    @endpush
</x-layouts.dashboard>
