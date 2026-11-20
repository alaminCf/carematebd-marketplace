<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAction;
use App\Models\GoogleReview;
use App\Models\Setting;
use App\Services\AuditLogService;
use App\Services\GoogleReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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
        $googleReviews = GoogleReview::orderBy('sort_order')->get();

        return view('admin.settings.index', compact('settings', 'googleReviews'));
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
            'google_review_url' => ['nullable', 'string', 'max:500'],
            'google_write_review_url' => ['nullable', 'string', 'max:500'],
            'google_place_id' => ['nullable', 'string', 'max:100'],
            'google_rating_avg' => ['nullable', 'numeric', 'between:1,5'],
            'google_reviews_count' => ['nullable', 'integer', 'min:0'],
            'google_maps_api_key' => ['nullable', 'string', 'max:200'],
            'google_reviews_embed_code' => ['nullable', 'string'],
            'google_analytics_id' => ['nullable', 'string', 'max:50'],
            'google_site_verification' => ['nullable', 'string', 'max:150'],
            'meta_pixel_id' => ['nullable', 'string', 'max:50'],
            'microsoft_clarity_id' => ['nullable', 'string', 'max:50'],
        ]);

        foreach ($validated as $key => $val) {
            Setting::set($key, $val);
        }

        try {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
        } catch (\Throwable) {
            //
        }

        $this->auditLogService->log(Auth::user(), 'settings.updated', null, null, $validated);

        return back()->with('success', 'Platform settings updated successfully.');
    }

    public function syncGoogleReviews(): RedirectResponse
    {
        $service = app(GoogleReviewService::class);
        $result = $service->syncFromPlacesApi();

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    public function storeGoogleReview(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'author_name' => ['required', 'string', 'max:100'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'max:2000'],
            'relative_time' => ['nullable', 'string', 'max:50'],
        ]);

        $maxSort = GoogleReview::max('sort_order') ?? 0;

        GoogleReview::create([
            'google_review_id' => 'custom_'.time().'_'.substr(md5($validated['author_name']), 0, 6),
            'author_name' => $validated['author_name'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'relative_time' => $validated['relative_time'] ?: 'Recently',
            'review_datetime' => now(),
            'is_published' => true,
            'sort_order' => $maxSort + 1,
        ]);

        return back()->with('success', 'Google review added successfully.');
    }

    public function updateGoogleReview(Request $request, GoogleReview $googleReview): RedirectResponse
    {
        $validated = $request->validate([
            'author_name' => ['required', 'string', 'max:100'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'max:2000'],
            'relative_time' => ['nullable', 'string', 'max:50'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $googleReview->update([
            'author_name' => $validated['author_name'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'relative_time' => $validated['relative_time'] ?: 'Recently',
            'is_published' => $request->boolean('is_published', true),
        ]);

        return back()->with('success', 'Google review updated successfully.');
    }

    public function destroyGoogleReview(GoogleReview $googleReview): RedirectResponse
    {
        $googleReview->delete();

        return back()->with('success', 'Google review removed successfully.');
    }

    public function auditLogs(Request $request): View
    {
        $logs = AdminAction::with('admin')->latest()->paginate(20);

        return view('admin.audit-logs', compact('logs'));
    }
}
