<?php

namespace App\Http\Controllers;

use App\Models\Caregiver;
use App\Models\Location;
use App\Models\Service;
use App\Services\PrivacyFilterService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function __construct(
        public PrivacyFilterService $privacyService
    ) {}

    public function index(Request $request): View
    {
        $query = Caregiver::publiclyVisible()->with(['user', 'services', 'district', 'area']);

        // Search term (name, skills, bio, city)
        if ($search = $request->input('search')) {
            $query->where(function (Builder $q) use ($search): void {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('caregiver_type', 'like', "%{$search}%")
                    ->orWhere('skills', 'like', "%{$search}%")
                    ->orWhere('about', 'like', "%{$search}%")
                    ->orWhereHas('district', fn ($d) => $d->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('area', fn ($a) => $a->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter by Service
        if ($serviceSlug = $request->input('service')) {
            $query->whereHas('services', fn ($q) => $q->where('services.slug', $serviceSlug));
        }

        // Filter by Division
        if ($divisionId = $request->input('division_id')) {
            $query->where('division_id', $divisionId);
        }

        // Filter by District
        if ($districtId = $request->input('district_id')) {
            $query->where('district_id', $districtId);
        }

        // Filter by Gender
        if ($gender = $request->input('gender')) {
            $query->where('gender', $gender);
        }

        // Filter by Minimum Experience
        if ($minExp = $request->input('experience')) {
            $query->where('years_experience', '>=', (int) $minExp);
        }

        // Filter by Minimum Rating
        if ($minRating = $request->input('rating')) {
            $query->where('rating_avg', '>=', (float) $minRating);
        }

        // Filter by Working Type
        if ($empType = $request->input('employment_type')) {
            $query->where('employment_type', $empType);
        }
        if ($liveType = $request->input('live_type')) {
            $query->where('live_type', $liveType);
        }

        // Filter by Price Range
        if ($minPrice = $request->input('min_price')) {
            $query->where('daily_rate', '>=', (float) $minPrice);
        }
        if ($maxPrice = $request->input('max_price')) {
            $query->where('daily_rate', '<=', (float) $maxPrice);
        }

        // Filter by Availability
        if ($request->boolean('available_only')) {
            $query->where('is_available', true);
        }

        // Sorting
        match ($request->input('sort', 'recommended')) {
            'highest_rated' => $query->orderByDesc('rating_avg')->orderByDesc('rating_count'),
            'most_experienced' => $query->orderByDesc('years_experience'),
            'price_low' => $query->orderBy('daily_rate'),
            'price_high' => $query->orderByDesc('daily_rate'),
            'most_jobs' => $query->orderByDesc('completed_jobs_count'),
            'newest' => $query->latest(),
            default => $query->orderByDesc('is_featured')
                ->orderByRaw('CASE WHEN sort_order > 0 THEN sort_order ELSE 999999 END ASC')
                ->orderByDesc('rating_avg')
                ->orderByDesc('completed_jobs_count'),
        };

        $caregivers = $query->paginate(9)->withQueryString();

        $services = Service::where('is_active', true)->orderBy('sort_order')->get();
        $divisions = Location::where('type', 'division')->where('is_active', true)->orderBy('name')->get();
        $districts = Location::where('type', 'district')->where('is_active', true)->orderBy('name')->get();

        return view('pages.marketplace.index', compact('caregivers', 'services', 'divisions', 'districts'));
    }

    public function show(string $slug): View
    {
        $caregiver = Caregiver::where('slug', $slug)
            ->whereHas('user', fn ($u) => $u->whereNull('suspended_at'))
            ->with([
                'user',
                'services',
                'division',
                'district',
                'area',
                'experiences',
                'availabilities',
                'certificates' => fn ($c) => $c->where('is_public', true),
                'reviews' => fn ($r) => $r->where('status', 'published')->with('client.user')->latest(),
            ])
            ->firstOrFail();

        // Enforce public verification rule
        if (! $caregiver->isVerified() && ! (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->caregiver?->id === $caregiver->id))) {
            abort(404, 'Caregiver profile is not publicly available.');
        }

        $profile = $this->privacyService->publicCaregiverProfile($caregiver);
        $reviews = $caregiver->reviews;

        $similarCaregivers = Caregiver::publiclyVisible()
            ->where('id', '!=', $caregiver->id)
            ->whereHas('services', fn ($q) => $q->whereIn('services.id', $caregiver->services->pluck('id')))
            ->with(['user', 'services', 'district', 'area'])
            ->take(3)
            ->get();

        return view('pages.marketplace.show', compact('caregiver', 'profile', 'reviews', 'similarCaregivers'));
    }
}
