<?php

namespace App\Http\Controllers;

use App\Models\Caregiver;
use App\Models\Service;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();

        return view('pages.services.index', compact('services'));
    }

    public function show(string $slug): View
    {
        $service = Service::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $caregivers = Caregiver::publiclyVisible()
            ->whereHas('services', fn ($q) => $q->where('services.id', $service->id))
            ->with(['user', 'services', 'district', 'area'])
            ->orderByDesc('rating_avg')
            ->paginate(9);

        return view('pages.services.show', compact('service', 'caregivers'));
    }
}
