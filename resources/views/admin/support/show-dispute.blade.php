<x-layouts.dashboard>
    <x-slot:title>Dispute #{{ $dispute->reference }} — Admin Adjudication | CareMate BD</x-slot:title>
    <x-slot:header>Dispute Case #{{ $dispute->reference }}</x-slot:header>
    <x-slot:subheading>Adjudication & Escrow Mediation for Booking #{{ $dispute->booking->booking_reference }}</x-slot:subheading>

    <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
        <a href="{{ route('admin.disputes.index') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 0.4rem;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            Back to Disputes
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 360px; gap: 2rem; align-items: flex-start;" class="admin-dispute-layout">
        <!-- Main Case Dossier -->
        <div>
            <!-- Status & Overview -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 1.25rem;">
                    <div>
                        <span style="font-size: 0.78rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted);">Formal Grievance Category</span>
                        <h2 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0.2rem 0 0 0;">
                            {{ $dispute->category?->label() ?? 'Service Dispute' }}
                        </h2>
                    </div>
                    <x-badge :tone="$dispute->status->tone()" style="font-size: 0.9rem; padding: 0.4rem 1rem;">
                        {{ $dispute->status->label() }}
                    </x-badge>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <div style="font-size: 0.82rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem;">
                        Filed by: {{ $dispute->reporter->name }} ({{ ucfirst($dispute->reporter->role->value) }}) on {{ $dispute->created_at->format('M d, Y h:i A') }}
                    </div>
                    <div style="font-size: 1rem; color: #1e293b; line-height: 1.6; background: rgba(248, 250, 252, 0.8); border: 1px solid rgba(226, 232, 240, 0.8); border-radius: var(--radius-lg); padding: 1.25rem; white-space: pre-line;">
                        {{ $dispute->description }}
                    </div>
                </div>

                @if ($dispute->evidence_uuid)
                    <div style="margin-top: 1rem; padding: 1rem; border-radius: var(--radius-md); background: rgba(10, 57, 74, 0.05); display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.88rem; font-weight: 600; color: #0a394a;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                            <span>Evidence Attachment: {{ $dispute->evidence_name ?? 'Documentation' }}</span>
                        </div>
                        <a href="{{ route('secure.document', $dispute->evidence_uuid) }}" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 0.78rem;">
                            View File ↗
                        </a>
                    </div>
                @endif
            </div>

            <!-- Adjudication Resolution Decision -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem;">
                    Adjudication & Final Decision
                </h3>

                @if ($dispute->resolution)
                    <div style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 1.5rem;">
                        <div style="font-weight: 800; color: #065f46; font-size: 0.95rem; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                            Resolution Executed on {{ $dispute->resolved_at?->format('M d, Y h:i A') }}
                        </div>
                        <div style="font-size: 0.95rem; color: #047857; line-height: 1.6; white-space: pre-line;">
                            {{ $dispute->resolution }}
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('admin.support.disputes.resolve', $dispute->id) }}">
                        @csrf
                        <div style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.4rem;">
                                Formal Mediation Outcome & Escrow Disposition *
                            </label>
                            <textarea name="resolution" rows="4" required minlength="10" placeholder="State findings, escrow refund or payout decision, and final policy enforcement..." class="glass-textarea" style="width: 100%; font-size: 0.9rem;"></textarea>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <label style="font-size: 0.82rem; font-weight: 700; color: #0f172a;">Closing Status:</label>
                                <select name="status" class="glass-select" style="font-size: 0.85rem; padding: 0.35rem 0.75rem;">
                                    <option value="resolved">Resolved (Settlement Reached)</option>
                                    <option value="rejected">Rejected (Claim Unsubstantiated)</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary" onclick="return confirm('Confirm and finalize this dispute decision?');">
                                Commit Dispute Settlement
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            <!-- Internal Staff Investigation Notes -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl);">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem;">
                    Internal Staff Investigation Log ({{ $dispute->notes->count() }})
                </h3>

                @forelse ($dispute->notes as $note)
                    <div style="background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(226, 232, 240, 0.8); border-radius: var(--radius-md); padding: 1rem 1.25rem; margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.35rem;">
                            <strong style="color: #0f172a;">{{ $note->admin->name }} (Care Coordinator)</strong>
                            <span>{{ $note->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <div style="font-size: 0.92rem; color: #334155; line-height: 1.5;">
                            {{ $note->note }}
                        </div>
                    </div>
                @empty
                    <div style="color: var(--text-muted); font-size: 0.88rem; margin-bottom: 1.5rem; font-style: italic;">
                        No internal staff notes recorded yet.
                    </div>
                @endforelse

                <form method="POST" action="{{ route('admin.support.disputes.note', $dispute->id) }}" style="margin-top: 1.5rem;">
                    @csrf
                    <div style="margin-bottom: 0.75rem;">
                        <textarea name="note" rows="2" required placeholder="Add private investigation note or call log with parties..." class="glass-textarea" style="width: 100%; font-size: 0.88rem;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-secondary btn-sm">
                        + Add Staff Log Note
                    </button>
                </form>
            </div>
        </div>

        <!-- Sidebar: Involved Parties & Contract Context -->
        <div>
            <!-- Booking Context -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); margin-bottom: 1.5rem;">
                <h4 style="font-size: 0.8rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 1rem;">
                    Associated Booking
                </h4>

                <div style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">
                    {{ $dispute->booking->service->name }}
                </div>
                <div style="font-size: 0.85rem; color: #0a394a; font-family: monospace; font-weight: 700; margin-bottom: 1rem;">
                    #{{ $dispute->booking->booking_reference }}
                </div>

                <div style="display: flex; justify-content: space-between; font-size: 0.88rem; margin-bottom: 0.5rem; color: var(--text-secondary);">
                    <span>Contract Escrow:</span>
                    <strong style="color: #0f172a;">৳{{ number_format($dispute->booking->total_amount, 0) }}</strong>
                </div>

                <div style="display: flex; justify-content: space-between; font-size: 0.88rem; margin-bottom: 0.5rem; color: var(--text-secondary);">
                    <span>Service Dates:</span>
                    <strong style="color: #0f172a;">{{ $dispute->booking->start_date->format('M d') }} - {{ $dispute->booking->end_date->format('M d, Y') }}</strong>
                </div>

                <div style="margin-top: 1rem;">
                    <a href="{{ route('admin.bookings.show', $dispute->booking->id) }}" class="btn btn-secondary btn-sm" style="width: 100%; justify-content: center;">
                        Inspect Booking Details →
                    </a>
                </div>
            </div>

            <!-- Client Dossier -->
            <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl); margin-bottom: 1.5rem;">
                <h4 style="font-size: 0.8rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 0.75rem;">
                    Client Contact
                </h4>
                <div style="font-weight: 800; color: #0f172a;">{{ $dispute->booking->client->user->name }}</div>
                <div style="font-size: 0.8rem; color: var(--text-muted); font-family: monospace;">{{ $dispute->booking->client->user->phone }}</div>
                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $dispute->booking->client->user->email }}</div>
            </div>

            <!-- Caregiver Dossier -->
            <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
                <h4 style="font-size: 0.8rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 0.75rem;">
                    Caregiver Contact
                </h4>
                <div style="font-weight: 800; color: #0f172a;">{{ $dispute->booking->caregiver->user->name }}</div>
                <div style="font-size: 0.8rem; color: var(--text-muted); font-family: monospace;">{{ $dispute->booking->caregiver->user->phone }}</div>
                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $dispute->booking->caregiver->user->email }}</div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
