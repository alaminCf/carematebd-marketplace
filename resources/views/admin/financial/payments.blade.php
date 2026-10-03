<x-layouts.dashboard>
    <x-slot:title>Escrow Payments — Admin Financials | CareMate BD</x-slot:title>
    <x-slot:header>Client Escrow Deposits & Platform Revenue</x-slot:header>
    <x-slot:subheading>Supervise incoming client advance payments held in escrow, commission splits, and payment reconciliations.</x-slot:subheading>

    <!-- Financial Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
            <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); margin-bottom: 0.35rem;">Total Escrow Volume</div>
            <div style="font-size: 1.7rem; font-weight: 800; color: #0f172a;">৳{{ number_format($stats['total_volume'], 0) }}</div>
            <div style="font-size: 0.75rem; color: #059669; font-weight: 600; margin-top: 0.25rem;">Gross client deposits</div>
        </div>

        <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
            <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); margin-bottom: 0.35rem;">Platform Revenue (15%)</div>
            <div style="font-size: 1.7rem; font-weight: 800; color: #0a394a;">৳{{ number_format($stats['total_commission'], 0) }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">CareMate operational revenue</div>
        </div>

        <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
            <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); margin-bottom: 0.35rem;">Caregiver Allocations</div>
            <div style="font-size: 1.7rem; font-weight: 800; color: #059669;">৳{{ number_format($stats['total_caregiver'], 0) }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Disbursable to caregivers</div>
        </div>

        <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
            <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); margin-bottom: 0.35rem;">Pending Escrows</div>
            <div style="font-size: 1.7rem; font-weight: 800; color: #f59e0b;">{{ $stats['pending_count'] }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Awaiting client transfer</div>
        </div>
    </div>

    <!-- Payments Table Container -->
    <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
        <!-- Filters -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm {{ !$status ? 'btn-primary' : 'btn-secondary' }}">
                    All Transactions
                </a>
                <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-primary' : 'btn-secondary' }}">
                    Pending Payment
                </a>
                <a href="{{ route('admin.payments.index', ['status' => 'paid']) }}" class="btn btn-sm {{ $status === 'paid' ? 'btn-primary' : 'btn-secondary' }}">
                    Paid (Secured)
                </a>
                <a href="{{ route('admin.payments.index', ['status' => 'refunded']) }}" class="btn btn-sm {{ $status === 'refunded' ? 'btn-primary' : 'btn-secondary' }}">
                    Refunded
                </a>
            </div>
        </div>

        <div class="table-container">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>Trx Reference</th>
                        <th>Client</th>
                        <th>Service & Booking</th>
                        <th>Gross Total</th>
                        <th>Caregiver Share</th>
                        <th>Commission</th>
                        <th>Channel / Gateway</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $p)
                        <tr>
                            <td style="font-family: monospace; font-weight: 700; color: #0f172a;">
                                {{ $p->transaction_reference ?? 'PAY-' . str_pad($p->id, 6, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #0f172a;">{{ $p->client->user->name }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted); font-family: monospace;">{{ $p->client->user->phone }}</div>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #0f172a;">{{ $p->booking?->service?->name ?? 'Care Service' }}</div>
                                @if ($p->booking)
                                    <a href="{{ route('admin.bookings.show', $p->booking->id) }}" style="font-size: 0.75rem; color: #0a394a; text-decoration: underline;">
                                        #{{ $p->booking->booking_reference }}
                                    </a>
                                @endif
                            </td>
                            <td style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">
                                ৳{{ number_format($p->amount, 0) }}
                            </td>
                            <td style="font-size: 0.88rem; color: #059669; font-weight: 600;">
                                ৳{{ number_format($p->caregiver_amount, 0) }}
                            </td>
                            <td style="font-size: 0.88rem; color: #0a394a; font-weight: 600;">
                                ৳{{ number_format($p->commission_amount, 0) }}
                            </td>
                            <td style="font-size: 0.85rem;">
                                <span style="font-weight: 600; text-transform: uppercase;">{{ $p->method ?? 'N/A' }}</span>
                                @if ($p->gateway_transaction_id)
                                    <div style="font-size: 0.72rem; color: var(--text-muted); font-family: monospace;">{{ $p->gateway_transaction_id }}</div>
                                @endif
                            </td>
                            <td>
                                <x-badge :tone="$p->status instanceof \App\Enums\PaymentStatus ? $p->status->tone() : ($p->status === 'paid' ? 'success' : ($p->status === 'pending' ? 'warning' : 'danger'))">
                                    {{ $p->status instanceof \App\Enums\PaymentStatus ? $p->status->label() : ucfirst($p->status) }}
                                </x-badge>
                            </td>
                            <td style="font-size: 0.8rem; color: var(--text-muted);">
                                {{ $p->created_at->format('M d, Y') }}
                            </td>
                            <td style="text-align: right;">
                                @if (($p->status instanceof \App\Enums\PaymentStatus ? $p->status->value : $p->status) === 'pending')
                                    <button type="button" class="btn btn-primary btn-sm" onclick="openPaymentModal({{ $p->id }}, '{{ $p->transaction_reference ?? 'PAY-' . str_pad($p->id, 6, '0', STR_PAD_LEFT) }}', {{ $p->amount }})">
                                        Mark Paid
                                    </button>
                                @else
                                    <span style="font-size: 0.8rem; color: #059669; font-weight: 700;">✓ Verified</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" style="text-align: center; color: var(--text-muted); padding: 3rem;">No payment transactions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $payments->links() }}
        </div>
    </div>

    <!-- Mark Payment Paid Modal -->
    <div id="mark-paid-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
        <div class="glass-card" style="background: rgba(255, 255, 255, 0.95); max-width: 480px; width: 100%; border-radius: var(--radius-xl); padding: 2rem; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;">Reconcile Escrow Payment</h3>
                <button type="button" onclick="document.getElementById('mark-paid-modal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>

            <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.25rem;">
                Verify client deposit for <strong id="modal-ref"></strong> of <strong id="modal-amount" style="color: #0f172a;"></strong>.
            </p>

            <form id="mark-paid-form" method="POST" action="">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Payment Channel *</label>
                    <select name="method" required class="glass-select" style="width: 100%;">
                        <option value="bkash">bKash Merchant Pay</option>
                        <option value="nagad">Nagad Direct</option>
                        <option value="rocket">Rocket</option>
                        <option value="bank_transfer">Bank Wire (EFT / NPSB)</option>
                        <option value="card">Visa / Mastercard / Amex</option>
                        <option value="cash">Direct Cash Escrow</option>
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Gateway Trx ID / Bank Voucher No *</label>
                    <input type="text" name="gateway_transaction_id" required placeholder="e.g. 9J782KL9" class="glass-input" style="width: 100%;">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" onclick="document.getElementById('mark-paid-modal').style.display='none'" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm & Secure Escrow</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openPaymentModal(id, ref, amount) {
            document.getElementById('mark-paid-form').action = `/admin/payments/${id}/mark-paid`;
            document.getElementById('modal-ref').innerText = ref;
            document.getElementById('modal-amount').innerText = `৳${amount.toLocaleString()}`;
            document.getElementById('mark-paid-modal').style.display = 'flex';
        }
    </script>
    @endpush
</x-layouts.dashboard>
