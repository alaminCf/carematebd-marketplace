<x-layouts.dashboard>
    <x-slot:title>Triage Request #{{ $hiringRequest->request_reference }} — Admin | CareMate BD</x-slot:title>
    <x-slot:header>Request #{{ $hiringRequest->request_reference }} Mediation Chamber</x-slot:header>
    <x-slot:subheading>Admin Coordination Workspace: Review requirements, reassign caregiver, or confirm booking contract.</x-slot:subheading>

    <div style="display: grid; grid-template-columns: 1fr 380px; gap: 2rem; align-items: flex-start;" class="triage-layout">
        <!-- Main Dossier -->
        <div>
            <!-- Status & Summary -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span style="font-size: 0.78rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted);">Current Status</span>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-top: 0.25rem;">
                        {{ $hiringRequest->status->label() }}
                    </div>
                </div>
                <x-badge :tone="$hiringRequest->status->badgeTone()" style="font-size: 0.9rem; padding: 0.45rem 1.1rem;">
                    {{ $hiringRequest->status->label() }}
                </x-badge>
            </div>

            <!-- Patient Information Card -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                    Patient Medical & Physical Care Plan
                </h3>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; margin-bottom: 1.5rem;">
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Patient Name / Age</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $hiringRequest->care_recipient_name }} ({{ $hiringRequest->care_recipient_age }} yrs, {{ ucfirst($hiringRequest->care_recipient_gender) }})</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Family Relationship</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $hiringRequest->care_recipient_relationship }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Mobility Level</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $hiringRequest->mobility_status }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Care Service</div>
                        <div style="font-weight: 700; color: #0f172a;">{{ $hiringRequest->service->name }}</div>
                    </div>
                </div>

                @if ($hiringRequest->health_conditions)
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Health Conditions</div>
                        <div style="color: var(--text-secondary); font-size: 0.95rem; margin-top: 0.25rem;">
                            {{ $hiringRequest->health_conditions }}
                        </div>
                    </div>
                @endif

                @if ($hiringRequest->special_instructions)
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Special Instructions</div>
                        <div style="color: var(--text-secondary); font-size: 0.95rem; margin-top: 0.25rem; background: rgba(241, 245, 249, 0.7); padding: 0.85rem 1rem; border-radius: var(--radius-md);">
                            {{ $hiringRequest->special_instructions }}
                        </div>
                    </div>
                @endif

                <div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Duty Address</div>
                    <div style="font-weight: 600; color: #0f172a; margin-top: 0.25rem;">
                        📍 {{ $hiringRequest->care_address }}
                    </div>
                </div>
            </div>

            <!-- Coordination Notes Timeline -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl);">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem;">
                    Admin Coordination Notes & History
                </h3>

                <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
                    @forelse ($hiringRequest->notes as $note)
                        <div style="padding: 1rem; background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(226, 232, 240, 0.8); border-radius: var(--radius-md);">
                            <div style="display: flex; justify-content: space-between; font-size: 0.82rem; margin-bottom: 0.35rem;">
                                <strong style="color: #0f172a;">{{ $note->author->name ?? 'Admin Staff' }}</strong>
                                <span style="color: var(--text-muted);">{{ $note->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div style="font-size: 0.9rem; color: var(--text-secondary);">
                                {{ $note->note }}
                            </div>
                        </div>
                    @empty
                        <p style="color: var(--text-muted); font-size: 0.9rem;">No coordination notes added yet.</p>
                    @endforelse
                </div>

                <!-- Add Note Form -->
                <form method="POST" action="{{ route('admin.hiring-requests.note', $hiringRequest->id) }}">
                    @csrf
                    <div style="margin-bottom: 0.75rem;">
                        <textarea name="note" rows="2" required placeholder="Add internal case note..." class="glass-input"></textarea>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <label style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; color: var(--text-muted); cursor: pointer;">
                            <input type="checkbox" name="is_private" value="1" checked style="accent-color: var(--brand-primary);">
                            <span>Internal Note (Hidden from client/caregiver)</span>
                        </label>
                        <button type="submit" class="btn btn-secondary btn-sm">Add Note</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Mediation Actions & Financial Control -->
        <div>
            <!-- Client & Caregiver Info -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); margin-bottom: 1.5rem;">
                <h4 style="font-size: 0.8rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 1rem;">
                    Parties Involved
                </h4>

                <div style="margin-bottom: 1.25rem;">
                    <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Client Family</span>
                    <div style="font-weight: 800; color: #0f172a; font-size: 1.05rem;">{{ $hiringRequest->client->user->name }}</div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">📞 {{ $hiringRequest->client->user->phone }}</div>
                    <div style="font-size: 0.82rem; color: var(--text-muted);">📧 {{ $hiringRequest->client->user->email }}</div>
                </div>

                <div style="border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 1rem;">
                    <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Assigned Caregiver</span>
                    @if ($hiringRequest->caregiver)
                        <div style="font-weight: 800; color: #0f172a; font-size: 1.05rem;">{{ $hiringRequest->caregiver->user->name }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-secondary);">📞 {{ $hiringRequest->caregiver->user->phone }}</div>
                        <div style="font-size: 0.82rem; color: var(--text-muted);">Daily Rate: ৳{{ number_format($hiringRequest->caregiver->daily_rate) }}</div>
                    @else
                        <div style="color: #e11d48; font-weight: 700; font-size: 0.9rem;">No caregiver currently assigned.</div>
                    @endif
                </div>
            </div>

            <!-- Triage Actions Box -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); margin-bottom: 1.5rem;">
                <h4 style="font-size: 0.85rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 1.25rem;">
                    Workflow Actions
                </h4>

                @if ($hiringRequest->status->value === 'pending')
                    <!-- Approve & Send to Caregiver -->
                    <form method="POST" action="{{ route('admin.hiring-requests.approve', $hiringRequest->id) }}" style="margin-bottom: 1rem;">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            ✓ Approve & Dispatch to Caregiver
                        </button>
                    </form>
                @endif

                @if (in_array($hiringRequest->status->value, ['caregiver_accepted', 'admin_approved']))
                    <!-- Confirm Booking & Create Booking Contract -->
                    <form method="POST" action="{{ route('admin.hiring-requests.confirm', $hiringRequest->id) }}" style="margin-bottom: 1rem;">
                        @csrf
                        <button type="submit" class="btn btn-mint btn-lg" style="width: 100%;" onclick="return confirm('Confirm booking and create binding contract?')">
                            🎉 Confirm Booking & Contract
                        </button>
                    </form>
                @endif

                <!-- Reassign Caregiver Form -->
                <div style="border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 1rem; margin-top: 1rem;">
                    <h5 style="font-size: 0.85rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">Reassign Staff</h5>
                    <form method="POST" action="{{ route('admin.hiring-requests.reassign', $hiringRequest->id) }}">
                        @csrf
                        <div style="margin-bottom: 0.5rem;">
                            <select name="caregiver_id" required class="glass-input" style="font-size: 0.82rem;">
                                <option value="">Select Caregiver</option>
                                @foreach ($availableCaregivers as $acg)
                                    <option value="{{ $acg->id }}" {{ $hiringRequest->caregiver_id == $acg->id ? 'selected' : '' }}>
                                        {{ $acg->user->name }} (৳{{ number_format($acg->daily_rate) }}/d)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-secondary btn-sm" style="width: 100%;">
                            Reassign Caregiver
                        </button>
                    </form>
                </div>

                <!-- Reject -->
                @if (!in_array($hiringRequest->status->value, ['confirmed', 'rejected']))
                    <div style="border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 1rem; margin-top: 1rem;">
                        <form method="POST" action="{{ route('admin.hiring-requests.reject', $hiringRequest->id) }}">
                            @csrf
                            <input type="text" name="reason" required placeholder="Rejection reason..." class="glass-input" style="font-size: 0.82rem; margin-bottom: 0.5rem;">
                            <button type="submit" class="btn btn-danger btn-sm" style="width: 100%;">
                                Reject Request
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.dashboard>
