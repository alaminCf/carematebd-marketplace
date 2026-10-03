<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAction;
use App\Models\Setting;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminSettingController extends Controller
{
    public function __construct(
        public AuditLogService $auditLogService
    ) {}

    public function index(): View
    {
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'platform_name' => ['required', 'string', 'max:100'],
            'platform_commission_rate' => ['required', 'numeric', 'between:0,50'],
            'contact_email' => ['required', 'email'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'emergency_hotline' => ['required', 'string', 'max:30'],
            'office_address' => ['required', 'string', 'max:255'],
            'min_payout_amount' => ['required', 'numeric', 'min:500'],
        ]);

        foreach ($validated as $key => $val) {
            Setting::set($key, $val);
        }

        $this->auditLogService->log(Auth::user(), 'settings.updated', null, null, $validated);

        return back()->with('success', 'Platform settings updated successfully.');
    }

    public function auditLogs(Request $request): View
    {
        $logs = AdminAction::with('admin')->latest()->paginate(20);

        return view('admin.audit-logs', compact('logs'));
    }
}
