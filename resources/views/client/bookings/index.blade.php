<x-layouts.dashboard>
    <x-slot:title>My Care Bookings — CareMate BD</x-slot:title>
    <x-slot:header>My Care Bookings</x-slot:header>
    <x-slot:subheading>Confirmed, active, and completed in-home care services supervised by CareMate BD.</x-slot:subheading>

    <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
        <div style="font-weight: 700; font-size: 1.15rem; color: #0f172a; margin-bottom: 1.5rem;">
            All Bookings ({{ $bookings->total() }})
        </div>

        @if ($bookings->isNotEmpty())
            <div class="table-container">
                <table class="glass-table">
                    <thead>
                        <tr>
                            <th>Booking Ref</th>
                            <th>Caregiver</th>
                            <th>Service</th>
                            <th>Schedule</th>
                            <th>Status</th>
                            <th>Total Amount</th>
                            <th>Payment</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $b)
                            <tr>
                                <td style="font-weight: 700; color: var(--brand-primary);">
                                    #{{ $b->booking_reference }}
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <img src="{{ $b->caregiver->avatarUrl() }}" alt="" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                        <span style="font-weight: 600;">{{ $b->caregiver->user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $b->service->name }}</td>
                                <td style="font-size: 0.85rem;">
                                    {{ $b->start_date->format('M d') }} - {{ $b->end_date->format('M d, Y') }}
                                </td>
                                <td>
                                    <x-badge :tone="$b->status->badgeTone()">{{ $b->status->label() }}</x-badge>
                                </td>
                                <td style="font-weight: 700;">
                                    ৳{{ number_format($b->client_total) }}
                                </td>
                                <td>
                                    @if ($b->payment_status->value === 'paid')
                                        <x-badge tone="success">Paid in Escrow</x-badge>
                                    @else
                                        <x-badge tone="warning">{{ $b->payment_status->label() }}</x-badge>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('client.bookings.show', $b->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.8rem;">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1.5rem;">
                {{ $bookings->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 3rem; color: var(--text-muted);">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📅</div>
                <h4 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">No Active or Past Bookings</h4>
                <p style="margin-bottom: 1rem;">Once your care request is confirmed by our Admin, it will appear here.</p>
                <a href="{{ route('marketplace.index') }}" class="btn btn-primary">Find a Caregiver</a>
            </div>
        @endif
    </div>
</x-layouts.dashboard>
