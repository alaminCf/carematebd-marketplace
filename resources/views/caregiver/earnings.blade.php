<x-layouts.dashboard>
    <x-slot:title>Earnings & Payouts — CareMate BD</x-slot:title>
    <x-slot:header>Earnings & Mobile Payouts</x-slot:header>
    <x-slot:subheading>Transparent compensation ledger. Instant withdrawals to bKash, Nagad, or Bank.</x-slot:subheading>

    <!-- Balance Stat Cards -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2.5rem;" class="stats-grid">
        <div class="glass-card stat-card">
            <span class="stat-label">Available for Withdrawal</span>
            <div class="stat-value" style="color: #059669;">৳{{ number_format($balance['available']) }}</div>
            <span class="stat-hint">Ready to transfer to bKash/Bank</span>
        </div>

        <div class="glass-card stat-card">
            <span class="stat-label">Pending Escrow Earnings</span>
            <div class="stat-value" style="color: #d97706;">৳{{ number_format($balance['pending']) }}</div>
            <span class="stat-hint">Shifts currently in progress</span>
        </div>

        <div class="glass-card stat-card">
            <span class="stat-label">Total Withdrawn to Date</span>
            <div class="stat-value" style="color: #0a394a;">৳{{ number_format($balance['total_paid']) }}</div>
            <span class="stat-hint">Transferred successfully</span>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 380px; gap: 2rem; align-items: flex-start;" class="earnings-layout">
        <!-- Earnings Ledger -->
        <div>
            <!-- Job Earnings Table -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem;">
                    Completed Shifts Ledger
                </h3>

                @if ($earnings->isNotEmpty())
                    <div class="table-container">
                        <table class="glass-table">
                            <thead>
                                <tr>
                                    <th>Booking Ref</th>
                                    <th>Service</th>
                                    <th>Gross (৳)</th>
                                    <th>CareMate Fee</th>
                                    <th>Your Net (৳)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($earnings as $e)
                                    <tr>
                                        <td style="font-weight: 700; color: var(--brand-primary);">
                                            #{{ $e->booking->booking_reference ?? 'N/A' }}
                                        </td>
                                        <td>{{ $e->booking->service->name ?? 'Care Service' }}</td>
                                        <td>৳{{ number_format($e->gross_amount) }}</td>
                                        <td style="color: #e11d48; font-size: 0.85rem;">-৳{{ number_format($e->platform_commission) }} ({{ $e->commission_rate_percent }}%)</td>
                                        <td style="font-weight: 800; color: #059669;">৳{{ number_format($e->net_caregiver_earnings) }}</td>
                                        <td>
                                            <x-badge :tone="$e->status->value === 'available' ? 'success' : ($e->status->value === 'paid_out' ? 'neutral' : 'warning')">
                                                {{ ucfirst(str_replace('_', ' ', $e->status->value)) }}
                                            </x-badge>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top: 1rem;">
                        {{ $earnings->links() }}
                    </div>
                @else
                    <p style="color: var(--text-muted); text-align: center; padding: 2rem;">No earnings entries recorded yet.</p>
                @endif
            </div>

            <!-- Payout History Table -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem;">
                    Payout Withdrawal Requests
                </h3>

                @if ($payouts->isNotEmpty())
                    <div class="table-container">
                        <table class="glass-table">
                            <thead>
                                <tr>
                                    <th>Ref #</th>
                                    <th>Method</th>
                                    <th>Account / Phone</th>
                                    <th>Amount (৳)</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payouts as $p)
                                    <tr>
                                        <td style="font-weight: 700;">#{{ $p->payout_reference }}</td>
                                        <td><strong>{{ strtoupper($p->payout_method->value) }}</strong></td>
                                        <td style="font-family: monospace;">{{ $p->account_number }}</td>
                                        <td style="font-weight: 800; color: #0f172a;">৳{{ number_format($p->amount) }}</td>
                                        <td>
                                            <x-badge :tone="$p->status->value === 'paid' ? 'success' : ($p->status->value === 'approved' ? 'info' : 'warning')">
                                                {{ ucfirst($p->status->value) }}
                                            </x-badge>
                                        </td>
                                        <td style="font-size: 0.8rem; color: var(--text-muted);">{{ $p->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p style="color: var(--text-muted); text-align: center; padding: 2rem;">No withdrawal requests made yet.</p>
                @endif
            </div>
        </div>

        <!-- Request Payout Card -->
        <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); position: sticky; top: 90px;">
            <h4 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">
                Request Payout
            </h4>
            <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.5rem;">
                Withdraw your cleared earnings directly into your personal mobile wallet or bank.
            </p>

            <form method="POST" action="{{ route('caregiver.earnings.payout') }}">
                @csrf

                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Withdrawal Amount (৳ BDT) <span style="color: #ef4444;">*</span></label>
                    <input type="number" name="amount" min="500" max="{{ max(500, $balance['available']) }}" value="{{ $balance['available'] }}" required class="glass-input">
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Max available: ৳{{ number_format($balance['available']) }}</span>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Payout Method <span style="color: #ef4444;">*</span></label>
                    <select name="payout_method" required class="glass-input" id="payoutMethodSelect" onchange="toggleBankFields(this.value)">
                        <option value="bkash">bKash (Personal)</option>
                        <option value="nagad">Nagad (Personal)</option>
                        <option value="rocket">Rocket (DBBL)</option>
                        <option value="bank_transfer">Bank Transfer (Any BD Bank)</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Account / Mobile Number <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="account_number" required placeholder="e.g. 01712-XXXXXX" class="glass-input">
                </div>

                <div id="bankFields" style="display: none; margin-bottom: 1rem;">
                    <div style="margin-bottom: 0.75rem;">
                        <label class="form-label">Bank Name</label>
                        <input type="text" name="bank_name" placeholder="e.g. Dutch Bangla Bank, City Bank" class="glass-input">
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <label class="form-label">Branch Name</label>
                        <input type="text" name="branch_name" placeholder="e.g. Banani Branch" class="glass-input">
                    </div>
                </div>

                <button type="submit" class="btn btn-mint btn-lg" style="width: 100%;" {{ $balance['available'] < 500 ? 'disabled' : '' }}>
                    Confirm Withdrawal Request
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleBankFields(val) {
            document.getElementById('bankFields').style.display = (val === 'bank_transfer') ? 'block' : 'none';
        }
    </script>
    @endpush
</x-layouts.dashboard>
