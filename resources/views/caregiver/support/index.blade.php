<x-layouts.dashboard>
    <x-slot:title>CareDesk Support — CareMate BD</x-slot:title>
    <x-slot:header>Caregiver Support Desk</x-slot:header>
    <x-slot:subheading>Reach your assigned Care Manager for schedule support, payment issues, or incident reporting.</x-slot:subheading>

    <div style="display: grid; grid-template-columns: 1fr 380px; gap: 2rem; align-items: flex-start;" class="support-layout">
        <!-- Tickets List -->
        <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
            <div style="font-weight: 700; font-size: 1.15rem; color: #0f172a; margin-bottom: 1.25rem;">
                Support Tickets ({{ $tickets->total() }})
            </div>

            @if ($tickets->isNotEmpty())
                <div class="table-container">
                    <table class="glass-table">
                        <thead>
                            <tr>
                                <th>Ticket #</th>
                                <th>Subject</th>
                                <th>Category</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Updated</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tickets as $t)
                                <tr>
                                    <td style="font-weight: 700; color: var(--brand-primary);">
                                        #{{ $t->ticket_number }}
                                    </td>
                                    <td style="font-weight: 600; color: #0f172a;">
                                        {{ Str::limit($t->subject, 30) }}
                                    </td>
                                    <td style="font-size: 0.85rem;">{{ ucfirst(str_replace('_', ' ', $t->category->value)) }}</td>
                                    <td>
                                        <x-badge :tone="$t->priority->value === 'urgent' ? 'danger' : ($t->priority->value === 'high' ? 'warning' : 'neutral')">
                                            {{ ucfirst($t->priority->value) }}
                                        </x-badge>
                                    </td>
                                    <td>
                                        <x-badge :tone="$t->status->value === 'resolved' || $t->status->value === 'closed' ? 'success' : 'info'">
                                            {{ ucfirst(str_replace('_', ' ', $t->status->value)) }}
                                        </x-badge>
                                    </td>
                                    <td style="font-size: 0.8rem; color: var(--text-muted);">
                                        {{ $t->updated_at->diffForHumans() }}
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="{{ route('caregiver.support.show', $t->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.8rem;">
                                            Open
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 1.5rem;">
                    {{ $tickets->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 3rem; color: var(--text-muted);">
                    <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">💬</div>
                    <p>No support tickets opened.</p>
                </div>
            @endif
        </div>

        <!-- Create New Ticket Card -->
        <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
            <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">
                Open Support Ticket
            </h3>

            <form method="POST" action="{{ route('caregiver.support.store') }}" enctype="multipart/form-data">
                @csrf

                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Category <span style="color: #ef4444;">*</span></label>
                    <select name="category" required class="glass-input">
                        <option value="booking">Job & Duty Location</option>
                        <option value="payment">Payout & Bank Wallet</option>
                        <option value="emergency">Emergency at Patient Home</option>
                        <option value="general">Verification / Account</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Related Job</label>
                    <select name="booking_id" class="glass-input">
                        <option value="">None / General Inquiry</option>
                        @foreach ($caregiverJobs as $cj)
                            <option value="{{ $cj->id }}">#{{ $cj->booking_reference }} ({{ $cj->service->name }})</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Priority <span style="color: #ef4444;">*</span></label>
                    <select name="priority" required class="glass-input">
                        <option value="normal">Normal</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Subject <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="subject" required placeholder="Brief summary" class="glass-input">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Message Details <span style="color: #ef4444;">*</span></label>
                    <textarea name="message" rows="4" required placeholder="Describe your question or situation..." class="glass-input"></textarea>
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label class="form-label">Optional Attachment</label>
                    <input type="file" name="attachment" class="glass-input">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Send to Care Desk
                </button>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
