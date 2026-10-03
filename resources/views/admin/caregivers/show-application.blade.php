<x-layouts.dashboard>
    <x-slot:title>Verification Application — {{ $caregiver->user->name }} | CareMate BD</x-slot:title>
    <x-slot:header>Caregiver Verification Desk: {{ $caregiver->user->name }}</x-slot:header>
    <x-slot:subheading>Inspect uploaded NID credentials, police documents, and verify profile credentials.</x-slot:subheading>

    <div style="display: grid; grid-template-columns: 1fr 360px; gap: 2rem; align-items: flex-start;" class="inspection-layout">
        <!-- Main Verification Dossier -->
        <div>
            <!-- Status & Summary -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <div style="display: flex; gap: 1.5rem; align-items: flex-start; margin-bottom: 1.5rem;">
                    <img src="{{ $caregiver->avatarUrl() }}" alt="{{ $caregiver->user->name }}" style="width: 84px; height: 84px; border-radius: 50%; object-fit: cover; border: 3px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.25rem;">
                            <h2 style="font-size: 1.6rem; font-weight: 800; color: #0f172a;">{{ $caregiver->user->name }}</h2>
                            <x-badge :tone="$caregiver->status->badgeTone()">{{ $caregiver->status->label() }}</x-badge>
                        </div>
                        <div style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                            {{ $caregiver->caregiver_type }} • {{ $caregiver->years_experience }} Years Clinical / In-home Experience
                        </div>
                        <div style="font-size: 0.85rem; color: var(--text-muted);">
                            📧 {{ $caregiver->user->email }} • 📞 {{ $caregiver->user->phone }}
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 1rem; font-size: 0.85rem;">
                    <div>
                        <span style="color: var(--text-muted); display: block;">National ID (Encrypted):</span>
                        <strong style="color: #0f172a; font-family: monospace;">{{ $caregiver->nid_number }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block;">Gender / Age:</span>
                        <strong style="color: #0f172a;">{{ ucfirst($caregiver->gender ?? 'Not Specified') }} ({{ $caregiver->age() ? $caregiver->age().' yrs' : 'N/A' }})</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block;">Requested Daily Rate:</span>
                        <strong style="color: #059669;">৳{{ number_format($caregiver->daily_rate) }}/day</strong>
                    </div>
                </div>
            </div>

            <!-- Uploaded Verification Documents Streamer -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                    1. National Identity & Legal Credentials
                </h3>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @forelse ($caregiver->documents as $doc)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(226, 232, 240, 0.9); border-radius: var(--radius-md);">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="font-size: 1.5rem;">🪪</div>
                                <div>
                                    <div style="font-weight: 700; color: #0f172a;">{{ $doc->title }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                                        Type: {{ strtoupper(str_replace('_', ' ', $doc->type->value)) }} • Uploaded: {{ $doc->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <x-badge :tone="$doc->status->value === 'approved' ? 'success' : 'warning'">{{ ucfirst($doc->status->value) }}</x-badge>
                                <a href="{{ route('secure.document', $doc->uuid) }}" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 0.8rem;">
                                    View Document ↗
                                </a>
                            </div>
                        </div>
                    @empty
                        <p style="color: var(--text-muted);">No identity documents attached to this profile.</p>
                    @endforelse
                </div>
            </div>

            <!-- Certifications & Training -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                    2. Clinical Certifications
                </h3>

                @forelse ($caregiver->certificates as $cert)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(226, 232, 240, 0.9); border-radius: var(--radius-md); margin-bottom: 0.75rem;">
                        <div>
                            <div style="font-weight: 700; color: #0f172a;">{{ $cert->name }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $cert->institution }} • Reg: {{ $cert->certificate_number ?? 'N/A' }} ({{ $cert->issue_year }})</div>
                        </div>
                        <a href="{{ route('secure.certificate', $cert->uuid) }}" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 0.8rem;">
                            View Certificate ↗
                        </a>
                    </div>
                @empty
                    <p style="color: var(--text-muted);">No clinical certificates recorded.</p>
                @endforelse
            </div>

            <!-- Address & Emergency Contact -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                    3. Address & Family Emergency Verification
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">Present Residence</span>
                        <div style="font-weight: 600; color: #0f172a; margin-top: 0.25rem;">{{ $caregiver->present_address }}</div>
                    </div>
                    <div>
                        <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">Permanent NID Address</span>
                        <div style="font-weight: 600; color: #0f172a; margin-top: 0.25rem;">{{ $caregiver->permanent_address }}</div>
                    </div>
                </div>

                <div style="background: rgba(241, 245, 249, 0.7); padding: 1rem 1.25rem; border-radius: var(--radius-md);">
                    <div style="font-weight: 700; font-size: 0.9rem; color: #0f172a; margin-bottom: 0.25rem;">
                        Emergency Relative Contact: {{ $caregiver->emergency_contact_name }} ({{ $caregiver->emergency_contact_relationship }})
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">
                        Phone: <strong style="font-family: monospace;">{{ $caregiver->emergency_contact_phone }}</strong>
                    </div>
                </div>
            </div>

            <!-- Verification Action Audit Trail -->
            @if ($caregiver->verificationLogs->isNotEmpty())
                <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl);">
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">
                        Verification Decision History
                    </h3>

                    @foreach ($caregiver->verificationLogs as $vl)
                        <div style="border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding: 0.75rem 0;">
                            <div style="display: flex; justify-content: space-between; font-size: 0.85rem;">
                                <strong style="color: #0f172a;">Action: {{ strtoupper($vl->action) }}</strong>
                                <span style="color: var(--text-muted);">{{ $vl->created_at->format('M d, Y h:i A') }} by {{ $vl->admin->name ?? 'Admin' }}</span>
                            </div>
                            @if ($vl->notes)
                                <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.25rem;">
                                    Notes: "{{ $vl->notes }}"
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Sidebar Verification Controls (Sticky) -->
        <div style="position: sticky; top: 90px;">
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); box-shadow: var(--glass-shadow-lg);">
                <h4 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem;">
                    Admin Verification Actions
                </h4>

                <!-- Action 1: Approve and Publish -->
                <form method="POST" action="{{ route('admin.applications.approve', $caregiver->id) }}" style="margin-bottom: 1.5rem;">
                    @csrf
                    <button type="submit" class="btn btn-mint btn-lg" style="width: 100%;" onclick="return confirm('Approve this caregiver and publish their profile to the public marketplace?')">
                        ✓ Approve & Publish Profile
                    </button>
                </form>

                <!-- Action 2: Request Changes -->
                <div style="border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 1.25rem; margin-bottom: 1.5rem;">
                    <h5 style="font-size: 0.95rem; font-weight: 700; color: #d97706; margin-bottom: 0.5rem;">
                        Request Document Changes
                    </h5>
                    <form method="POST" action="{{ route('admin.applications.request-changes', $caregiver->id) }}">
                        @csrf
                        <div style="margin-bottom: 0.75rem;">
                            <textarea name="notes" rows="3" required placeholder="Specify what document or information needs re-uploading..." class="glass-input" style="font-size: 0.85rem;"></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning btn-sm" style="width: 100%;">
                            Notify Caregiver to Fix
                        </button>
                    </form>
                </div>

                <!-- Action 3: Reject Application -->
                <div style="border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 1.25rem;">
                    <h5 style="font-size: 0.95rem; font-weight: 700; color: #e11d48; margin-bottom: 0.5rem;">
                        Reject Application
                    </h5>
                    <form method="POST" action="{{ route('admin.applications.reject', $caregiver->id) }}">
                        @csrf
                        <div style="margin-bottom: 0.75rem;">
                            <input type="text" name="reason" required placeholder="Rejection reason (failed check, false info)..." class="glass-input" style="font-size: 0.85rem;">
                        </div>
                        <button type="submit" class="btn btn-danger btn-sm" style="width: 100%;" onclick="return confirm('Reject this caregiver application?')">
                            ✕ Permanently Reject
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
