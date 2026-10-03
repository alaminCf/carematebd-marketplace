<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LocationType;
use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminLocationController extends Controller
{
    public function index(): View
    {
        $divisions = Location::where('type', LocationType::Division)->with('children.children')->orderBy('sort_order')->get();
        $districts = Location::where('type', LocationType::District)->orderBy('name')->get();

        return view('admin.locations.index', compact('divisions', 'districts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:division,district,area'],
            'parent_id' => ['nullable', 'exists:locations,id'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        Location::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'type' => LocationType::from($validated['type']),
            'parent_id' => $validated['parent_id'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'Location added successfully.');
    }

    public function toggle(Location $location): RedirectResponse
    {
        $location->update(['is_active' => ! $location->is_active]);

        return back()->with('success', 'Location status updated.');
    }
}
