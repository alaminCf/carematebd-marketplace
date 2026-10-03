<x-layouts.dashboard>
    <x-slot:title>Ticket #{{ $ticket->ticket_number }} — CareMate BD</x-slot:title>
    <x-slot:header>Ticket #{{ $ticket->ticket_number }}</x-slot:header>
    <x-slot:subheading>{{ $ticket->subject }}</x-slot:subheading>

    <div style="display: grid; grid-template-columns: 1fr 320px; gap: 2rem; align-items: flex-start;" class="ticket-layout">
        <!-- Thread Area -->
        <div>
            <!-- Conversation Thread -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    @foreach ($ticket->messages as $msg)
                        <div style="display: flex; gap: 1rem; align-items: flex-start; {{ $msg->user_id === auth()->id() ? 'flex-direction: row-reverse;' : '' }}">
                            <img src="{{ $msg->user->avatarUrl() }}" alt="{{ $msg->user->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                            
                            <div style="max-width: 80%; background: {{ $msg->user_id === auth()->id() ? 'rgba(10, 57, 74, 0.08)' : 'rgba(255, 255, 255, 0.85)' }}; border: 1px solid {{ $msg->user_id === auth()->id() ? 'rgba(10, 57, 74, 0.2)' : 'rgba(226, 232, 240, 0.8)' }}; border-radius: var(--radius-md); padding: 1.25rem;">
                                <div style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; margin-bottom: 0.35rem;">
                                    <span style="font-weight: 700; font-size: 0.88rem; color: #0f172a;">
                                        {{ $msg->user->name }}
                                        @if ($msg->user->isAdmin())
                                            <span style="font-size: 0.72rem; color: #0a394a; background: rgba(10, 57, 74, 0.1); padding: 0.15rem 0.45rem; border-radius: var(--radius-sm); font-weight: 800;">Care Manager</span>
                                        @endif
                                    </span>
                                    <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $msg->created_at->format('M d, h:i A') }}</span>
                                </div>
                                <div style="font-size: 0.92rem; color: var(--text-secondary); line-height: 1.6; white-space: pre-line;">
                                    {{ $msg->message }}
                                </div>
                                @if ($msg->attachment_path)
                                    <div style="margin-top: 0.75rem; padding-top: 0.5rem; border-top: 1px solid rgba(226, 232, 240, 0.8);">
                                        <a href="{{ Storage::url($msg->attachment_path) }}" target="_blank" style="font-size: 0.82rem; color: var(--brand-primary); font-weight: 600;">
                                            📎 View Attachment
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Reply Box -->
            @if ($ticket->status->value !== 'closed')
                <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
                    <h4 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem;">
                        Send Reply
                    </h4>
                    <form method="POST" action="{{ route('client.support.reply', $ticket->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div style="margin-bottom: 1rem;">
                            <textarea name="message" rows="4" required placeholder="Type your response to the Care Manager..." class="glass-input"></textarea>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                            <input type="file" name="attachment" style="font-size: 0.82rem;">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Send Reply
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
            <div style="font-size: 0.78rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 1rem;">
                Ticket Meta
            </div>

            <div style="margin-bottom: 0.75rem;">
                <div style="font-size: 0.8rem; color: var(--text-muted);">Status</div>
                <div style="margin-top: 0.25rem;">
                    <x-badge :tone="$ticket->status->value === 'resolved' || $ticket->status->value === 'closed' ? 'success' : 'info'">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status->value)) }}
                    </x-badge>
                </div>
            </div>

            <div style="margin-bottom: 0.75rem;">
                <div style="font-size: 0.8rem; color: var(--text-muted);">Category</div>
                <div style="font-weight: 700; color: #0f172a;">{{ ucfirst(str_replace('_', ' ', $ticket->category->value)) }}</div>
            </div>

            <div style="margin-bottom: 0.75rem;">
                <div style="font-size: 0.8rem; color: var(--text-muted);">Priority</div>
                <div style="font-weight: 700; color: #0f172a;">{{ ucfirst($ticket->priority->value) }}</div>
            </div>

            @if ($ticket->booking)
                <div style="border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 0.75rem; margin-top: 0.75rem;">
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Related Booking</div>
                    <a href="{{ route('client.bookings.show', $ticket->booking->id) }}" style="font-weight: 700; color: var(--brand-primary); font-size: 0.9rem;">
                        #{{ $ticket->booking->booking_reference }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.dashboard>
