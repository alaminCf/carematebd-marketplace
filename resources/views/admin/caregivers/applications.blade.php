<x-layouts.dashboard>
    <x-slot:title>Verification Queue — Admin | CareMate BD</x-slot:title>
    <x-slot:header>Caregiver Verification Queue</x-slot:header>
    <x-slot:subheading>Inspect NID credentials, background police certificates, and clinical diplomas before publishing.</x-slot:subheading>

    <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
        <!-- Status Filter Tabs -->
        <div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.75rem;">
            <a href="{{ route('admin.applications.index', ['status' => 'all']) }}" class="btn btn-sm {{ $status === 'all' ? 'btn-primary' : 'btn-secondary' }}">
                Active Queue
            </a>
            <a href="{{ route('admin.applications.index', ['status' => 'pending_verification']) }}" class="btn btn-sm {{ $status === 'pending_verification' ? 'btn-primary' : 'btn-secondary' }}">
                Pending Review
            </a>
            <a href="{{ route('admin.applications.index', ['status' => 'changes_required']) }}" class="btn btn-sm {{ $status === 'changes_required' ? 'btn-primary' : 'btn-secondary' }}">
                Changes Requested
            </a>
        </div>

        @if ($applications->isNotEmpty())
            <div class="table-container">
                <table class="glass-table">
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Care Specialty</th>
                            <th>Experience</th>
                            <th>Location</th>
                            <th>Submitted Date</th>
                            <th>Status</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $app)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <img src="{{ $app->avatarUrl() }}" alt="" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                                        <div>
                                            <div style="font-weight: 700; color: #0f172a;">{{ $app->user->name }}</div>
                                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $app->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $app->caregiver_type ?? 'Caregiver' }}</td>
                                <td>{{ $app->years_experience }} Years</td>
                                <td>{{ $app->city ?? 'Dhaka' }}</td>
                                <td style="font-size: 0.82rem; color: var(--text-muted);">
                                    {{ $app->submitted_at ? $app->submitted_at->format('M d, Y') : $app->created_at->format('M d, Y') }}
                                </td>
                                <td>
                                    <x-badge :tone="$app->status->badgeTone()">{{ $app->status->label() }}</x-badge>
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('admin.applications.show', $app->id) }}" class="btn btn-primary btn-sm">
                                        Inspect Documents →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1.5rem;">
                {{ $applications->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 4rem; color: var(--text-muted);">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🛡️</div>
                <h4 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">Queue is Empty</h4>
                <p>No caregiver applications are pending in this filter category.</p>
            </div>
        @endif
    </div>
</x-layouts.dashboard>
