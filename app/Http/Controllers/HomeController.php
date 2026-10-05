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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
}
