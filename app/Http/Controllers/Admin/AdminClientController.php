<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminClientController extends Controller
{
    public function __construct(
        public AuditLogService $auditLogService
    ) {}

    public function index(Request $request): View
    {
        $search = $request->input('search');

        $query = Client::with(['user', 'district', 'area', 'bookings']);

        if ($search) {
            $query->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"));
        }

        $clients = $query->latest()->paginate(15)->withQueryString();

        return view('admin.clients.index', compact('clients', 'search'));
    }

    public function show(Client $client): View
    {
        $client->load(['user', 'division', 'district', 'area', 'hiringRequests.service', 'bookings.service', 'reviews']);

        return view('admin.clients.show', compact('client'));
    }

    public function suspend(Request $request, Client $client): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $client->user->update([
            'status' => UserStatus::Suspended,
            'suspended_at' => now(),
            'suspension_reason' => $validated['reason'],
        ]);

        $this->auditLogService->log(
            Auth::user(),
            'client.suspended',
            $client->user,
            ['status' => 'active'],
            ['status' => 'suspended'],
            $validated['reason']
        );

        return back()->with('warning', 'Client account has been suspended.');
    }

    public function reactivate(Client $client): RedirectResponse
    {
        $client->user->update([
            'status' => UserStatus::Active,
            'suspended_at' => null,
            'suspension_reason' => null,
        ]);

        $this->auditLogService->log(
            Auth::user(),
            'client.reactivated',
            $client->user,
            ['status' => 'suspended'],
            ['status' => 'active'],
            'Reactivated client account'
        );

        return back()->with('success', 'Client account reactivated.');
    }
}
