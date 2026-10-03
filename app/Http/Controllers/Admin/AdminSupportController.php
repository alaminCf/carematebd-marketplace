<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DisputeStatus;
use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\DisputeNote;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Notifications\CareMateDatabaseNotification;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminSupportController extends Controller
{
    public function __construct(
        public AuditLogService $auditLogService
    ) {}

    public function tickets(Request $request): View
    {
        $status = $request->input('status');

        $query = SupportTicket::with(['user', 'booking.service']);

        if ($status) {
            $query->where('status', $status);
        }

        $tickets = $query->latest('last_reply_at')->paginate(15)->withQueryString();

        return view('admin.support.tickets', compact('tickets', 'status'));
    }

    public function showTicket(SupportTicket $ticket): View
    {
        $ticket->load(['user', 'booking.service', 'messages.user']);

        return view('admin.support.show-ticket', compact('ticket'));
    }

    public function replyTicket(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'min:3', 'max:3000'],
            'status' => ['required', 'in:open,in_review,waiting_for_user,resolved,closed'],
            'is_internal' => ['boolean'],
        ]);

        DB::transaction(function () use ($validated, $ticket, $request): void {
            $isInternal = $request->boolean('is_internal');

            SupportMessage::create([
                'support_ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'message' => $validated['message'],
                'is_internal' => $isInternal,
            ]);

            $ticket->update([
                'status' => TicketStatus::from($validated['status']),
                'last_reply_at' => now(),
                'assigned_to' => Auth::id(),
                'resolved_at' => $validated['status'] === 'resolved' ? now() : null,
            ]);

            if (! $isInternal) {
                $ticket->user->notify(new CareMateDatabaseNotification(
                    title: 'Support Update: Ticket #'.$ticket->ticket_number,
                    body: "CareMate Support replied to: {$ticket->subject}",
                    actionUrl: $ticket->user->isCaregiver() ? route('caregiver.support.show', $ticket) : route('client.support.show', $ticket),
                    type: 'support_reply'
                ));
            }
        });

        return back()->with('success', 'Reply recorded successfully.');
    }

    public function disputes(): View
    {
        $disputes = Dispute::with(['reporter', 'booking.client.user', 'booking.caregiver.user', 'booking.service'])
            ->latest()
            ->paginate(15);

        return view('admin.support.disputes', compact('disputes'));
    }

    public function showDispute(Dispute $dispute): View
    {
        $dispute->load(['reporter', 'booking.client.user', 'booking.caregiver.user', 'booking.service', 'notes.admin']);

        return view('admin.support.show-dispute', compact('dispute'));
    }

    public function resolveDispute(Request $request, Dispute $dispute): RedirectResponse
    {
        $validated = $request->validate([
            'resolution' => ['required', 'string', 'min:10', 'max:2000'],
            'status' => ['required', 'in:resolved,rejected,closed'],
        ]);

        $dispute->update([
            'status' => DisputeStatus::from($validated['status']),
            'resolution' => $validated['resolution'],
            'resolved_by' => Auth::id(),
            'resolved_at' => now(),
        ]);

        $this->auditLogService->log(Auth::user(), 'dispute.resolved', $dispute);

        return back()->with('success', 'Dispute resolution recorded.');
    }

    public function addDisputeNote(Request $request, Dispute $dispute): RedirectResponse
    {
        $validated = $request->validate([
            'note' => ['required', 'string', 'max:1000'],
        ]);

        DisputeNote::create([
            'dispute_id' => $dispute->id,
            'admin_id' => Auth::id(),
            'note' => $validated['note'],
        ]);

        return back()->with('success', 'Dispute internal note added.');
    }
}
