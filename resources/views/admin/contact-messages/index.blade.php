@php
    $siteSettings ??= \App\Models\Setting::contactInfo();
@endphp
<x-layouts.dashboard>
    <x-slot:title>Care Desk Inquiries — Admin | CareMate BD</x-slot:title>
    <x-slot:header>Care Desk Inquiries</x-slot:header>
    <x-slot:subheading>Messages and inquiries submitted via the website contact form (dispatched to {{ $siteSettings['contact_email'] }}).</x-slot:subheading>

    @if (session('success'))
        <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.35); color: #065f46; padding: 0.9rem 1.25rem; border-radius: var(--radius-lg); margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; font-weight: 600; font-size: 0.9rem;">
            <span>✓ {{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; font-size: 1.1rem; cursor: pointer; color: #065f46;">✕</button>
        </div>
    @endif

    <!-- Top Stats & Filtering -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
            <span style="font-size: 0.88rem; color: var(--text-muted);">
                Total Inquiries: <strong style="color: #0f172a;">{{ $totalCount }}</strong>
                @if ($unreadCount > 0)
                    &bull; <span style="color: #e11d48; font-weight: 700;">{{ $unreadCount }} Unread</span>
                @endif
            </span>
        </div>

        <div style="font-size: 0.85rem; color: #0a394a; background: #e0f2fe; padding: 0.4rem 0.9rem; border-radius: 9999px; font-weight: 600;">
            ✉️ Email Dispatch: <strong>{{ $siteSettings['contact_email'] }}</strong>
        </div>
    </div>

    <!-- Main Card -->
    <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); width: 100%; box-sizing: border-box;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.contact-messages.index') }}" style="display: flex; gap: 0.75rem; flex: 1; max-width: 440px;">
                @if ($status)
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by sender, email, subject, phone..." class="glass-input" style="font-size: 0.85rem; padding: 0.5rem 0.85rem;">
                <button type="submit" class="btn btn-secondary btn-sm">Search</button>
                @if (request('search') || $status)
                    <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline btn-sm" style="font-size: 0.8rem; padding: 0.4rem 0.65rem;">Reset</a>
                @endif
            </form>

            <!-- Quick Filter Tabs -->
            <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                <a href="{{ route('admin.contact-messages.index', array_filter(['search' => request('search')])) }}" 
                   class="btn btn-sm {{ empty($status) ? 'btn-primary' : 'btn-secondary' }}" 
                   style="font-size: 0.78rem; padding: 0.35rem 0.75rem;">
                    All ({{ $totalCount }})
                </a>
                <a href="{{ route('admin.contact-messages.index', array_filter(['status' => 'unread', 'search' => request('search')])) }}" 
                   class="btn btn-sm {{ $status === 'unread' ? 'btn-primary' : 'btn-secondary' }}" 
                   style="font-size: 0.78rem; padding: 0.35rem 0.75rem;">
                    🔴 Unread ({{ $unreadCount }})
                </a>
                <a href="{{ route('admin.contact-messages.index', array_filter(['status' => 'read', 'search' => request('search')])) }}" 
                   class="btn btn-sm {{ $status === 'read' ? 'btn-primary' : 'btn-secondary' }}" 
                   style="font-size: 0.78rem; padding: 0.35rem 0.75rem;">
                    ✓ Read
                </a>
            </div>
        </div>

        <!-- Inquiries Table -->
        <div class="table-container">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th style="width: 90px;">Status</th>
                        <th>Sender Information</th>
                        <th>Subject & Message</th>
                        <th>Submitted At</th>
                        <th style="text-align: right; width: 220px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $msg)
                        <tr style="{{ !$msg->is_read ? 'background-color: rgba(240, 253, 250, 0.7);' : '' }}">
                            <td>
                                @if (!$msg->is_read)
                                    <span style="display: inline-block; padding: 0.25rem 0.6rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 800; background: #ffe4e6; color: #e11d48; text-transform: uppercase;">
                                        New
                                    </span>
                                @else
                                    <span style="display: inline-block; padding: 0.25rem 0.6rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 700; background: #f1f5f9; color: #64748b; text-transform: uppercase;">
                                        Read
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">
                                    {{ $msg->name }}
                                </div>
                                <div style="font-size: 0.82rem; margin-top: 2px;">
                                    <a href="mailto:{{ $msg->email }}" style="color: #15798e; text-decoration: none; font-weight: 600;">
                                        ✉️ {{ $msg->email }}
                                    </a>
                                </div>
                                @if ($msg->phone)
                                    <div style="font-size: 0.82rem; color: #475569; margin-top: 2px;">
                                        📞 <a href="tel:{{ $msg->phone }}" style="color: inherit; text-decoration: none;">{{ $msg->phone }}</a>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem; margin-bottom: 4px;">
                                    {{ $msg->subject }}
                                </div>
                                <div style="font-size: 0.82rem; color: #475569; line-height: 1.4; max-width: 480px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    {{ $msg->message }}
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.85rem; font-weight: 600; color: #0f172a;">
                                    {{ $msg->created_at->format('M d, Y') }}
                                </div>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">
                                    {{ $msg->created_at->format('h:i A') }}
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 0.4rem; justify-content: flex-end; align-items: center; flex-wrap: wrap;">
                                    <!-- View Modal Button -->
                                    <button type="button" 
                                            class="btn btn-secondary btn-sm" 
                                            style="font-size: 0.78rem; padding: 0.35rem 0.65rem;"
                                            onclick="openMessageModal({{ json_encode($msg) }})">
                                        View Full
                                    </button>

                                    <!-- Reply Mailto -->
                                    <a href="mailto:{{ $msg->email }}?subject=Re: {{ rawurlencode($msg->subject) }}" 
                                       class="btn btn-primary btn-sm" 
                                       style="font-size: 0.78rem; padding: 0.35rem 0.65rem;">
                                        Reply ↗
                                    </a>

                                    <!-- Toggle Read/Unread -->
                                    <form method="POST" action="{{ route('admin.contact-messages.toggle-read', $msg->id) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" 
                                                title="{{ $msg->is_read ? 'Mark as Unread' : 'Mark as Read' }}" 
                                                class="btn btn-outline btn-sm" 
                                                style="font-size: 0.78rem; padding: 0.35rem 0.55rem;">
                                            {{ $msg->is_read ? 'Mark Unread' : 'Mark Read' }}
                                        </button>
                                    </form>

                                    <!-- Delete -->
                                    <form method="POST" action="{{ route('admin.contact-messages.destroy', $msg->id) }}" style="display: inline;" onsubmit="return confirm('Delete this inquiry permanently?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Delete" 
                                                class="btn btn-outline btn-sm" 
                                                style="color: #ef4444; border-color: rgba(239, 68, 68, 0.3); font-size: 0.78rem; padding: 0.35rem 0.55rem;">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 3rem;">
                                No contact inquiries found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($messages->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $messages->links() }}
            </div>
        @endif
    </div>

    <!-- Inquiry Details Modal -->
    <div id="messageModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 1.25rem;">
        <div class="glass-card" style="background: #ffffff; border-radius: var(--radius-xl); max-width: 620px; width: 100%; padding: 2rem; box-shadow: 0 20px 40px rgba(0,0,0,0.2); position: relative; max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                <div>
                    <h3 id="modalSubject" style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0 0 0.35rem 0;"></h3>
                    <div id="modalDate" style="font-size: 0.82rem; color: var(--text-muted);"></div>
                </div>
                <button type="button" onclick="closeMessageModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b; line-height: 1;">✕</button>
            </div>

            <div style="background: #f8fafc; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem; border: 1px solid #e2e8f0;">
                <div style="display: grid; grid-template-columns: 120px 1fr; gap: 0.5rem 1rem; font-size: 0.88rem;">
                    <span style="font-weight: 700; color: #64748b;">Sender:</span>
                    <strong id="modalName" style="color: #0f172a;"></strong>

                    <span style="font-weight: 700; color: #64748b;">Email:</span>
                    <span id="modalEmail"></span>

                    <span style="font-weight: 700; color: #64748b;">Phone:</span>
                    <span id="modalPhone"></span>

                    <span style="font-weight: 700; color: #64748b;">IP Address:</span>
                    <span id="modalIp" style="color: var(--text-muted);"></span>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em; display: block; margin-bottom: 0.5rem;">
                    Message Content:
                </label>
                <div id="modalBody" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 10px; padding: 1rem 1.25rem; font-size: 0.92rem; line-height: 1.6; color: #1e293b; white-space: pre-wrap;"></div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="closeMessageModal()" class="btn btn-secondary">Close</button>
                <a id="modalReplyBtn" href="#" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.4rem;">
                    Reply via Email ↗
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openMessageModal(msg) {
                document.getElementById('modalSubject').textContent = msg.subject;
                document.getElementById('modalName').textContent = msg.name;
                document.getElementById('modalEmail').innerHTML = `<a href="mailto:${msg.email}" style="color: #15798e; font-weight: 600;">${msg.email}</a>`;
                document.getElementById('modalPhone').innerHTML = msg.phone ? `<a href="tel:${msg.phone}" style="color: #0f172a;">${msg.phone}</a>` : '<span style="color: #94a3b8; font-style: italic;">Not provided</span>';
                document.getElementById('modalIp').textContent = msg.ip_address || 'N/A';
                document.getElementById('modalDate').textContent = 'Received: ' + (new Date(msg.created_at)).toLocaleString();
                document.getElementById('modalBody').textContent = msg.message;
                document.getElementById('modalReplyBtn').href = `mailto:${msg.email}?subject=Re: ${encodeURIComponent(msg.subject)}`;
                
                document.getElementById('messageModal').style.display = 'flex';
            }

            function closeMessageModal() {
                document.getElementById('messageModal').style.display = 'none';
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeMessageModal();
            });
        </script>
    @endpush
</x-layouts.dashboard>
