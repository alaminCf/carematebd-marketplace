<x-layouts.dashboard>
    <x-slot:title>Manage All Caregivers — Admin | CareMate BD</x-slot:title>
    <x-slot:header>Manage All Caregivers</x-slot:header>
    <x-slot:subheading>Full roster of approved, active, and suspended caregivers across Bangladesh.</x-slot:subheading>

    <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl); overflow: hidden;">
        <!-- Top Search and Filter Bar -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <form method="GET" action="{{ route('admin.caregivers.index') }}" style="display: flex; gap: 0.75rem; flex: 1; max-width: 540px; flex-wrap: wrap;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, email, phone, city..." class="glass-input" style="font-size: 0.85rem; padding: 0.45rem 0.8rem; flex: 1; min-width: 200px;">
                <select name="status" class="glass-input" style="width: auto; font-size: 0.85rem; padding: 0.45rem 0.8rem;">
                    <option value="">All Statuses</option>
                    <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="published" {{ $status == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="pending_verification" {{ $status == 'pending_verification' ? 'selected' : '' }}>Pending Verification</option>
                    <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="suspended" {{ $status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
                <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            </form>

            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('admin.applications.index') }}" class="btn btn-primary btn-sm">
                    Verification Queue →
                </a>
            </div>
        </div>

        <!-- Caregivers Table with Smooth Scroll Container -->
        <div class="table-container" style="margin-bottom: 1.5rem;">
            <table class="glass-table" style="min-width: 1050px;">
                <thead>
                    <tr>
                        <th style="width: 110px; text-align: center;">Order / Seq</th>
                        <th>Caregiver</th>
                        <th>Care Specialty</th>
                        <th>City / Area</th>
                        <th>Rating</th>
                        <th>Daily Rate</th>
                        <th style="text-align: center;">Featured</th>
                        <th>Status</th>
                        <th style="text-align: right; min-width: 220px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($caregivers as $cg)
                        <tr>
                            <!-- Marketplace Sequence Order Input -->
                            <td style="text-align: center;">
                                <form method="POST" action="{{ route('admin.caregivers.sort-order', $cg->id) }}" style="display: inline-flex; align-items: center; justify-content: center; gap: 0.3rem;">
                                    @csrf
                                    <input type="number" name="sort_order" value="{{ $cg->sort_order }}" min="0" max="9999" title="Lower number appears first (1 = top first, 2 = 2nd)" style="width: 52px; padding: 0.25rem 0.35rem; font-size: 0.82rem; font-weight: 700; text-align: center; border: 1px solid rgba(203, 213, 225, 0.9); border-radius: var(--radius-sm); color: var(--brand-primary); background: #ffffff;">
                                    <button type="submit" class="btn btn-secondary btn-sm" style="padding: 0.25rem 0.45rem; font-size: 0.7rem; line-height: 1;" title="Save Position">
                                        Save
                                    </button>
                                </form>
                            </td>

                            <!-- Caregiver Identity -->
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <img src="{{ $cg->avatarUrl() }}" alt="" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 2px solid #fff; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 0.35rem;">
                                            <a href="{{ route('admin.caregivers.show', $cg->id) }}" style="color: inherit; text-decoration: none;">
                                                {{ $cg->user->name }}
                                            </a>
                                            @if ($cg->sort_order > 0)
                                                <span style="font-size: 0.7rem; background: rgba(21, 121, 142, 0.1); color: var(--brand-primary); padding: 0.1rem 0.35rem; border-radius: 3px; font-weight: 700;">#{{ $cg->sort_order }}</span>
                                            @endif
                                        </div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $cg->user->email }} • {{ $cg->user->phone }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Care Specialty -->
                            <td style="max-width: 180px;">
                                <div style="font-weight: 600; font-size: 0.88rem; color: #334155; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $cg->caregiver_type }}">
                                    {{ $cg->caregiver_type }}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $cg->years_experience }} yrs exp</div>
                            </td>

                            <!-- City / Area -->
                            <td style="font-size: 0.88rem;">{{ $cg->area?->name ?? $cg->district?->name ?? $cg->city }}</td>

                            <!-- Rating -->
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.25rem;">
                                    <span style="color: #f59e0b;">★</span>
                                    <strong>{{ number_format($cg->rating_avg, 1) }}</strong>
                                    <span style="font-size: 0.75rem; color: var(--text-muted);">({{ $cg->rating_count }})</span>
                                </div>
                            </td>

                            <!-- Daily Rate -->
                            <td style="font-weight: 700; color: #059669;">৳{{ number_format($cg->daily_rate) }}</td>

                            <!-- Featured Toggle -->
                            <td style="text-align: center;">
                                <form method="POST" action="{{ route('admin.caregivers.toggle-featured', $cg->id) }}">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; cursor: pointer; font-size: 1.15rem; color: {{ $cg->is_featured ? '#f59e0b' : '#cbd5e1' }}; transition: transform 0.15s ease;" title="{{ $cg->is_featured ? 'Featured on marketplace. Click to remove.' : 'Click to feature on marketplace' }}">
                                        ★
                                    </button>
                                </form>
                            </td>

                            <!-- Status Badge -->
                            <td>
                                <x-badge :tone="$cg->status->badgeTone()">{{ $cg->status->label() }}</x-badge>
                            </td>

                            <!-- Actions Group (View, Edit, Dossier, Suspend) -->
                            <td style="text-align: right; white-space: nowrap;">
                                <div style="display: inline-flex; align-items: center; gap: 0.35rem;">
                                    <!-- View Profile -->
                                    <a href="{{ route('admin.caregivers.show', $cg->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.75rem; padding: 0.28rem 0.55rem;" title="View Full Profile">
                                        👁️ View
                                    </a>

                                    <!-- Edit Caregiver -->
                                    <a href="{{ route('admin.caregivers.edit', $cg->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.75rem; padding: 0.28rem 0.55rem;" title="Edit Caregiver Details">
                                        ✏️ Edit
                                    </a>

                                    <!-- Dossier / Documents -->
                                    <a href="{{ route('admin.applications.show', $cg->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.75rem; padding: 0.28rem 0.55rem;" title="Verification Dossier">
                                        🪪 Dossier
                                    </a>

                                    <!-- Suspend / Reactivate -->
                                    @if ($cg->status->value !== 'suspended')
                                        <form method="POST" action="{{ route('admin.caregivers.suspend', $cg->id) }}" onsubmit="return prompt('Reason for suspension:') ? true : false;" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="reason" value="Administrative compliance review">
                                            <button type="submit" class="btn btn-sm" style="background: rgba(244, 63, 94, 0.1); color: #e11d48; border: 1px solid rgba(244, 63, 94, 0.25); font-size: 0.75rem; padding: 0.28rem 0.55rem;" title="Suspend Caregiver">
                                                Suspend
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.caregivers.reactivate', $cg->id) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm" style="background: rgba(16, 185, 129, 0.1); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25); font-size: 0.75rem; padding: 0.28rem 0.55rem;" title="Reactivate Caregiver">
                                                Reactivate
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 3rem;">
                                No caregivers matched this search query.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1rem;">
            {{ $caregivers->links() }}
        </div>
    </div>
</x-layouts.dashboard>
