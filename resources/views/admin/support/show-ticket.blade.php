<x-layouts.dashboard>
    <x-slot:title>Ticket #{{ $ticket->ticket_number }} — Admin Support | CareMate BD</x-slot:title>
    <x-slot:header>Ticket #{{ $ticket->ticket_number }}</x-slot:header>
    <x-slot:subheading>{{ $ticket->subject }}</x-slot:subheading>

    <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
        <a href="{{ route('admin.support.tickets') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 0.4rem;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            Back to Tickets Queue
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 2rem; align-items: flex-start;" class="admin-ticket-layout">
        <!-- Message Thread & Reply Form -->
        <div>
            <!-- Initial User Complaint / Inbound Inquiry -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <img src="{{ $ticket->user->avatarUrl() }}" alt="" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover;">
                        <div>
                            <div style="font-weight: 800; color: #0f172a; font-size: 1.05rem;">{{ $ticket->user->name }}</div>
                            <div style="font-size: 0.78rem; color: var(--text-muted);">
                                <span style="text-transform: uppercase; font-weight: 700; color: {{ $ticket->user->isCaregiver() ? '#059669' : '#0a394a' }};">
                                    {{ $ticket->user->role->value }}
                                </span>
                                • {{ $ticket->user->email }}
                            </div>
                        </div>
                    </div>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $ticket->created_at->format('M d, Y h:i A') }}</span>
                </div>

                <div style="font-size: 1rem; color: #1e293b; line-height: 1.6; white-space: pre-line;">
                    {{ $ticket->message }}
                </div>
            </div>

            <!-- Conversation Messages -->
            @foreach ($ticket->messages as $msg)
                @if ($msg->is_internal)
                    <!-- Internal Staff Note -->
                    <div style="background: rgba(254, 243, 199, 0.7); border: 1px solid rgba(245, 158, 11, 0.4); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 1.25rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; font-weight: 700; color: #92400e;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                <span>INTERNAL STAFF MEMO — {{ $msg->user->name }} (CareMate Admin)</span>
                            </div>
                            <span style="font-size: 0.78rem; color: #b45309;">{{ $msg->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <div style="font-size: 0.92rem; color: #78350f; line-height: 1.5; white-space: pre-line;">
                            {{ $msg->message }}
                        </div>
                    </div>
                @else
                    <!-- Public Message -->
                    <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl); margin-bottom: 1.25rem; {{ $msg->user->isAdmin() ? 'border-left: 4px solid #0a394a; background: rgba(230, 241, 244, 0.5);' : '' }}">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                            <div style="display: flex; align-items: center; gap: 0.6rem;">
                                <img src="{{ $msg->user->avatarUrl() }}" alt="" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                <div>
                                    <span style="font-weight: 700; color: #0f172a; font-size: 0.92rem;">{{ $msg->user->name }}</span>
                                    @if ($msg->user->isAdmin())
                                        <span style="font-size: 0.7rem; background: #0a394a; color: #fff; padding: 0.15rem 0.4rem; border-radius: 4px; font-weight: 700; margin-left: 0.35rem;">Staff</span>
                                    @endif
                                </div>
                            </div>
                            <span style="font-size: 0.78rem; color: var(--text-muted);">{{ $msg->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <div style="font-size: 0.95rem; color: #1e293b; line-height: 1.6; white-space: pre-line;">
                            {{ $msg->message }}
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Reply Form -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-top: 2rem;">
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem;">
                    Post Reply or Staff Memo
                </h3>

                <form method="POST" action="{{ route('admin.support.tickets.reply', $ticket->id) }}">
                    @csrf
                    <div style="margin-bottom: 1.25rem;">
                        <textarea name="message" rows="5" required placeholder="Compose response to the user or note internal actions taken..." class="glass-textarea" style="width: 100%; font-size: 0.95rem;"></textarea>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                        <div style="display: flex; align-items: center; gap: 1.25rem;">
                            <div style="display: flex; align-items: center; gap: 0.4rem;">
                                <input type="checkbox" id="is_internal" name="is_internal" value="1" style="width: 18px; height: 18px;">
                                <label for="is_internal" style="font-size: 0.85rem; font-weight: 700; color: #92400e; cursor: pointer;">
                                    🔒 Internal Note (Hidden from user)
                                </label>
                            </div>

                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <label style="font-size: 0.82rem; font-weight: 700; color: #0f172a;">Update Status:</label>
                                <select name="status" class="glass-select" style="font-size: 0.85rem; padding: 0.35rem 0.75rem;">
                                    <option value="open" {{ $ticket->status->value === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_review" {{ $ticket->status->value === 'in_review' ? 'selected' : '' }}>In Review</option>
                                    <option value="waiting_for_user" {{ $ticket->status->value === 'waiting_for_user' ? 'selected' : '' }}>Waiting for User</option>
                                    <option value="resolved" {{ $ticket->status->value === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ $ticket->status->value === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            Submit Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar / Ticket Metadata -->
        <div>
            <!-- Ticket Info Card -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); margin-bottom: 1.5rem;">
                <h4 style="font-size: 0.8rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 1rem;">
                    Ticket Dossier
                </h4>

                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.75rem; color: var(--text-secondary);">
                    <span>Status:</span>
                    <x-badge :tone="$ticket->status->tone()">
                        {{ $ticket->status->label() }}
                    </x-badge>
                </div>

                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.75rem; color: var(--text-secondary);">
                    <span>Priority:</span>
                    <x-badge :tone="$ticket->priority->tone()">
                        {{ $ticket->priority->label() }}
                    </x-badge>
                </div>

                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.75rem; color: var(--text-secondary);">
                    <span>Category:</span>
                    <strong style="color: #0f172a;">{{ $ticket->category?->label() ?? 'General' }}</strong>
                </div>

                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; color: var(--text-secondary);">
                    <span>Opened:</span>
                    <span style="color: #0f172a;">{{ $ticket->created_at->format('M d, Y') }}</span>
                </div>
            </div>

            <!-- Related Booking Context -->
            @if ($ticket->booking)
                <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
                    <h4 style="font-size: 0.8rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 1rem;">
                        Linked Booking
                    </h4>

                    <div style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">
                        {{ $ticket->booking->service->name }}
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1rem;">
                        Ref: #{{ $ticket->booking->booking_reference }}
                    </div>

                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 0.5rem; color: var(--text-secondary);">
                        <span>Client:</span>
                        <strong style="color: #0f172a;">{{ $ticket->booking->client->user->name }}</strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 1rem; color: var(--text-secondary);">
                        <span>Caregiver:</span>
                        <strong style="color: #0f172a;">{{ $ticket->booking->caregiver->user->name }}</strong>
                    </div>

                    <a href="{{ route('admin.bookings.show', $ticket->booking->id) }}" class="btn btn-secondary btn-sm" style="width: 100%; justify-content: center;">
                        View Booking Details →
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.dashboard>
