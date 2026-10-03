<x-layouts.dashboard>
    <x-slot:title>{{ $caregiver->user->name }} — Caregiver Dossier & Management | CareMate BD</x-slot:title>
    <x-slot:header>Caregiver Profile: {{ $caregiver->user->name }}</x-slot:header>
    <x-slot:subheading>Comprehensive administrator profile overview, marketplace ranking, and service details.</x-slot:subheading>

    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <a href="{{ route('admin.caregivers.index') }}" class="btn btn-secondary btn-sm">
                ← Back to All Caregivers
            </a>
            <x-badge :tone="$caregiver->status->badgeTone()">{{ $caregiver->status->label() }}</x-badge>
            @if ($caregiver->is_featured)
                <span class="badge" style="background: rgba(245, 158, 11, 0.15); color: #d97706; font-weight: 700; border: 1px solid rgba(245, 158, 11, 0.3);">
                    ★ Featured Top Provider
                </span>
            @endif
            @if ($caregiver->sort_order > 0)
                <span class="badge" style="background: rgba(21, 121, 142, 0.12); color: var(--brand-primary); font-weight: 700; border: 1px solid rgba(21, 121, 142, 0.25);">
                    Marketplace Rank #{{ $caregiver->sort_order }}
                </span>
            @endif
        </div>

        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('admin.caregivers.edit', $caregiver->id) }}" class="btn btn-primary btn-sm">
                ✏️ Edit Profile
            </a>
            <a href="{{ route('admin.applications.show', $caregiver->id) }}" class="btn btn-secondary btn-sm">
                🪪 Verification Dossier
            </a>
            @if ($caregiver->status === \App\Enums\CaregiverStatus::Published)
                <a href="{{ route('marketplace.show', $caregiver->slug) }}" target="_blank" class="btn btn-secondary btn-sm">
                    🌐 View Public Page ↗
                </a>
            @endif
            <form method="POST" action="{{ route('admin.caregivers.toggle-featured', $caregiver->id) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm" title="Toggle Featured Provider">
                    {{ $caregiver->is_featured ? '★ Unfeature' : '☆ Mark Featured' }}
                </button>
            </form>
            @if ($caregiver->status->value !== 'suspended')
                <form method="POST" action="{{ route('admin.caregivers.suspend', $caregiver->id) }}" onsubmit="return prompt('Reason for account suspension:') ? true : false;" style="display: inline;">
                    @csrf
                    <input type="hidden" name="reason" value="Administrative compliance review">
                    <button type="submit" class="btn btn-sm" style="background: rgba(244, 63, 94, 0.12); color: #e11d48; border: 1px solid rgba(244, 63, 94, 0.3);">
                        ⛔ Suspend
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.caregivers.reactivate', $caregiver->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm" style="background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.3);">
                        ✓ Reactivate
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Quick Stats Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
        <div class="glass-card" style="padding: 1.25rem;">
            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600; margin-bottom: 0.35rem;">Marketplace Position</div>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="font-size: 1.5rem; font-weight: 800; color: var(--brand-primary);">
                    {{ $caregiver->sort_order > 0 ? '#' . $caregiver->sort_order : 'Unranked' }}
                </div>
                <form method="POST" action="{{ route('admin.caregivers.sort-order', $caregiver->id) }}" style="display: flex; gap: 0.25rem;">
                    @csrf
                    <input type="number" name="sort_order" value="{{ $caregiver->sort_order }}" min="0" max="9999" style="width: 55px; padding: 0.2rem 0.4rem; font-size: 0.8rem; border-radius: var(--radius-sm); border: 1px solid rgba(203,213,225,0.8); text-align: center;">
                    <button type="submit" class="btn btn-secondary btn-sm" style="padding: 0.2rem 0.4rem; font-size: 0.72rem;">Set</button>
                </form>
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.35rem;">1 = Highest priority on marketplace</div>
        </div>

        <div class="glass-card" style="padding: 1.25rem;">
            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600; margin-bottom: 0.35rem;">Client Rating</div>
            <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                <span style="font-size: 1.6rem; font-weight: 800; color: #f59e0b;">★ {{ number_format($caregiver->rating_avg, 1) }}</span>
                <span style="font-size: 0.85rem; color: var(--text-muted);">({{ $caregiver->rating_count }} reviews)</span>
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.35rem;">From completed care bookings</div>
        </div>

        <div class="glass-card" style="padding: 1.25rem;">
            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600; margin-bottom: 0.35rem;">Daily Care Rate</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #059669;">
                ৳{{ number_format($caregiver->daily_rate) }} <span style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">/day</span>
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.35rem;">Hourly: ৳{{ number_format($caregiver->hourly_rate ?? 0) }}/hr</div>
        </div>

        <div class="glass-card" style="padding: 1.25rem;">
            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600; margin-bottom: 0.35rem;">Completed Bookings</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #0f172a;">
                {{ $caregiver->completed_jobs_count }} <span style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Assignments</span>
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.35rem;">Active Bookings: {{ $caregiver->bookings->where('status', 'in_progress')->count() }}</div>
        </div>
    </div>

    <!-- Main Profile Content Grid -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: flex-start;" class="profile-layout">
        <!-- Left Column: Professional Details, Bio & History -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            
            <!-- Bio & Professional Overview -->
            <div class="glass-card" style="padding: 2rem;">
                <div style="display: flex; gap: 1.5rem; align-items: flex-start; margin-bottom: 1.5rem;">
                    <img src="{{ $caregiver->avatarUrl() }}" alt="{{ $caregiver->user->name }}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #fff; box-shadow: var(--glass-shadow);">
                    <div style="flex: 1;">
                        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">{{ $caregiver->user->name }}</h2>
                        <div style="font-size: 0.95rem; font-weight: 600; color: var(--brand-primary); margin-bottom: 0.35rem;">
                            {{ $caregiver->caregiver_type }}
                        </div>
                        <div style="font-size: 0.85rem; color: var(--text-secondary);">
                            📍 {{ $caregiver->locationSummary() }} • {{ $caregiver->years_experience }} Years Practical Experience
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <h4 style="font-size: 0.9rem; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.5rem;">About</h4>
                    <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-primary); margin: 0; background: rgba(248, 250, 252, 0.7); padding: 1rem; border-radius: var(--radius-md);">
                        {{ $caregiver->about ?? 'No summary provided.' }}
                    </p>
                </div>

                @if ($caregiver->bio)
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="font-size: 0.9rem; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.5rem;">Detailed Biography & Care Philosophy</h4>
                        <div style="font-size: 0.92rem; line-height: 1.65; color: var(--text-secondary); background: rgba(248, 250, 252, 0.7); padding: 1rem; border-radius: var(--radius-md); white-space: pre-line;">
                            {{ $caregiver->bio }}
                        </div>
                    </div>
                @endif

                @if ($caregiver->previous_workplace || $caregiver->previous_experience)
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="font-size: 0.9rem; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.5rem;">Prior Clinical & In-Home Experience</h4>
                        <div style="background: rgba(248, 250, 252, 0.7); padding: 1rem; border-radius: var(--radius-md); font-size: 0.92rem;">
                            @if ($caregiver->previous_workplace)
                                <div style="margin-bottom: 0.35rem;">
                                    <strong>Workplace / Agency:</strong> {{ $caregiver->previous_workplace }}
                                </div>
                            @endif
                            @if ($caregiver->previous_experience)
                                <div style="color: var(--text-secondary); line-height: 1.5;">
                                    {{ $caregiver->previous_experience }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Services & Capabilities -->
                <div style="margin-bottom: 1.5rem;">
                    <h4 style="font-size: 0.9rem; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.5rem;">Services Provided</h4>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        @forelse ($caregiver->services as $svc)
                            <span class="badge" style="background: rgba(21, 121, 142, 0.1); color: var(--brand-primary); font-size: 0.85rem; padding: 0.35rem 0.75rem; border-radius: var(--radius-md); border: 1px solid rgba(21, 121, 142, 0.25);">
                                {{ $svc->name }} {{ $svc->pivot?->is_primary ? '★ (Primary)' : '' }}
                            </span>
                        @empty
                            <span style="font-size: 0.85rem; color: var(--text-muted);">No services assigned.</span>
                        @endforelse
                    </div>
                </div>

                <!-- Skills & Languages -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <h4 style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem;">Clinical & Care Skills</h4>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.35rem;">
                            @if (is_array($caregiver->skills) && count($caregiver->skills))
                                @foreach ($caregiver->skills as $sk)
                                    <span style="font-size: 0.78rem; background: rgba(226, 232, 240, 0.7); padding: 0.2rem 0.5rem; border-radius: var(--radius-sm); color: #334155;">{{ $sk }}</span>
                                @endforeach
                            @else
                                <span style="font-size: 0.82rem; color: var(--text-muted);">No specialized skills listed.</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h4 style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem;">Languages</h4>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.35rem;">
                            @if (is_array($caregiver->languages) && count($caregiver->languages))
                                @foreach ($caregiver->languages as $lang)
                                    <span style="font-size: 0.78rem; background: rgba(226, 232, 240, 0.7); padding: 0.2rem 0.5rem; border-radius: var(--radius-sm); color: #334155;">{{ $lang }}</span>
                                @endforeach
                            @else
                                <span style="font-size: 0.82rem; color: var(--text-muted);">Bengali</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings Assigned to this Caregiver -->
            <div class="glass-card" style="padding: 1.75rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                    <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a;">Recent Care Bookings</h3>
                    <span style="font-size: 0.85rem; color: var(--text-muted);">Total: {{ $caregiver->bookings->count() }}</span>
                </div>

                <div class="table-container">
                    <table class="glass-table" style="min-width: 600px;">
                        <thead>
                            <tr>
                                <th>Booking Ref</th>
                                <th>Client / Family</th>
                                <th>Start Date</th>
                                <th>Total Fee</th>
                                <th>Status</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($caregiver->bookings->take(5) as $b)
                                <tr>
                                    <td style="font-weight: 700; font-family: monospace;">{{ $b->booking_number }}</td>
                                    <td>{{ $b->client?->user?->name ?? 'Client' }}</td>
                                    <td>{{ $b->start_date?->format('M d, Y') ?? 'N/A' }}</td>
                                    <td style="font-weight: 700;">৳{{ number_format($b->total_price) }}</td>
                                    <td>
                                        <x-badge :tone="$b->status->badgeTone()">{{ $b->status->label() }}</x-badge>
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="{{ route('admin.bookings.show', $b->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.75rem; padding: 0.2rem 0.5rem;">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">No assignments recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Client Reviews -->
            <div class="glass-card" style="padding: 1.75rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                    <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a;">Client Reviews & Testimonials</h3>
                    <span style="font-size: 0.85rem; color: var(--text-muted);">({{ $caregiver->reviews->count() }} published reviews)</span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @forelse ($caregiver->reviews->take(4) as $rev)
                        <div style="padding: 1rem; background: rgba(248, 250, 252, 0.75); border: 1px solid rgba(226, 232, 240, 0.8); border-radius: var(--radius-md);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                                <div style="font-weight: 700; color: #0f172a;">{{ $rev->client?->user?->name ?? 'Verified Family' }}</div>
                                <div style="color: #f59e0b; font-size: 0.88rem;">★ {{ $rev->rating }}/5</div>
                            </div>
                            <p style="font-size: 0.88rem; color: var(--text-secondary); margin: 0; line-height: 1.5;">
                                "{{ $rev->review_text }}"
                            </p>
                            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.35rem;">
                                {{ $rev->created_at->format('M d, Y') }}
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; color: var(--text-muted); padding: 1.5rem;">No client reviews posted yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Personal Info, Safety, Rates & Location -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            
            <!-- Contact & Personal Details Card -->
            <div class="glass-card" style="padding: 1.75rem;">
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                    Contact & Identification
                </h3>

                <div style="display: flex; flex-direction: column; gap: 0.9rem; font-size: 0.88rem;">
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.78rem;">Full Name:</span>
                        <strong style="color: #0f172a;">{{ $caregiver->user->name }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.78rem;">Phone Number:</span>
                        <strong style="color: #0f172a;">{{ $caregiver->user->phone }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.78rem;">Email Address:</span>
                        <strong style="color: #0f172a;">{{ $caregiver->user->email }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.78rem;">Gender & Age:</span>
                        <strong style="color: #0f172a;">{{ ucfirst($caregiver->gender ?? 'N/A') }} ({{ $caregiver->age() ? $caregiver->age() . ' yrs' : 'N/A' }})</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.78rem;">Date of Birth:</span>
                        <strong style="color: #0f172a;">{{ $caregiver->date_of_birth ? $caregiver->date_of_birth->format('M d, Y') : 'Not specified' }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.78rem;">National ID (Encrypted):</span>
                        <code style="font-size: 0.85rem; color: #0f172a; background: rgba(226, 232, 240, 0.8); padding: 0.2rem 0.4rem; border-radius: 4px;">{{ $caregiver->nid_number ?? 'Not Recorded' }}</code>
                    </div>
                </div>
            </div>

            <!-- Pricing & Work Preferences Card -->
            <div class="glass-card" style="padding: 1.75rem;">
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                    Rates & Work Type
                </h3>

                <div style="display: flex; flex-direction: column; gap: 0.85rem; font-size: 0.88rem;">
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed rgba(226, 232, 240, 0.8); padding-bottom: 0.4rem;">
                        <span style="color: var(--text-muted);">Daily Rate:</span>
                        <strong style="color: #059669;">৳{{ number_format($caregiver->daily_rate) }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed rgba(226, 232, 240, 0.8); padding-bottom: 0.4rem;">
                        <span style="color: var(--text-muted);">Hourly Rate:</span>
                        <strong>{{ $caregiver->hourly_rate ? '৳' . number_format($caregiver->hourly_rate) . '/hr' : 'N/A' }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed rgba(226, 232, 240, 0.8); padding-bottom: 0.4rem;">
                        <span style="color: var(--text-muted);">Weekly Rate:</span>
                        <strong>{{ $caregiver->weekly_rate ? '৳' . number_format($caregiver->weekly_rate) : 'N/A' }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed rgba(226, 232, 240, 0.8); padding-bottom: 0.4rem;">
                        <span style="color: var(--text-muted);">Monthly Rate:</span>
                        <strong>{{ $caregiver->monthly_rate ? '৳' . number_format($caregiver->monthly_rate) : 'N/A' }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed rgba(226, 232, 240, 0.8); padding-bottom: 0.4rem;">
                        <span style="color: var(--text-muted);">Employment:</span>
                        <strong>{{ ucfirst(str_replace('_', ' ', $caregiver->employment_type ?? 'full_time')) }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed rgba(226, 232, 240, 0.8); padding-bottom: 0.4rem;">
                        <span style="color: var(--text-muted);">Living Style:</span>
                        <strong>{{ ucfirst(str_replace('_', ' ', $caregiver->live_type ?? 'both')) }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Client Gender:</span>
                        <strong>{{ ucfirst($caregiver->preferred_client_gender ?? 'Any') }}</strong>
                    </div>
                </div>
            </div>

            <!-- Geographic Location Card -->
            <div class="glass-card" style="padding: 1.75rem;">
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                    Location & Territory
                </h3>

                <div style="display: flex; flex-direction: column; gap: 0.85rem; font-size: 0.88rem;">
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.78rem;">Administrative Division:</span>
                        <strong style="color: #0f172a;">{{ $caregiver->division?->name ?? 'Not assigned' }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.78rem;">District:</span>
                        <strong style="color: #0f172a;">{{ $caregiver->district?->name ?? 'Not assigned' }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.78rem;">Area / Upazila:</span>
                        <strong style="color: #0f172a;">{{ $caregiver->area?->name ?? $caregiver->city ?? 'Not assigned' }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.78rem;">Present Address:</span>
                        <span style="color: #334155;">{{ $caregiver->present_address ?? 'Confidential' }}</span>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.78rem;">Permanent Address:</span>
                        <span style="color: #334155;">{{ $caregiver->permanent_address ?? 'Confidential' }}</span>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="glass-card" style="padding: 1.75rem;">
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                    Emergency Contact
                </h3>

                <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.88rem;">
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.78rem;">Contact Person:</span>
                        <strong style="color: #0f172a;">{{ $caregiver->emergency_contact_name ?? 'Not listed' }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.78rem;">Phone:</span>
                        <strong style="color: #0f172a;">{{ $caregiver->emergency_contact_phone ?? 'Not listed' }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.78rem;">Relationship:</span>
                        <strong style="color: #0f172a;">{{ $caregiver->emergency_contact_relationship ?? 'Not specified' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
