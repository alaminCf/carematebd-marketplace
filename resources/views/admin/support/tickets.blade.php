<x-layouts.dashboard>
    <x-slot:title>Support Tickets — Admin Desk | CareMate BD</x-slot:title>
    <x-slot:header>Care Coordination & Support Desk</x-slot:header>
    <x-slot:subheading>Central communications hub for addressing client concerns, caregiver operational queries, and shift coordination.</x-slot:subheading>

    <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
        <!-- Filter Tabs -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <a href="{{ route('admin.support.tickets') }}" class="btn btn-sm {{ !$status ? 'btn-primary' : 'btn-secondary' }}">
                    All Tickets
                </a>
                <a href="{{ route('admin.support.tickets', ['status' => 'open']) }}" class="btn btn-sm {{ $status === 'open' ? 'btn-primary' : 'btn-secondary' }}">
                    Open
                </a>
                <a href="{{ route('admin.support.tickets', ['status' => 'in_review']) }}" class="btn btn-sm {{ $status === 'in_review' ? 'btn-primary' : 'btn-secondary' }}">
                    In Review
                </a>
                <a href="{{ route('admin.support.tickets', ['status' => 'waiting_for_user']) }}" class="btn btn-sm {{ $status === 'waiting_for_user' ? 'btn-primary' : 'btn-secondary' }}">
                    Waiting for User
                </a>
                <a href="{{ route('admin.support.tickets', ['status' => 'resolved']) }}" class="btn btn-sm {{ $status === 'resolved' ? 'btn-primary' : 'btn-secondary' }}">
                    Resolved
                </a>
                <a href="{{ route('admin.support.tickets', ['status' => 'closed']) }}" class="btn btn-sm {{ $status === 'closed' ? 'btn-primary' : 'btn-secondary' }}">
                    Closed
                </a>
            </div>
        </div>

        <div class="table-container">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>Ticket #</th>
                        <th>User</th>
                        <th>Subject & Category</th>
                        <th>Related Booking</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Last Reply</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $t)
                        <tr>
                            <td style="font-family: monospace; font-weight: 700; color: #0f172a;">
                                #{{ $t->ticket_number }}
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <img src="{{ $t->user->avatarUrl() }}" alt="" style="width: 34px; height: 34px; border-radius: 50%; object-fit: cover;">
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">{{ $t->user->name }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                                            <span style="text-transform: uppercase; font-weight: 700; color: {{ $t->user->isCaregiver() ? '#059669' : '#0a394a' }};">
                                                {{ $t->user->role->value }}
                                            </span>
                                            • {{ $t->user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #0f172a; max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $t->subject }}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">
                                    Category: {{ $t->category?->label() ?? ucfirst(str_replace('_', ' ', $t->category?->value ?? 'general')) }}
                                </div>
                            </td>
                            <td style="font-size: 0.85rem;">
                                @if ($t->booking)
                                    <div style="font-weight: 600; color: #0f172a;">{{ $t->booking->service->name }}</div>
                                    <a href="{{ route('admin.bookings.show', $t->booking->id) }}" style="font-size: 0.75rem; color: #0a394a; text-decoration: underline;">
                                        #{{ $t->booking->booking_reference }}
                                    </a>
                                @else
                                    <span style="color: var(--text-muted); font-size: 0.8rem;">General Inquiry</span>
                                @endif
                            </td>
                            <td>
                                <x-badge :tone="$t->priority->tone()">
                                    {{ $t->priority->label() }}
                                </x-badge>
                            </td>
                            <td>
                                <x-badge :tone="$t->status->tone()">
                                    {{ $t->status->label() }}
                                </x-badge>
                            </td>
                            <td style="font-size: 0.8rem; color: var(--text-muted);">
                                {{ $t->last_reply_at ? $t->last_reply_at->diffForHumans() : $t->created_at->diffForHumans() }}
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('admin.support.tickets.show', $t->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.8rem;">
                                    Open Ticket →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" style="text-align: center; color: var(--text-muted); padding: 3rem;">No support tickets in this queue.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $tickets->links() }}
        </div>
    </div>
</x-layouts.dashboard>
