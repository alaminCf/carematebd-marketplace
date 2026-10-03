<x-layouts.dashboard>
    <x-slot:title>Caregiver Payouts — Admin Financials | CareMate BD</x-slot:title>
    <x-slot:header>Caregiver Earnings & Payout Disbursements</x-slot:header>
    <x-slot:subheading>Review withdrawal requests, verify completed duty escrow balances, and disburse via bKash, Nagad, or Bank Wire.</x-slot:subheading>

    <!-- Stats Row -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
            <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); margin-bottom: 0.35rem;">Pending Payout Requests</div>
            <div style="font-size: 1.7rem; font-weight: 800; color: #f59e0b;">{{ $stats['pending_payouts'] }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Awaiting administrative signoff</div>
        </div>

        <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
            <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); margin-bottom: 0.35rem;">Pending Withdrawal Amount</div>
            <div style="font-size: 1.7rem; font-weight: 800; color: #0f172a;">৳{{ number_format($stats['pending_amount'], 0) }}</div>
            <div style="font-size: 0.75rem; color: #0a394a; font-weight: 600; margin-top: 0.25rem;">In processing pipeline</div>
        </div>

        <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
            <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); margin-bottom: 0.35rem;">Total Disbursed to Date</div>
            <div style="font-size: 1.7rem; font-weight: 800; color: #059669;">৳{{ number_format($stats['paid_amount'], 0) }}</div>
            <div style="font-size: 0.75rem; color: #059669; font-weight: 600; margin-top: 0.25rem;">Successfully paid caregivers</div>
        </div>
    </div>

    <!-- Payouts Table -->
    <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
        <!-- Filters -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <a href="{{ route('admin.payouts.index') }}" class="btn btn-sm {{ !$status ? 'btn-primary' : 'btn-secondary' }}">
                    All Payouts
                </a>
                <a href="{{ route('admin.payouts.index', ['status' => 'requested']) }}" class="btn btn-sm {{ $status === 'requested' ? 'btn-primary' : 'btn-secondary' }}">
                    Requested
                </a>
                <a href="{{ route('admin.payouts.index', ['status' => 'approved']) }}" class="btn btn-sm {{ $status === 'approved' ? 'btn-primary' : 'btn-secondary' }}">
                    Approved (Ready)
                </a>
                <a href="{{ route('admin.payouts.index', ['status' => 'paid']) }}" class="btn btn-sm {{ $status === 'paid' ? 'btn-primary' : 'btn-secondary' }}">
                    Paid (Completed)
                </a>
                <a href="{{ route('admin.payouts.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ $status === 'rejected' ? 'btn-primary' : 'btn-secondary' }}">
                    Rejected
                </a>
            </div>
        </div>

        <div class="table-container">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>Payout Ref</th>
                        <th>Caregiver</th>
                        <th>Channel & Account</th>
                        <th>Requested Amount</th>
                        <th>Status</th>
                        <th>Requested On</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payouts as $po)
                        <tr>
                            <td style="font-family: monospace; font-weight: 700; color: #0f172a;">
                                {{ $po->reference }}
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <img src="{{ $po->caregiver->user->avatarUrl() }}" alt="" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a;">{{ $po->caregiver->user->name }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted); font-family: monospace;">{{ $po->caregiver->user->phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 700; text-transform: uppercase; color: #0f172a; font-size: 0.85rem;">
                                    {{ $po->method }}
                                </div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary); font-family: monospace;">
                                    {{ $po->account_details }}
                                </div>
                            </td>
                            <td style="font-size: 1.05rem; font-weight: 800; color: #0f172a;">
                                ৳{{ number_format($po->amount, 0) }}
                            </td>
                            <td>
                                <x-badge :tone="$po->status->tone()">
                                    {{ $po->status->label() }}
                                </x-badge>
                                @if ($po->transaction_reference)
                                    <div style="font-size: 0.72rem; color: var(--text-muted); font-family: monospace; margin-top: 0.2rem;">
                                        Trx: {{ $po->transaction_reference }}
                                    </div>
                                @endif
                            </td>
                            <td style="font-size: 0.8rem; color: var(--text-muted);">
                                {{ $po->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.4rem; align-items: center;">
                                    @if ($po->status->value === 'requested')
                                        <form method="POST" action="{{ route('admin.payouts.approve', $po->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm" style="font-size: 0.75rem; padding: 0.35rem 0.65rem;">
                                                Approve
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-sm" style="color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); background: rgba(239, 68, 68, 0.05); font-size: 0.75rem; padding: 0.35rem 0.65rem;" onclick="openRejectModal({{ $po->id }}, '{{ $po->reference }}')">
                                            Reject
                                        </button>

                                    @elseif ($po->status->value === 'approved')
                                        <button type="button" class="btn btn-primary btn-sm" style="background: #059669; border-color: #059669; font-size: 0.75rem; padding: 0.35rem 0.65rem;" onclick="openDisburseModal({{ $po->id }}, '{{ $po->reference }}', {{ $po->amount }}, '{{ $po->method }}', '{{ addslashes($po->account_details) }}')">
                                            Disburse & Mark Paid
                                        </button>

                                        <button type="button" class="btn btn-sm" style="color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); background: rgba(239, 68, 68, 0.05); font-size: 0.75rem; padding: 0.35rem 0.65rem;" onclick="openRejectModal({{ $po->id }}, '{{ $po->reference }}')">
                                            Reject
                                        </button>

                                    @elseif ($po->status->value === 'paid')
                                        <span style="font-size: 0.78rem; color: #059669; font-weight: 700;">
                                            ✓ Paid on {{ $po->paid_at?->format('M d') }}
                                        </span>
                                    @elseif ($po->status->value === 'rejected')
                                        <span style="font-size: 0.78rem; color: #ef4444;">
                                            Rejected
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 3rem;">No payout requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $payouts->links() }}
        </div>
    </div>

    <!-- Disburse & Mark Paid Modal -->
    <div id="disburse-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
        <div class="glass-card" style="background: rgba(255, 255, 255, 0.95); max-width: 480px; width: 100%; border-radius: var(--radius-xl); padding: 2rem; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;">Disburse Caregiver Payout</h3>
                <button type="button" onclick="document.getElementById('disburse-modal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>

            <div style="background: rgba(10, 57, 74, 0.06); border-radius: var(--radius-md); padding: 1rem; margin-bottom: 1.25rem; font-size: 0.88rem;">
                <div>Payout Ref: <strong id="modal-po-ref"></strong></div>
                <div style="margin-top: 0.25rem;">Amount: <strong id="modal-po-amount" style="color: #0f172a; font-size: 1.1rem;"></strong></div>
                <div style="margin-top: 0.25rem;">Target: <span id="modal-po-dest" style="font-family: monospace;"></span></div>
            </div>

            <form id="disburse-form" method="POST" action="">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Payout Transaction Reference (bKash/Nagad TrxID / Bank EFTN) *</label>
                    <input type="text" name="transaction_reference" required placeholder="e.g. TRX-9382109" class="glass-input" style="width: 100%;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Internal Accounting Note</label>
                    <input type="text" name="admin_note" placeholder="Disbursed from CareMate Operations account" class="glass-input" style="width: 100%;">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" onclick="document.getElementById('disburse-modal').style.display='none'" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background: #059669; border-color: #059669;">Disburse & Notify Caregiver</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Payout Modal -->
    <div id="reject-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
        <div class="glass-card" style="background: rgba(255, 255, 255, 0.95); max-width: 480px; width: 100%; border-radius: var(--radius-xl); padding: 2rem; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;">Reject Payout Request</h3>
                <button type="button" onclick="document.getElementById('reject-modal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>

            <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.25rem;">
                Rejecting payout <strong id="modal-reject-ref"></strong> will return funds to the caregiver's pending balance.
            </p>

            <form id="reject-form" method="POST" action="">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Reason for Rejection *</label>
                    <textarea name="reason" rows="3" required placeholder="e.g. Account number invalid or name mismatch on bKash..." class="glass-textarea" style="width: 100%; font-size: 0.85rem;"></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" onclick="document.getElementById('reject-modal').style.display='none'" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openDisburseModal(id, ref, amount, method, details) {
            document.getElementById('disburse-form').action = `/admin/payouts/${id}/mark-paid`;
            document.getElementById('modal-po-ref').innerText = ref;
            document.getElementById('modal-po-amount').innerText = `৳${amount.toLocaleString()}`;
            document.getElementById('modal-po-dest').innerText = `${method.toUpperCase()} - ${details}`;
            document.getElementById('disburse-modal').style.display = 'flex';
        }

        function openRejectModal(id, ref) {
            document.getElementById('reject-form').action = `/admin/payouts/${id}/reject`;
            document.getElementById('modal-reject-ref').innerText = ref;
            document.getElementById('reject-modal').style.display = 'flex';
        }
    </script>
    @endpush
</x-layouts.dashboard>
