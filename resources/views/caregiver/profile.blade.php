<x-layouts.dashboard>
    <x-slot:title>Caregiver Profile & Hub — CareMate BD</x-slot:title>
    <x-slot:header>My Profile & Caregiver Hub</x-slot:header>
    <x-slot:subheading>Manage your profile completion, quick caregiver tools, service rates, and biography.</x-slot:subheading>

    @php
        $user = auth()->user();
        $completion = $caregiver->profileCompletion();
        $pct = $completion['percentage'];
        $itemsByKey = collect($completion['items'])->keyBy('key');
        $docCount = $caregiver->documents()->count();
        $progressColor = $pct >= 100 ? '#10b981' : ($pct >= 70 ? '#0d9488' : '#f59e0b');
    @endphp

    @push('styles')
    <style>
        .profile-page-wrap {
            max-width: 860px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1.15rem;
        }
        .profile-hero-card {
            background: linear-gradient(135deg, #0a394a 0%, #0f5268 100%);
            border-radius: 20px;
            padding: 1.35rem 1.4rem;
            color: #ffffff;
            box-shadow: 0 10px 28px rgba(10, 57, 74, 0.16);
            position: relative;
            overflow: hidden;
        }
        .profile-hero-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .profile-hero-user {
            display: flex;
            align-items: center;
            gap: 0.95rem;
            min-width: 0;
        }
        .profile-hero-avatar {
            width: 66px;
            height: 66px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.85);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            flex-shrink: 0;
            background: #ffffff;
        }
        .profile-completion-box {
            margin-top: 1.1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 14px;
            padding: 0.9rem 1rem;
            backdrop-filter: blur(10px);
        }
        .profile-progress-track {
            width: 100%;
            height: 10px;
            background: rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            overflow: hidden;
            margin: 0.5rem 0;
        }
        .profile-progress-fill {
            height: 100%;
            border-radius: 999px;
            transition: width 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .missing-checklist-card {
            background: #fffbeb;
            border: 1.5px solid #fde68a;
            border-radius: 18px;
            padding: 1.1rem 1.2rem;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.06);
        }
        .missing-item-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.65rem 0.8rem;
            background: #ffffff;
            border: 1px solid rgba(251, 191, 36, 0.35);
            border-radius: 12px;
        }
        .profile-hub-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 18px;
            padding: 1.15rem 1.2rem;
            box-shadow: 0 4px 14px rgba(10, 57, 74, 0.04);
        }
        .profile-hub-grid {
            display: grid !important;
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            gap: 0.65rem !important;
        }
        .profile-hub-tile {
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
        .profile-hub-tile:hover {
            background: #f0fdfa;
            border-color: rgba(13, 148, 136, 0.35);
            transform: translateY(-1px);
        }
        .profile-hub-icon {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.1rem;
        }
        .profile-section-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 18px;
            padding: 1.35rem 1.4rem;
            box-shadow: 0 4px 14px rgba(10, 57, 74, 0.04);
            margin-bottom: 1rem;
            scroll-margin-top: 80px;
        }
        .profile-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.65rem;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            padding-bottom: 0.75rem;
            margin-bottom: 1.1rem;
            flex-wrap: wrap;
        }
        .section-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.22rem 0.6rem;
            border-radius: 999px;
        }
        .section-status-complete {
            background: #d1fae5;
            color: #065f46;
        }
        .section-status-incomplete {
            background: #fef3c7;
            color: #92400e;
        }
        @media (max-width: 900px) {
            .profile-hub-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 0.55rem !important;
            }
            .profile-hero-card {
                padding: 1.1rem 1rem;
                border-radius: 18px;
            }
            .profile-section-card {
                padding: 1.1rem 0.95rem;
            }
            .missing-item-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            .missing-item-row .btn {
                width: 100%;
                justify-content: center;
                min-height: 38px;
            }
        }
    </style>
    @endpush

    <div class="profile-page-wrap">
        <!-- 1. PROFILE HERO & COMPLETION PROGRESS CARD -->
        <div class="profile-hero-card">
            <div class="profile-hero-top">
                <div class="profile-hero-user">
                    <img id="caregiverHeroAvatar" src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="profile-hero-avatar">
                    <div style="min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                            <h2 style="font-size: 1.25rem; font-weight: 800; color: #ffffff; margin: 0; line-height: 1.2;">{{ $user->name }}</h2>
                            <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; padding: 0.2rem 0.55rem; border-radius: 999px; background: rgba(255,255,255,0.18); color: #ffffff;">
                                {{ $caregiver->status->label() }}
                            </span>
                        </div>
                        <div style="font-size: 0.82rem; color: rgba(255, 255, 255, 0.82); margin-top: 0.2rem;">
                            {{ $user->phone ?: $user->email }} • {{ $caregiver->district?->name ?? 'Bangladesh' }}
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.45rem; margin-top: 0.45rem; flex-wrap: wrap;">
                            <span style="display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.73rem; font-weight: 700; padding: 0.18rem 0.55rem; border-radius: 999px; background: {{ $caregiver->is_available ? 'rgba(16, 185, 129, 0.25)' : 'rgba(244, 63, 94, 0.25)' }}; color: #ffffff;">
                                <span style="width: 7px; height: 7px; border-radius: 50%; background: {{ $caregiver->is_available ? '#34d399' : '#fb7185' }};"></span>
                                {{ $caregiver->is_available ? 'Available for Shifts' : 'Offline / Busy' }}
                            </span>
                            @if ($caregiver->status->value === 'approved' && $caregiver->slug)
                                <a href="{{ route('marketplace.show', $caregiver->slug) }}" target="_blank" style="font-size: 0.73rem; font-weight: 700; color: #5eead4; text-decoration: none;">
                                    View Public Profile ↗
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                @if (in_array($caregiver->status->value, ['draft', 'changes_required']))
                    <a href="{{ route('caregiver.register') }}" class="btn btn-mint btn-sm" style="font-weight: 800; white-space: nowrap;">
                        Complete 9-Step Verification →
                    </a>
                @endif
            </div>

            <!-- Profile Completion Bar -->
            <div class="profile-completion-box">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                    <div style="font-size: 0.84rem; font-weight: 700; color: #ffffff;">
                        Profile Completion Status
                    </div>
                    <div style="font-size: 0.95rem; font-weight: 800; color: {{ $pct >= 100 ? '#6ee7b7' : '#fde047' }};">
                        {{ $pct }}% Complete ({{ $completion['completed_count'] }}/{{ $completion['total_count'] }})
                    </div>
                </div>
                <div class="profile-progress-track">
                    <div class="profile-progress-fill" style="width: {{ $pct }}%; background: {{ $progressColor }};"></div>
                </div>
                <div style="font-size: 0.76rem; color: rgba(255, 255, 255, 0.86); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.4rem;">
                    @if ($pct >= 100)
                        <span>✓ All profile sections & verification documents are complete!</span>
                    @else
                        <span>⚠️ Your profile is {{ 100 - $pct }}% incomplete. Complete the items below to get more family bookings.</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- 2. INCOMPLETE PROFILE CHECKLIST PROMPT (SHOWN WHEN < 100%) -->
        @if (count($completion['missing_items']) > 0)
            <div class="missing-checklist-card">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; margin-bottom: 0.75rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="font-size: 1.15rem;">⚡</span>
                        <div>
                            <h3 style="font-size: 0.95rem; font-weight: 800; color: #92400e; margin: 0;">Complete Your Profile ({{ count($completion['missing_items']) }} {{ Str::plural('step', count($completion['missing_items'])) }} remaining)</h3>
                            <p style="font-size: 0.78rem; color: #b45309; margin: 0.1rem 0 0 0;">Families trust 100% completed profiles first. Tap any item to complete it now:</p>
                        </div>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach ($completion['missing_items'] as $missing)
                        <div class="missing-item-row">
                            <div style="min-width: 0;">
                                <div style="display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap;">
                                    <span style="font-weight: 800; font-size: 0.86rem; color: #0f172a;">{{ $missing['label'] }}</span>
                                    <span style="font-size: 0.68rem; font-weight: 800; padding: 0.12rem 0.45rem; border-radius: 999px; background: #fef3c7; color: #b45309;">+{{ $missing['weight'] }}%</span>
                                </div>
                                <div style="font-size: 0.76rem; color: #64748b; margin-top: 0.1rem;">{{ $missing['description'] ?? $missing['hint'] ?? '' }}</div>
                            </div>
                            <a href="{{ $missing['action_url'] ?? $missing['url'] ?? route('caregiver.profile') }}" class="btn btn-primary btn-sm" style="padding: 0.4rem 0.85rem; font-size: 0.78rem; font-weight: 700; white-space: nowrap; flex-shrink: 0;">
                                {{ $missing['action_label'] ?? 'Complete Now' }} →
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 3. ORGANIZED CAREGIVER TOOLS & ACCOUNT HUB (All menu features in one place) -->
        <div class="profile-hub-card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.85rem;">
                <div>
                    <h3 style="font-size: 0.98rem; font-weight: 800; color: #0f172a; margin: 0;">Caregiver Menu & Quick Tools</h3>
                    <p style="font-size: 0.76rem; color: #64748b; margin: 0.1rem 0 0 0;">Access all your schedule, verification documents, jobs, and account settings.</p>
                </div>
            </div>

            <div class="profile-hub-grid">
                <a href="{{ route('caregiver.availability') }}" class="profile-hub-tile">
                    <div class="profile-hub-icon" style="background: rgba(13, 148, 136, 0.12); color: #0d9488;">📅</div>
                    <div style="min-width: 0;">
                        <div style="font-weight: 800; font-size: 0.82rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Availability</div>
                        <div style="font-size: 0.7rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Shifts & status</div>
                    </div>
                </a>

                <a href="{{ route('caregiver.documents') }}" class="profile-hub-tile">
                    <div class="profile-hub-icon" style="background: rgba(14, 165, 233, 0.12); color: #0284c7;">🪪</div>
                    <div style="min-width: 0;">
                        <div style="font-weight: 800; font-size: 0.82rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">NID & Docs</div>
                        <div style="font-size: 0.7rem; color: {{ $docCount > 0 ? '#059669' : '#d97706' }}; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $docCount > 0 ? $docCount.' uploaded' : 'Upload NID' }}
                        </div>
                    </div>
                </a>

                <a href="{{ route('caregiver.requests.index') }}" class="profile-hub-tile">
                    <div class="profile-hub-icon" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">📩</div>
                    <div style="min-width: 0;">
                        <div style="font-weight: 800; font-size: 0.82rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Care Invites</div>
                        <div style="font-size: 0.7rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Family requests</div>
                    </div>
                </a>

                <a href="{{ route('caregiver.jobs.index') }}" class="profile-hub-tile">
                    <div class="profile-hub-icon" style="background: rgba(99, 102, 241, 0.12); color: #4f46e5;">💼</div>
                    <div style="min-width: 0;">
                        <div style="font-weight: 800; font-size: 0.82rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">My Jobs</div>
                        <div style="font-size: 0.7rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Active bookings</div>
                    </div>
                </a>

                <a href="{{ route('caregiver.earnings') }}" class="profile-hub-tile">
                    <div class="profile-hub-icon" style="background: rgba(16, 185, 129, 0.12); color: #059669;">💰</div>
                    <div style="min-width: 0;">
                        <div style="font-weight: 800; font-size: 0.82rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Earnings</div>
                        <div style="font-size: 0.7rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Payouts & wallet</div>
                    </div>
                </a>

                <a href="{{ route('caregiver.support.index') }}" class="profile-hub-tile">
                    <div class="profile-hub-icon" style="background: rgba(236, 72, 153, 0.12); color: #db2777;">💬</div>
                    <div style="min-width: 0;">
                        <div style="font-weight: 800; font-size: 0.82rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Support Desk</div>
                        <div style="font-size: 0.7rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">24/7 Care help</div>
                    </div>
                </a>

                <a href="{{ route('password.change') }}" class="profile-hub-tile">
                    <div class="profile-hub-icon" style="background: rgba(100, 116, 139, 0.12); color: #475569;">🔑</div>
                    <div style="min-width: 0;">
                        <div style="font-weight: 800; font-size: 0.82rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Security</div>
                        <div style="font-size: 0.7rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Change password</div>
                    </div>
                </a>

                <a href="{{ route('home') }}" class="profile-hub-tile">
                    <div class="profile-hub-icon" style="background: rgba(10, 57, 74, 0.1); color: #0a394a;">🌐</div>
                    <div style="min-width: 0;">
                        <div style="font-weight: 800; font-size: 0.82rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Visit Website</div>
                        <div style="font-size: 0.7rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Public home</div>
                    </div>
                </a>
            </div>

            <div style="margin-top: 0.8rem; padding-top: 0.75rem; border-top: 1px solid rgba(226, 232, 240, 0.8); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                <span style="font-size: 0.76rem; color: #64748b;">Signed in as <strong>{{ $user->email }}</strong></span>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" style="background: rgba(239, 68, 68, 0.08); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.22); border-radius: 10px; padding: 0.4rem 0.85rem; font-size: 0.78rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        <span>Sign Out / Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- 4. ORGANIZED PROFILE & RATES EDIT FORM -->
        <form method="POST" action="{{ route('caregiver.profile.update') }}" enctype="multipart/form-data">
            @csrf

            <!-- Section 1: Personal Information & Headshot -->
            @php
                $sec1Complete = ($itemsByKey['photo']['completed'] ?? false) && ($itemsByKey['personal']['completed'] ?? false);
            @endphp
            <div id="section-personal" class="profile-section-card">
                <div class="profile-section-header">
                    <div>
                        <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0;">1. Personal Identity & Headshot</h3>
                        <p style="font-size: 0.76rem; color: #64748b; margin: 0.1rem 0 0 0;">Your public photo, full name, phone, gender, and date of birth.</p>
                    </div>
                    <span class="section-status-pill {{ $sec1Complete ? 'section-status-complete' : 'section-status-incomplete' }}">
                        {{ $sec1Complete ? '✓ Complete' : '⚠️ Incomplete' }}
                    </span>
                </div>

                <!-- Avatar Preview + Upload -->
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem; padding: 0.85rem; background: #f8fafc; border-radius: 14px; border: 1px solid rgba(226, 232, 240, 0.85); flex-wrap: wrap;">
                    <img id="caregiverAvatarPreview" src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" style="width: 68px; height: 68px; border-radius: 50%; object-fit: cover; border: 3px solid #ffffff; box-shadow: 0 3px 10px rgba(0,0,0,0.08); flex-shrink: 0;">
                    <div style="flex: 1; min-width: 200px;">
                        <label class="form-label" style="margin-bottom: 0.3rem; font-size: 0.82rem;">Update Headshot Photo</label>
                        <input type="file" name="profile_photo" id="caregiverPhotoInput" accept="image/*" class="glass-input" style="font-size: 0.82rem; padding: 0.45rem 0.65rem;">
                        <p style="font-size: 0.72rem; color: var(--text-muted); margin: 0.25rem 0 0 0;">Supported: JPG, PNG, WebP (Max 5MB). Clear portrait photos build family trust.</p>
                    </div>
                </div>

                <div class="form-row-2col" style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.9rem; margin-bottom: 0.9rem;">
                    <div>
                        <label class="form-label">Full Name <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="glass-input">
                    </div>
                    <div>
                        <label class="form-label">Contact Phone <span style="color: #ef4444;">*</span></label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required class="glass-input">
                    </div>
                </div>

                <div class="form-row-3col" style="display: grid; grid-template-columns: 1.2fr 1fr 1fr; gap: 0.9rem;">
                    <div>
                        <label class="form-label">Email (Account Login)</label>
                        <input type="email" value="{{ $user->email }}" disabled class="glass-input" style="opacity: 0.7; cursor: not-allowed;">
                    </div>
                    <div>
                        @php
                            $genderVal = $caregiver->gender instanceof \BackedEnum ? $caregiver->gender->value : $caregiver->gender;
                        @endphp
                        <label class="form-label">Gender</label>
                        <select name="gender" class="glass-input">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $genderVal) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $genderVal) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $genderVal) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $caregiver->date_of_birth?->format('Y-m-d')) }}" class="glass-input">
                    </div>
                </div>
            </div>

            <!-- Section 2: Location & Service Territory -->
            @php
                $sec2Complete = $itemsByKey['location']['completed'] ?? false;
            @endphp
            <div id="section-location" class="profile-section-card">
                <div class="profile-section-header">
                    <div>
                        <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0;">2. Location & Service Territory</h3>
                        <p style="font-size: 0.76rem; color: #64748b; margin: 0.1rem 0 0 0;">Used to match you with nearby families looking for care.</p>
                    </div>
                    <span class="section-status-pill {{ $sec2Complete ? 'section-status-complete' : 'section-status-incomplete' }}">
                        {{ $sec2Complete ? '✓ Complete' : '⚠️ Incomplete' }}
                    </span>
                </div>

                <div style="margin-bottom: 0.9rem;">
                    <label class="form-label">Present Residential Address</label>
                    <input type="text" name="present_address" value="{{ old('present_address', $caregiver->present_address) }}" class="glass-input" placeholder="House #, Road #, Area/Sector">
                </div>

                <div class="form-row-3col" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.9rem; margin-bottom: 1.15rem;">
                    <div>
                        <label class="form-label">Home Division</label>
                        <select name="division_id" id="profileDivisionSelect" class="glass-input">
                            <option value="">Select Division</option>
                            @foreach ($divisions as $div)
                                <option value="{{ $div->id }}" {{ old('division_id', $caregiver->division_id) == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Home District</label>
                        <select name="district_id" id="profileDistrictSelect" class="glass-input">
                            <option value="">Select District</option>
                            @foreach ($districts as $dst)
                                <option value="{{ $dst->id }}" data-division-id="{{ $dst->parent_id }}" {{ old('district_id', $caregiver->district_id) == $dst->id ? 'selected' : '' }}>{{ $dst->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Home Thana / Area</label>
                        <select name="area_id" id="profileAreaSelect" class="glass-input">
                            <option value="">Select Area</option>
                            @foreach ($areas as $a)
                                <option value="{{ $a->id }}" data-district-id="{{ $a->parent_id }}" {{ old('area_id', $caregiver->area_id) == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @php
                    $profileServiceAreaIds = old('service_area_ids', $caregiver->serviceAreas->pluck('id')->toArray());
                @endphp
                <div style="background: rgba(13, 148, 136, 0.05); border: 1.5px solid rgba(13, 148, 136, 0.25); border-radius: 14px; padding: 1rem;">
                    <input type="hidden" name="service_areas_submitted" value="1">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.35rem; gap: 0.5rem;">
                        <label class="form-label" style="margin: 0; color: #0a394a; font-weight: 800;">📍 Preferred Service Areas (Where You Provide Care)</label>
                        <span style="font-size: 0.72rem; font-weight: 800; color: #0f766e;">{{ count((array) $profileServiceAreaIds) }} selected</span>
                    </div>
                    <p style="font-size: 0.75rem; color: #64748b; margin: 0 0 0.65rem 0;">Choose all areas/districts where you are available to travel for care shifts.</p>
                    <div style="max-height: 185px; overflow-y: auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 0.4rem;">
                        @foreach ($areas as $ar)
                            <label style="display: flex; align-items: center; gap: 0.4rem; padding: 0.4rem 0.55rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 9px; font-size: 0.78rem; font-weight: 600; cursor: pointer;">
                                <input type="checkbox" name="service_area_ids[]" value="{{ $ar->id }}" {{ in_array($ar->id, (array) $profileServiceAreaIds) ? 'checked' : '' }} style="accent-color: #0d9488;">
                                <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $ar->name }}</span>
                            </label>
                        @endforeach
                        @foreach ($districts as $dst)
                            <label style="display: flex; align-items: center; gap: 0.4rem; padding: 0.4rem 0.55rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 9px; font-size: 0.78rem; font-weight: 600; cursor: pointer;">
                                <input type="checkbox" name="service_area_ids[]" value="{{ $dst->id }}" {{ in_array($dst->id, (array) $profileServiceAreaIds) ? 'checked' : '' }} style="accent-color: #0d9488;">
                                <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $dst->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Section 3: Service Rates & Work Arrangement -->
            @php
                $sec3Complete = $itemsByKey['rates']['completed'] ?? false;
            @endphp
            <div id="section-rates" class="profile-section-card">
                <div class="profile-section-header">
                    <div>
                        <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0;">3. Service Rates & Shift Preferences</h3>
                        <p style="font-size: 0.76rem; color: #64748b; margin: 0.1rem 0 0 0;">Set your daily, weekly, and monthly rates in BDT (৳).</p>
                    </div>
                    <span class="section-status-pill {{ $sec3Complete ? 'section-status-complete' : 'section-status-incomplete' }}">
                        {{ $sec3Complete ? '✓ Complete' : '⚠️ Incomplete' }}
                    </span>
                </div>

                <div class="form-row-3col" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.9rem; margin-bottom: 0.9rem;">
                    <div>
                        <label class="form-label">Daily Rate (BDT ৳) <span style="color: #ef4444;">*</span></label>
                        <input type="number" name="daily_rate" value="{{ old('daily_rate', (int)$caregiver->daily_rate) }}" required min="200" step="50" class="glass-input">
                    </div>
                    <div>
                        <label class="form-label">Weekly Rate (BDT ৳)</label>
                        <input type="number" name="weekly_rate" value="{{ old('weekly_rate', (int)$caregiver->weekly_rate) }}" min="1000" step="100" class="glass-input">
                    </div>
                    <div>
                        <label class="form-label">Monthly Rate (BDT ৳)</label>
                        <input type="number" name="monthly_rate" value="{{ old('monthly_rate', (int)$caregiver->monthly_rate) }}" min="3000" step="500" class="glass-input">
                    </div>
                </div>

                @php
                    $prefShiftVal = $caregiver->preferred_shift instanceof \BackedEnum ? $caregiver->preferred_shift->value : $caregiver->preferred_shift;
                    $workArrVal = $caregiver->work_arrangement instanceof \BackedEnum ? $caregiver->work_arrangement->value : $caregiver->work_arrangement;
                @endphp
                <div class="form-row-2col" style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.9rem;">
                    <div>
                        <label class="form-label">Preferred Shift <span style="color: #ef4444;">*</span></label>
                        <select name="preferred_shift" required class="glass-input">
                            @foreach (['day' => 'Day Shift (8-12 Hours)', 'night' => 'Night Shift (10-12 Hours)', '24hr' => '24-Hour Full Day', 'live_in' => 'Live-in Care', 'flexible' => 'Flexible / Any Shift'] as $val => $lbl)
                                <option value="{{ $val }}" {{ old('preferred_shift', $prefShiftVal) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Work Arrangement <span style="color: #ef4444;">*</span></label>
                        <select name="work_arrangement" required class="glass-input">
                            @foreach (['live_out' => 'Live-out (Commute Daily)', 'live_in' => 'Live-in (Stay at Patient Residence)', 'both' => 'Both (Live-in or Live-out)'] as $val => $lbl)
                                <option value="{{ $val }}" {{ old('work_arrangement', $workArrVal) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 4: Experience, Biography & Care Categories -->
            @php
                $sec4Complete = $itemsByKey['experience']['completed'] ?? false;
            @endphp
            <div id="section-experience" class="profile-section-card">
                <div class="profile-section-header">
                    <div>
                        <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0;">4. Experience, Biography & Care Categories</h3>
                        <p style="font-size: 0.76rem; color: #64748b; margin: 0.1rem 0 0 0;">Highlight your clinical training, spoken languages, and care specialties.</p>
                    </div>
                    <span class="section-status-pill {{ $sec4Complete ? 'section-status-complete' : 'section-status-incomplete' }}">
                        {{ $sec4Complete ? '✓ Complete' : '⚠️ Incomplete' }}
                    </span>
                </div>

                <div class="form-row-2col" style="display: grid; grid-template-columns: 140px 1fr; gap: 0.9rem; margin-bottom: 0.9rem;">
                    <div>
                        <label class="form-label">Experience (Yrs) <span style="color: #ef4444;">*</span></label>
                        <input type="number" name="years_experience" value="{{ old('years_experience', $caregiver->years_experience) }}" required min="0" max="45" class="glass-input">
                    </div>
                    <div>
                        <label class="form-label">Qualification Summary</label>
                        <input type="text" name="qualification_summary" value="{{ old('qualification_summary', $caregiver->qualification_summary ?? $caregiver->education_qualification) }}" placeholder="e.g., B.Sc in Nursing, Certified Geriatric Aide" class="glass-input">
                    </div>
                </div>

                <div style="margin-bottom: 0.9rem;">
                    <label class="form-label">Languages Spoken</label>
                    <div style="display: flex; gap: 1.25rem; margin-top: 0.35rem; flex-wrap: wrap;">
                        @php $currentLangs = old('languages', is_array($caregiver->languages) ? $caregiver->languages : ['Bengali']); @endphp
                        @foreach (['Bengali', 'English', 'Hindi'] as $lang)
                            <label style="display: flex; align-items: center; gap: 0.45rem; font-size: 0.88rem; cursor: pointer; font-weight: 600;">
                                <input type="checkbox" name="languages[]" value="{{ $lang }}" {{ in_array($lang, (array) $currentLangs) ? 'checked' : '' }}>
                                <span>{{ $lang }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div style="margin-bottom: 1.1rem;">
                    <label class="form-label">Public Biography & Care Philosophy</label>
                    <textarea name="bio" rows="4" class="glass-input" placeholder="Describe your background, patient care experience, and clinical skills...">{{ old('bio', $caregiver->bio ?? $caregiver->about) }}</textarea>
                </div>

                <div>
                    <label class="form-label">Care Categories Offered</label>
                    <div class="services-checkbox-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem; margin-top: 0.4rem;">
                        @php
                            $selectedCats = old('service_categories', $caregiver->services->pluck('id')->toArray());
                            $availableServices = $services ?? $serviceCategories ?? collect();
                        @endphp
                        @foreach ($availableServices as $cat)
                            <label style="display: flex; align-items: center; gap: 0.65rem; padding: 0.65rem 0.8rem; border-radius: 12px; background: #f8fafc; border: 1px solid rgba(226, 232, 240, 0.9); cursor: pointer;">
                                <input type="checkbox" name="service_categories[]" value="{{ $cat->id }}" {{ in_array($cat->id, (array) $selectedCats) ? 'checked' : '' }}>
                                <span style="font-weight: 600; font-size: 0.86rem; color: #0f172a;">{{ $cat->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-actions-footer" style="margin-top: 1.25rem; padding-top: 1.1rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                        Save Profile & Service Rates
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Live photo preview
        const photoInput = document.getElementById('caregiverPhotoInput');
        const avatarPreview = document.getElementById('caregiverAvatarPreview');
        const heroAvatar = document.getElementById('caregiverHeroAvatar');
        if (photoInput && avatarPreview) {
            photoInput.addEventListener('change', function (e) {
                const file = e.target.files && e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (ev) {
                        avatarPreview.src = ev.target.result;
                        if (heroAvatar) heroAvatar.src = ev.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Dynamic Cascading Location Dropdowns (Division -> District -> Area)
        const divSel = document.getElementById('profileDivisionSelect');
        const distSel = document.getElementById('profileDistrictSelect');
        const areaSel = document.getElementById('profileAreaSelect');

        if (divSel && distSel && areaSel) {
            const distOptions = Array.from(distSel.querySelectorAll('option')).filter(opt => opt.value !== '');
            const areaOptions = Array.from(areaSel.querySelectorAll('option')).filter(opt => opt.value !== '');

            const initialDist = "{{ old('district_id', $caregiver->district_id) }}";
            const initialArea = "{{ old('area_id', $caregiver->area_id) }}";

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
