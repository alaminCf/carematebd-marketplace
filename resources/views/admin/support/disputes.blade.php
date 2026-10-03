<x-layouts.dashboard>
    <x-slot:title>Dispute Resolution — Admin Desk | CareMate BD</x-slot:title>
    <x-slot:header>Dispute Adjudication Chamber</x-slot:header>
    <x-slot:subheading>Formal complaints, service standard disagreements, and escrow refund claims requiring administrator mediation.</x-slot:subheading>

    <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
        <div class="table-container">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>Case Ref</th>
                        <th>Filed By</th>
                        <th>Care Booking</th>
                        <th>Category</th>
                        <th>Parties Involved</th>
                        <th>Status</th>
                        <th>Filed On</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($disputes as $d)
                        <tr>
                            <td style="font-family: monospace; font-weight: 700; color: #0f172a;">
                                {{ $d->reference }}
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.6rem;">
                                    <img src="{{ $d->reporter->avatarUrl() }}" alt="" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">{{ $d->reporter->name }}</div>
                                        <x-badge :tone="$d->reporter->isCaregiver() ? 'info' : 'primary'" style="font-size: 0.68rem; padding: 0.15rem 0.4rem;">
                                            {{ ucfirst($d->reporter->role->value) }}
                                        </x-badge>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #0f172a;">{{ $d->booking->service->name }}</div>
                                <a href="{{ route('admin.bookings.show', $d->booking->id) }}" style="font-size: 0.75rem; color: #0a394a; text-decoration: underline;">
                                    #{{ $d->booking->booking_reference }}
                                </a>
                            </td>
                            <td>
                                <span style="font-weight: 600; font-size: 0.85rem; color: #0f172a;">
                                    {{ $d->category?->label() ?? ucfirst(str_replace('_', ' ', $d->category?->value ?? 'Dispute')) }}
                                </span>
                            </td>
                            <td style="font-size: 0.82rem; color: var(--text-secondary);">
                                <div>Client: <strong>{{ $d->booking->client->user->name }}</strong></div>
                                <div>Caregiver: <strong>{{ $d->booking->caregiver->user->name }}</strong></div>
                            </td>
                            <td>
                                <x-badge :tone="$d->status->tone()">
                                    {{ $d->status->label() }}
                                </x-badge>
                            </td>
                            <td style="font-size: 0.8rem; color: var(--text-muted);">
                                {{ $d->created_at->format('M d, Y') }}
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('admin.support.disputes.show', $d->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.8rem;">
                                    Investigate Case →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" style="text-align: center; color: var(--text-muted); padding: 3rem;">No active disputes on record.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $disputes->links() }}
        </div>
    </div>
</x-layouts.dashboard>
