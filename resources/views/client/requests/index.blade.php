<x-layouts.dashboard>
    <x-slot:title>My Care Requests — CareMate BD</x-slot:title>
    <x-slot:header>My Care Hiring Requests</x-slot:header>
    <x-slot:subheading>Track submitted hiring requests, CareMate Admin triage status, and caregiver assignment progress.</x-slot:subheading>

    <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <div style="font-weight: 700; font-size: 1.1rem; color: #0f172a;">
                All Submitted Requests ({{ $requests->total() }})
            </div>
            <a href="{{ route('marketplace.index') }}" class="btn btn-primary btn-sm">
                + New Care Request
            </a>
        </div>

        @if ($requests->isNotEmpty())
            <div class="table-container">
                <table class="glass-table">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Service</th>
                            <th>Caregiver</th>
                            <th>Care Recipient</th>
                            <th>Dates</th>
                            <th>Status</th>
                            <th>Total (Est)</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $req)
                            <tr>
                                <td style="font-weight: 700; color: var(--brand-primary);">
                                    #{{ $req->request_reference }}
                                </td>
                                <td>{{ $req->service->name }}</td>
                                <td>
                                    @if ($req->caregiver)
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <img src="{{ $req->caregiver->avatarUrl() }}" alt="" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover;">
                                            <span style="font-weight: 600;">{{ $req->caregiver->user->name }}</span>
                                        </div>
                                    @else
                                        <span style="color: var(--text-muted);">Admin Choice</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $req->care_recipient_name }}
                                    <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">
                                        ({{ $req->care_recipient_relationship }}, {{ $req->care_recipient_age }} yrs)
                                    </span>
                                </td>
                                <td style="font-size: 0.85rem;">
                                    {{ $req->start_date->format('M d') }} - {{ $req->end_date->format('M d, Y') }}
                                </td>
                                <td>
                                    <x-badge :tone="$req->status->badgeTone()">{{ $req->status->label() }}</x-badge>
                                </td>
                                <td style="font-weight: 700;">
                                    ৳{{ number_format($req->client_quoted_amount) }}
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('client.requests.show', $req->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.8rem;">
                                        View Details
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
            <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">📋</div>
                <h4 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">No Care Requests Yet</h4>
                <p style="margin-bottom: 1.5rem;">Ready to find a trusted caregiver for your family?</p>
                <a href="{{ route('marketplace.index') }}" class="btn btn-primary">Find a Caregiver</a>
            </div>
        @endif
    </div>
</x-layouts.dashboard>
