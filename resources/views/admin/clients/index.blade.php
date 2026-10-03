<x-layouts.dashboard>
    <x-slot:title>Manage Family Clients — Admin | CareMate BD</x-slot:title>
    <x-slot:header>Registered Family Clients</x-slot:header>
    <x-slot:subheading>Manage verified families, emergency contact records, and account statuses.</x-slot:subheading>

    <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <form method="GET" action="{{ route('admin.clients.index') }}" style="display: flex; gap: 0.75rem; flex: 1; max-width: 450px;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, email, phone, city..." class="glass-input" style="font-size: 0.85rem; padding: 0.45rem 0.8rem;">
                <button type="submit" class="btn btn-secondary btn-sm">Search</button>
            </form>
        </div>

        <div class="table-container">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Contact Phone</th>
                        <th>Care Location</th>
                        <th>Emergency Contact</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $c)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <img src="{{ $c->user->avatarUrl() }}" alt="" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a;">{{ $c->user->name }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $c->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-family: monospace;">{{ $c->user->phone }}</td>
                            <td>{{ $c->area?->name ?? $c->city }}, {{ $c->district?->name }}</td>
                            <td style="font-size: 0.85rem;">
                                @if ($c->emergency_contact_name)
                                    {{ $c->emergency_contact_name }} ({{ $c->emergency_contact_phone }})
                                @else
                                    <span style="color: var(--text-muted);">Not provided</span>
                                @endif
                            </td>
                            <td>
                                <x-badge :tone="$c->user->isSuspended() ? 'danger' : 'success'">
                                    {{ $c->user->isSuspended() ? 'Suspended' : 'Active' }}
                                </x-badge>
                            </td>
                            <td style="font-size: 0.82rem; color: var(--text-muted);">{{ $c->created_at->format('M d, Y') }}</td>
                            <td style="text-align: right;">
                                <a href="{{ route('admin.clients.show', $c->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.8rem;">
                                    Profile →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 3rem;">No clients found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $clients->links() }}
        </div>
    </div>
</x-layouts.dashboard>
