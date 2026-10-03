<x-layouts.dashboard>
    <x-slot:title>Manage All Bookings — Admin | CareMate BD</x-slot:title>
    <x-slot:header>Platform Care Bookings</x-slot:header>
    <x-slot:subheading>Supervise active in-home care shifts, verify duty completion, and manage service lifecycles.</x-slot:subheading>

    <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
        <!-- Search and Filter Bar -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <form method="GET" action="{{ route('admin.bookings.index') }}" style="display: flex; gap: 0.75rem; flex: 1; max-width: 500px;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by booking ref, client, caregiver..." class="glass-input" style="font-size: 0.85rem; padding: 0.45rem 0.8rem;">
                <select name="status" class="glass-input" style="width: auto; font-size: 0.85rem; padding: 0.45rem 0.8rem;">
                    <option value="">All Statuses</option>
                    <option value="confirmed" {{ $status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="in_progress" {{ $status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="disputed" {{ $status == 'disputed' ? 'selected' : '' }}>Disputed</option>
                    <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            </form>
        </div>

        <div class="table-container">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>Booking Ref</th>
                        <th>Client (Family)</th>
                        <th>Caregiver</th>
                        <th>Service</th>
                        <th>Schedule</th>
                        <th>Total Paid (৳)</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $b)
                        <tr>
                            <td style="font-weight: 700; color: var(--brand-primary);">#{{ $b->booking_reference }}</td>
                            <td>
                                <div style="font-weight: 700; color: #0f172a;">{{ $b->client->user->name }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $b->client->user->phone }}</div>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #0f172a;">{{ $b->caregiver->user->name }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $b->caregiver->user->phone }}</div>
                            </td>
                            <td>{{ $b->service->name }}</td>
                            <td style="font-size: 0.85rem;">{{ $b->start_date->format('M d') }} - {{ $b->end_date->format('M d, Y') }}</td>
                            <td style="font-weight: 700;">৳{{ number_format($b->client_total) }}</td>
                            <td>
                                <x-badge :tone="$b->payment_status->value === 'paid' ? 'success' : 'warning'">
                                    {{ ucfirst($b->payment_status->value) }}
                                </x-badge>
                            </td>
                            <td><x-badge :tone="$b->status->badgeTone()">{{ $b->status->label() }}</x-badge></td>
                            <td style="text-align: right;">
                                <a href="{{ route('admin.bookings.show', $b->id) }}" class="btn btn-secondary btn-sm">
                                    Manage →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" style="text-align: center; color: var(--text-muted); padding: 3rem;">No bookings found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $bookings->links() }}
        </div>
    </div>
</x-layouts.dashboard>
