<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminServiceController extends Controller
{
    public function __construct(
        public AuditLogService $auditLogService
    ) {}

    public function index(): View
    {
        $services = Service::withCount('caregivers')->orderBy('sort_order')->get();

        return view('admin.services.index', compact('services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'short_description' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['required', 'string', 'max:40'],
            'sort_order' => ['required', 'integer'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = '/storage/'.$request->file('image')->store('services', 'public');
        }

        $service = Service::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'icon' => $validated['icon'],
            'sort_order' => $validated['sort_order'],
            'image_path' => $imagePath,
            'is_active' => true,
        ]);

        $this->auditLogService->log(Auth::user(), 'service.created', $service);

        return back()->with('success', 'Service category created successfully.');
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'short_description' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['required', 'string', 'max:40'],
            'sort_order' => ['required', 'integer'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = '/storage/'.$request->file('image')->store('services', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $service->update($validated);

        $this->auditLogService->log(Auth::user(), 'service.updated', $service);

        return back()->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return back()->with('success', 'Service deleted.');
    }
}
