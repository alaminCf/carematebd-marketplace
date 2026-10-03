<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PayoutStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Payout;
use App\Notifications\CareMateDatabaseNotification;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminFinancialController extends Controller
{
    public function __construct(
        public AuditLogService $auditLogService
    ) {}

    public function payments(Request $request): View
    {
        $status = $request->input('status');

        $query = Payment::with(['booking.service', 'client.user']);

        if ($status) {
            $query->where('status', $status);
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total_volume' => Payment::where('status', 'paid')->sum('amount'),
            'total_commission' => Payment::where('status', 'paid')->sum('commission_amount'),
            'total_caregiver' => Payment::where('status', 'paid')->sum('caregiver_amount'),
            'pending_count' => Payment::where('status', 'pending')->count(),
        ];

        return view('admin.financial.payments', compact('payments', 'stats', 'status'));
    }

    public function markPaymentPaid(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'method' => ['required', 'string'],
            'gateway_transaction_id' => ['nullable', 'string', 'max:100'],
        ]);

        $payment->update([
            'status' => 'paid',
            'method' => $validated['method'],
            'gateway_transaction_id' => $validated['gateway_transaction_id'] ?? null,
            'paid_at' => now(),
            'recorded_by' => Auth::id(),
        ]);

        $this->auditLogService->log(Auth::user(), 'payment.marked_paid', $payment);

        return back()->with('success', 'Payment marked as Paid.');
    }

    public function payouts(Request $request): View
    {
        $status = $request->input('status');

        $query = Payout::with('caregiver.user');

        if ($status) {
            $query->where('status', $status);
        }

        $payouts = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'pending_payouts' => Payout::where('status', PayoutStatus::Requested)->count(),
            'pending_amount' => Payout::where('status', PayoutStatus::Requested)->sum('amount'),
            'paid_amount' => Payout::where('status', PayoutStatus::Paid)->sum('amount'),
        ];

        return view('admin.financial.payouts', compact('payouts', 'stats', 'status'));
    }

    public function approvePayout(Payout $payout): RedirectResponse
    {
        $payout->update([
            'status' => PayoutStatus::Approved,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        $this->auditLogService->log(Auth::user(), 'payout.approved', $payout);

        return back()->with('success', 'Payout request approved for disbursement.');
    }

    public function markPayoutPaid(Request $request, Payout $payout): RedirectResponse
    {
        $validated = $request->validate([
            'transaction_reference' => ['required', 'string', 'max:100'],
            'admin_note' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($payout, $validated): void {
            $payout->update([
                'status' => PayoutStatus::Paid,
                'transaction_reference' => $validated['transaction_reference'],
                'admin_note' => $validated['admin_note'] ?? null,
                'paid_at' => now(),
                'processed_by' => Auth::id(),
            ]);

            $this->auditLogService->log(Auth::user(), 'payout.paid', $payout);

            $payout->caregiver->user->notify(new CareMateDatabaseNotification(
                title: 'Payout Disbursed: ৳'.number_format($payout->amount, 0),
                body: "Your payout request #{$payout->reference} has been sent via {$payout->method}. Trx ID: {$validated['transaction_reference']}.",
                actionUrl: route('caregiver.earnings'),
                type: 'payout_paid'
            ));
        });

        return back()->with('success', 'Payout marked as paid and caregiver notified.');
    }

    public function rejectPayout(Request $request, Payout $payout): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $payout->update([
            'status' => PayoutStatus::Rejected,
            'admin_note' => $validated['reason'],
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        $this->auditLogService->log(Auth::user(), 'payout.rejected', $payout, null, null, $validated['reason']);

        return back()->with('warning', 'Payout request rejected.');
    }
}
