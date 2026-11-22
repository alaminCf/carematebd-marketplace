<x-layouts.dashboard>
    <x-slot:title>Platform Edit History & Audit Trail — Admin | CareMate BD</x-slot:title>
    <x-slot:header>Platform Edit History & Audit Trail</x-slot:header>
    <x-slot:subheading>Complete record of which administrator added, edited, verified, or updated any Caregiver, Nurse, Admin User, Booking, or Platform Setting.</x-slot:subheading>

    <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
        <!-- Filter Bar: Filter by Admin, Entity Type, or Search -->
        <form method="GET" action="{{ route('admin.audit-logs') }}" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 1.15rem; border-bottom: 1px solid rgba(226, 232, 240, 0.85);">
            <div style="display: flex; align-items: center; gap: 0.65rem; flex-wrap: wrap; flex: 1;">
                <select name="admin_id" class="glass-input" style="width: auto; font-size: 0.84rem; padding: 0.45rem 0.75rem;">
                    <option value="">👑 All Admin Operators</option>
                    @foreach ($admins ?? [] as $adm)
                        <option value="{{ $adm->id }}" {{ ($adminFilter ?? '') == $adm->id ? 'selected' : '' }}>
                            {{ $adm->name }} ({{ $adm->email }})
                        </option>
                    @endforeach
                </select>

                <select name="entity" class="glass-input" style="width: auto; font-size: 0.84rem; padding: 0.45rem 0.75rem;">
                    <option value="">📂 All Target Types</option>
                    <option value="Caregiver" {{ ($entityFilter ?? '') === 'Caregiver' ? 'selected' : '' }}>🩺 Caregivers / Nurses</option>
                    <option value="User" {{ ($entityFilter ?? '') === 'User' ? 'selected' : '' }}>👤 Admin & User Accounts</option>
                    <option value="Booking" {{ ($entityFilter ?? '') === 'Booking' ? 'selected' : '' }}>📅 Care Bookings</option>
                    <option value="HiringRequest" {{ ($entityFilter ?? '') === 'HiringRequest' ? 'selected' : '' }}>📩 Hiring Requests</option>
                    <option value="Service" {{ ($entityFilter ?? '') === 'Service' ? 'selected' : '' }}>🏥 Services</option>
                </select>

                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search action or edit summary..." class="glass-input" style="font-size: 0.84rem; padding: 0.45rem 0.8rem; max-width: 260px;">

                <button type="submit" class="btn btn-primary btn-sm">Filter History</button>

                @if (!empty($adminFilter) || !empty($entityFilter) || !empty($search))
                    <a href="{{ route('admin.audit-logs') }}" class="btn btn-secondary btn-sm">Reset</a>
                @endif
            </div>

            <div style="font-size: 0.82rem; color: var(--text-muted); font-weight: 600;">
                Total Logged Actions: <strong style="color: #0f172a;">{{ $logs->total() }}</strong>
            </div>
        </form>

        <div class="table-container">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Edited By (Admin)</th>
                        <th>Action Performed</th>
                        <th>Target Person / Record</th>
                        <th>Field-by-Field Edit Changes (Before → After)</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        @php
                            $target = $log->targetInfo();
                            $diffs = $log->changedFields();
                        @endphp
                        <tr>
                            <!-- Timestamp -->
                            <td style="font-size: 0.8rem; color: var(--text-muted); white-space: nowrap; vertical-align: top;">
                                <div style="font-weight: 800; color: #0f172a;">{{ $log->created_at->format('d M Y') }}</div>
                                <div style="font-family: monospace; font-size: 0.76rem; color: #0d9488; font-weight: 700;">{{ $log->created_at->format('h:i:s A') }}</div>
                                <div style="font-size: 0.7rem; color: #94a3b8;">{{ $log->created_at->diffForHumans() }}</div>
                            </td>

                            <!-- Admin Operator -->
                            <td style="vertical-align: top;">
                                <div style="display: flex; align-items: center; gap: 0.6rem;">
                                    @if ($log->admin)
                                        <img src="{{ $log->admin->avatarUrl() }}" alt="" style="width: 34px; height: 34px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                                        <div>
                                            <div style="font-weight: 800; color: #0f172a; font-size: 0.88rem;">{{ $log->admin->name }}</div>
                                            <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $log->admin->email }}</div>
                                        </div>
                                    @else
                                        <div style="font-style: italic; color: var(--text-muted); font-size: 0.85rem;">System / Automated</div>
                                    @endif
                                </div>
                            </td>

                            <!-- Action Performed -->
                            <td style="vertical-align: top;">
                                <div style="font-weight: 800; font-size: 0.84rem; color: #0a394a; background: rgba(10, 57, 74, 0.08); padding: 0.25rem 0.6rem; border-radius: 8px; display: inline-block; margin-bottom: 0.2rem;">
                                    {{ $log->actionLabel() }}
                                </div>
                                <div style="font-family: monospace; font-size: 0.7rem; color: #64748b;">
                                    {{ $log->action }}
                                </div>
                            </td>

                            <!-- Target Entity -->
                            <td style="font-size: 0.84rem; vertical-align: top;">
                                <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: #64748b; display: block;">{{ $target['type'] }}</span>
                                @if ($target['url'])
                                    <a href="{{ $target['url'] }}" style="font-weight: 800; color: #0284c7; text-decoration: none;">
                                        {{ $target['label'] }} ↗
                                    </a>
                                @else
                                    <strong style="color: #0f172a;">{{ $target['label'] }}</strong>
                                @endif
                            </td>

                            <!-- State Changes / Notes -->
                            <td style="font-size: 0.82rem; max-width: 380px; vertical-align: top;">
                                @if ($log->reason)
                                    <div style="color: #0f172a; font-weight: 700; margin-bottom: 0.35rem;">
                                        {{ $log->reason }}
                                    </div>
                                @endif

                                @if (count($diffs) > 0)
                                    <div style="background: #f8fafc; border: 1px solid rgba(226, 232, 240, 0.9); border-radius: 8px; padding: 0.45rem 0.6rem; display: flex; flex-direction: column; gap: 0.25rem;">
                                        @foreach (collect($diffs)->take(6) as $d)
                                            <div style="font-size: 0.75rem; line-height: 1.35;">
                                                <strong style="color: #334155;">{{ $d['field'] }}:</strong>
                                                @if ($d['from'] !== '—')
                                                    <span style="color: #dc2626; text-decoration: line-through;">{{ Str::limit($d['from'], 40) }}</span> →
                                                @endif
                                                <strong style="color: #059669;">{{ Str::limit($d['to'], 45) }}</strong>
                                            </div>
                                        @endforeach
                                        @if (count($diffs) > 6)
                                            <div style="font-size: 0.7rem; color: #64748b;">+{{ count($diffs) - 6 }} more fields updated</div>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            <!-- IP Address -->
                            <td style="font-family: monospace; font-size: 0.76rem; color: var(--text-muted); vertical-align: top;">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 3rem;">No administrative edit records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $logs->links() }}
        </div>
    </div>
</x-layouts.dashboard>
