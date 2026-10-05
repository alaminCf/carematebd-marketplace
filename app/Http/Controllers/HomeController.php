<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\CaregiverStatus;
use App\Models\Booking;
use App\Models\Caregiver;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();

        $featuredCaregivers = Caregiver::publiclyVisible()
            ->with(['user', 'services', 'district', 'area'])
            ->where('is_featured', true)
            ->take(6)
            ->get();

        if ($featuredCaregivers->isEmpty()) {
            $featuredCaregivers = Caregiver::publiclyVisible()
                ->with(['user', 'services', 'district', 'area'])
                ->orderByDesc('rating_avg')
                ->take(6)
                ->get();
        }

        $stats = [
            'caregivers_count' => Caregiver::whereIn('status', [CaregiverStatus::Approved, CaregiverStatus::Published])->count(),
            'clients_count' => User::where('role', 'client')->count(),
            'completed_bookings' => Booking::where('status', BookingStatus::Completed)->count(),
            'satisfaction_rate' => Review::where('status', 'published')->avg('rating') ? round(Review::where('status', 'published')->avg('rating'), 1) : 4.9,
        ];

        $faqs = Faq::where('is_active', true)->orderBy('sort_order')->take(5)->get();

        $testimonials = Review::where('status', 'published')
            ->where('rating', '>=', 4)
            ->with(['client.user', 'caregiver.user', 'booking.service'])
            ->latest()
            ->take(4)
            ->get();

        $popularAreas = [
            'Dhanmondi', 'Gulshan', 'Banani', 'Uttara', 'Mirpur',
            'Mohammadpur', 'Bashundhara', 'Banasree', 'Sylhet', 'Chittagong',
        ];

        return view('pages.home', compact('services', 'featuredCaregivers', 'stats', 'faqs', 'testimonials', 'popularAreas'));
    }

    public function howItWorks(): View
    {
        return view('pages.how-it-works');
    }

    public function about(): View
    {
        $stats = [
            'caregivers_count' => Caregiver::whereIn('status', [CaregiverStatus::Approved, CaregiverStatus::Published])->count(),
            'completed_bookings' => Booking::where('status', BookingStatus::Completed)->count(),
            'divisions_count' => 4,
        ];

        return view('pages.about', compact('stats'));
    }

    public function faq(): View
    {
        $clientFaqs = Faq::where('is_active', true)->whereIn('audience', ['client', 'general'])->orderBy('sort_order')->get();
        $caregiverFaqs = Faq::where('is_active', true)->whereIn('audience', ['caregiver', 'general'])->orderBy('sort_order')->get();

        return view('pages.faq', compact('clientFaqs', 'caregiverFaqs'));
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:120'],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        ContactMessage::create(array_merge($validated, [
            'ip_address' => $request->ip(),
        ]));

        return back()->with('success', 'Thank you! Your message has been sent to CareMate Support. Our care coordinator will respond promptly.');
    }

    public function detectLocation(Request $request): JsonResponse
    {
        $lat = $request->query('lat');
        $lon = $request->query('lon');

        if ($lat && $lon) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'CareMateBD/1.0 (contact@carematebd.com)',
                ])->timeout(4)->get('https://nominatim.openstreetmap.org/reverse', [
                    'format' => 'jsonv2',
                    'lat' => $lat,
                    'lon' => $lon,
                    'accept-language' => 'en',
                ]);

                if ($response->successful()) {
                    $address = $response->json('address') ?? [];
                    $suburb = $address['suburb'] ?? $address['neighbourhood'] ?? $address['residential'] ?? $address['quarter'] ?? $address['city_district'] ?? $address['subdistrict'] ?? null;
                    $city = $address['city'] ?? $address['town'] ?? $address['state'] ?? 'Dhaka';

                    if ($suburb) {
                        return response()->json([
                            'success' => true,
                            'area' => $suburb,
                            'city' => $city,
                            'display' => "{$suburb}, {$city}",
                            'source' => 'gps',
                        ]);
                    }

                    return response()->json([
                        'success' => true,
                        'area' => $city,
                        'city' => $city,
                        'display' => "{$city}, Bangladesh",
                        'source' => 'gps',
                    ]);
                }
            } catch (\Throwable $e) {
                // Ignore and fall back to coordinate approximation
            }

            $latF = (float) $lat;
            $lonF = (float) $lon;
            if ($latF >= 23.73 && $latF <= 23.76 && $lonF >= 90.36 && $lonF <= 90.39) {
                return response()->json(['success' => true, 'area' => 'Dhanmondi', 'city' => 'Dhaka', 'display' => 'Dhanmondi, Dhaka', 'source' => 'coordinates']);
            }
            if ($latF >= 23.77 && $latF <= 23.81 && $lonF >= 90.40 && $lonF <= 90.43) {
                return response()->json(['success' => true, 'area' => 'Gulshan', 'city' => 'Dhaka', 'display' => 'Gulshan, Dhaka', 'source' => 'coordinates']);
            }
            if ($latF >= 23.78 && $latF <= 23.81 && $lonF >= 90.39 && $lonF <= 90.41) {
                return response()->json(['success' => true, 'area' => 'Banani', 'city' => 'Dhaka', 'display' => 'Banani, Dhaka', 'source' => 'coordinates']);
            }
            if ($latF >= 23.85 && $latF <= 23.90 && $lonF >= 90.37 && $lonF <= 90.42) {
                return response()->json(['success' => true, 'area' => 'Uttara', 'city' => 'Dhaka', 'display' => 'Uttara, Dhaka', 'source' => 'coordinates']);
            }
            if ($latF >= 23.79 && $latF <= 23.84 && $lonF >= 90.34 && $lonF <= 90.38) {
                return response()->json(['success' => true, 'area' => 'Mirpur', 'city' => 'Dhaka', 'display' => 'Mirpur, Dhaka', 'source' => 'coordinates']);
            }
            if ($latF >= 23.74 && $latF <= 23.78 && $lonF >= 90.34 && $lonF <= 90.37) {
                return response()->json(['success' => true, 'area' => 'Mohammadpur', 'city' => 'Dhaka', 'display' => 'Mohammadpur, Dhaka', 'source' => 'coordinates']);
            }
            if ($latF >= 23.80 && $latF <= 23.84 && $lonF >= 90.42 && $lonF <= 90.45) {
                return response()->json(['success' => true, 'area' => 'Bashundhara', 'city' => 'Dhaka', 'display' => 'Bashundhara, Dhaka', 'source' => 'coordinates']);
            }
            if ($latF >= 23.68 && $latF <= 23.92 && $lonF >= 90.32 && $lonF <= 90.52) {
                return response()->json(['success' => true, 'area' => 'Dhaka', 'city' => 'Dhaka', 'display' => 'Dhaka, Bangladesh', 'source' => 'coordinates']);
            }
            if ($latF >= 22.25 && $latF <= 22.45 && $lonF >= 91.75 && $lonF <= 91.90) {
                return response()->json(['success' => true, 'area' => 'Chittagong', 'city' => 'Chittagong', 'display' => 'Chittagong, Bangladesh', 'source' => 'coordinates']);
            }
            if ($latF >= 24.85 && $latF <= 24.95 && $lonF >= 91.80 && $lonF <= 91.95) {
                return response()->json(['success' => true, 'area' => 'Sylhet', 'city' => 'Sylhet', 'display' => 'Sylhet, Bangladesh', 'source' => 'coordinates']);
            }
        }

        // Try IP detection fallback
        $clientIp = $request->header('CF-Connecting-IP')
            ?? $request->header('X-Forwarded-For')
            ?? $request->ip();

        if (is_string($clientIp) && str_contains($clientIp, ',')) {
            $clientIp = trim(explode(',', $clientIp)[0]);
        }

        if ($clientIp && ! in_array($clientIp, ['127.0.0.1', '::1'])) {
            try {
                $ipRes = Http::timeout(3)->get("https://ipwho.is/{$clientIp}");
                if ($ipRes->successful() && $ipRes->json('success')) {
                    $city = $ipRes->json('city') ?: 'Dhaka';

                    return response()->json([
                        'success' => true,
                        'area' => $city,
                        'city' => $city,
                        'display' => "{$city}, Bangladesh",
                        'source' => 'ip',
                    ]);
                }
            } catch (\Throwable $e) {
            }
        }

        return response()->json([
            'success' => true,
            'area' => 'Dhaka',
            'city' => 'Dhaka',
            'display' => 'Dhaka, Bangladesh',
            'source' => 'default',
        ]);
    }
}
