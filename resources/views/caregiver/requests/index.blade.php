<x-layouts.dashboard>
    <x-slot:title>Care Invitations & Requests — CareMate BD</x-slot:title>
    <x-slot:header>Care Requests & Job Invitations</x-slot:header>
    <x-slot:subheading>Assignments dispatched to you by CareMate Care Managers. Review patient care plans and confirm acceptance.</x-slot:subheading>

    <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
        <div style="font-weight: 700; font-size: 1.15rem; color: #0f172a; margin-bottom: 1.5rem;">
            Assigned Care Requests ({{ $requests->total() }})
        </div>

        @if ($requests->isNotEmpty())
            <div class="table-container">
                <table class="glass-table">
                    <thead>
                        <tr>
                            <th>Request #</th>
                            <th>Service</th>
                            <th>Schedule</th>
                            <th>Shift Type</th>
                            <th>Duration</th>
                            <th>Net Pay (৳)</th>
                            <th>Status</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $r)
                            <tr>
                                <td style="font-weight: 700; color: var(--brand-primary);">
                                    #{{ $r->request_reference }}
                                </td>
                                <td>{{ $r->service->name }}</td>
                                <td style="font-size: 0.85rem;">
                                    {{ $r->start_date->format('M d') }} - {{ $r->end_date->format('M d, Y') }}
                                </td>
                                <td>{{ ucfirst(str_replace('_', ' ', $r->shift_type)) }}</td>
                                <td>{{ $r->total_days }} Days</td>
                                <td style="font-weight: 800; color: #059669;">
                                    ৳{{ number_format($r->caregiver_net_amount) }}
                                </td>
                                <td>
                                    <x-badge :tone="$r->status->badgeTone()">{{ $r->status->label() }}</x-badge>
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('caregiver.requests.show', $r->id) }}" class="btn btn-primary btn-sm" style="font-size: 0.8rem;">
                                        Review Job
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1.5rem;">
                {{ $requests->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 3.5rem; color: var(--text-muted);">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📬</div>
                <h4 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">No Pending Invitations</h4>
                <p>You have responded to all assigned care requests. New invitations will alert you here.</p>
            </div>
        @endif
    </div>
</x-layouts.dashboard>
