<x-layouts.dashboard>
    <x-slot:title>My Active & Completed Jobs — CareMate BD</x-slot:title>
    <x-slot:header>My Care Jobs</x-slot:header>
    <x-slot:subheading>Manage your confirmed patient care appointments, daily attendance logs, and job details.</x-slot:subheading>

    <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
        <div style="font-weight: 700; font-size: 1.15rem; color: #0f172a; margin-bottom: 1.5rem;">
            Care Assignments ({{ $jobs->total() }})
        </div>

        @if ($jobs->isNotEmpty())
            <div class="table-container">
                <table class="glass-table">
                    <thead>
                        <tr>
                            <th>Booking Ref</th>
                            <th>Service</th>
                            <th>Patient / Recipient</th>
                            <th>Schedule</th>
                            <th>Shift Type</th>
                            <th>Status</th>
                            <th>Net Pay (৳)</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobs as $job)
                            <tr>
                                <td style="font-weight: 700; color: var(--brand-primary);">
                                    #{{ $job->booking_reference }}
                                </td>
                                <td>{{ $job->service->name }}</td>
                                <td>
                                    <span style="font-weight: 700; color: #0f172a;">{{ $job->care_recipient_name }}</span>
                                    <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">
                                        ({{ $job->care_recipient_age }} yrs, {{ ucfirst($job->care_recipient_gender) }})
                                    </span>
                                </td>
                                <td style="font-size: 0.85rem;">
                                    {{ $job->start_date->format('M d') }} - {{ $job->end_date->format('M d, Y') }}
                                </td>
                                <td>{{ ucfirst(str_replace('_', ' ', $job->shift_type)) }}</td>
                                <td>
                                    <x-badge :tone="$job->status->badgeTone()">{{ $job->status->label() }}</x-badge>
                                </td>
                                <td style="font-weight: 800; color: #059669;">
                                    ৳{{ number_format($job->caregiver_net_pay) }}
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('caregiver.jobs.show', $job->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.8rem;">
                                        Duty Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1.5rem;">
                {{ $jobs->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 3.5rem; color: var(--text-muted);">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">💼</div>
                <h4 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">No Active Care Jobs</h4>
                <p>When you accept a care request and the client locks payment in escrow, the booking appears here.</p>
            </div>
        @endif
    </div>
</x-layouts.dashboard>
