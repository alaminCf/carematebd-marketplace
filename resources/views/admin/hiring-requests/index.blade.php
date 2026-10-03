<x-layouts.dashboard>
    <x-slot:title>Hiring Requests Triage — Admin | CareMate BD</x-slot:title>
    <x-slot:header>Client Care Requests Triage</x-slot:header>
    <x-slot:subheading>Admin mediation desk for evaluating incoming care requirements, matching staff, and issuing quotes.</x-slot:subheading>

    <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
        <!-- Search and Filter Bar -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <form method="GET" action="{{ route('admin.hiring-requests.index') }}" style="display: flex; gap: 0.75rem; flex: 1; max-width: 500px;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by reference, client, patient..." class="glass-input" style="font-size: 0.85rem; padding: 0.45rem 0.8rem;">
                <select name="status" class="glass-input" style="width: auto; font-size: 0.85rem; padding: 0.45rem 0.8rem;">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending Review</option>
                    <option value="admin_approved" {{ $status == 'admin_approved' ? 'selected' : '' }}>Admin Approved</option>
                    <option value="caregiver_accepted" {{ $status == 'caregiver_accepted' ? 'selected' : '' }}>Caregiver Accepted</option>
                    <option value="confirmed" {{ $status == 'confirmed' ? 'selected' : '' }}>Confirmed / Booked</option>
                </select>
                <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            </form>
        </div>

        <div class="table-container">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>Ref #</th>
                        <th>Client (Family)</th>
                        <th>Assigned Caregiver</th>
                        <th>Service</th>
                        <th>Patient</th>
                        <th>Dates</th>
                        <th>Quoted Total</th>
                        <th>Status</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $r)
                        <tr>
                            <td style="font-weight: 700; color: var(--brand-primary);">#{{ $r->request_reference }}</td>
                            <td>
                                <div style="font-weight: 700; color: #0f172a;">{{ $r->client->user->name }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $r->client->user->phone }}</div>
                            </td>
                            <td>
                                @if ($r->caregiver)
                                    <div style="font-weight: 600;">{{ $r->caregiver->user->name }}</div>
                                @else
                                    <span style="color: var(--text-muted);">Unassigned</span>
                                @endif
                            </td>
                            <td>{{ $r->service->name }}</td>
                            <td>
                                {{ $r->care_recipient_name }}
                                <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">({{ $r->care_recipient_relationship }})</span>
                            </td>
                            <td style="font-size: 0.85rem;">{{ $r->start_date->format('M d') }} - {{ $r->end_date->format('M d') }} ({{ $r->total_days }}d)</td>
                            <td style="font-weight: 700;">৳{{ number_format($r->client_quoted_amount) }}</td>
                            <td><x-badge :tone="$r->status->badgeTone()">{{ $r->status->label() }}</x-badge></td>
                            <td style="text-align: right;">
                                <a href="{{ route('admin.hiring-requests.show', $r->id) }}" class="btn btn-primary btn-sm">
                                    Triage →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" style="text-align: center; color: var(--text-muted); padding: 3rem;">No hiring requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $requests->links() }}
        </div>
    </div>
</x-layouts.dashboard>
