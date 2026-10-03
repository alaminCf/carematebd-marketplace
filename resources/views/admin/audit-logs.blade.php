<x-layouts.dashboard>
    <x-slot:title>Security Audit Trail — Admin | CareMate BD</x-slot:title>
    <x-slot:header>Platform Audit & Governance Ledger</x-slot:header>
    <x-slot:subheading>Tamper-evident record of administrator decisions, caregiver approvals, status overrides, and financial disbursements.</x-slot:subheading>

    <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
        <div class="table-container">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Admin Operator</th>
                        <th>Action Logged</th>
                        <th>Target Entity</th>
                        <th>State Changes / Notes</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td style="font-size: 0.8rem; color: var(--text-muted); white-space: nowrap;">
                                {{ $log->created_at->format('M d, Y') }}
                                <div style="font-family: monospace; font-size: 0.75rem; color: #0f172a;">{{ $log->created_at->format('h:i:s A') }}</div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.6rem;">
                                    @if ($log->admin)
                                        <img src="{{ $log->admin->avatarUrl() }}" alt="" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                        <div>
                                            <div style="font-weight: 700; color: #0f172a; font-size: 0.88rem;">{{ $log->admin->name }}</div>
                                            <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $log->admin->email }}</div>
                                        </div>
                                    @else
                                        <div style="font-style: italic; color: var(--text-muted); font-size: 0.85rem;">Automated System</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <code style="font-size: 0.82rem; font-weight: 700; color: #0a394a; background: rgba(10, 57, 74, 0.08); padding: 0.25rem 0.6rem; border-radius: 6px;">
                                    {{ $log->action }}
                                </code>
                            </td>
                            <td style="font-size: 0.85rem;">
                                @if ($log->entity_type)
                                    <span style="font-weight: 600; color: #0f172a;">{{ class_basename($log->entity_type) }}</span>
                                    <span style="font-family: monospace; color: var(--text-muted);">#{{ $log->entity_id }}</span>
                                @else
                                    <span style="color: var(--text-muted);">General</span>
                                @endif
                            </td>
                            <td style="font-size: 0.85rem; max-width: 320px;">
                                @if ($log->reason)
                                    <div style="color: #0f172a; font-weight: 600; margin-bottom: 0.25rem;">
                                        "{{ $log->reason }}"
                                    </div>
                                @endif

                                @if (!empty($log->new_state))
                                    <details style="font-size: 0.75rem; color: var(--text-secondary); cursor: pointer;">
                                        <summary style="color: #0a394a; font-weight: 600;">Inspect Payload Diff</summary>
                                        <div style="margin-top: 0.4rem; background: rgba(15, 23, 42, 0.04); padding: 0.5rem; border-radius: 6px; font-family: monospace; white-space: pre-wrap; word-break: break-all;">
@if(!empty($log->previous_state))Previous: {{ json_encode($log->previous_state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
@endif
New: {{ json_encode($log->new_state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</div>
                                    </details>
                                @endif
                            </td>
                            <td style="font-family: monospace; font-size: 0.78rem; color: var(--text-muted);">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 3rem;">No administrative actions recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $logs->links() }}
        </div>
    </div>
</x-layouts.dashboard>
