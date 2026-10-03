<x-layouts.dashboard>
    <x-slot:title>Location Management — Admin | CareMate BD</x-slot:title>
    <x-slot:header>Bangladesh Geographic Coverage</x-slot:header>
    <x-slot:subheading>Manage operating Divisions, Districts, and Thana/Areas for caregiver dispatch and client search filters.</x-slot:subheading>

    <div style="display: grid; grid-template-columns: 1fr 360px; gap: 2rem; align-items: flex-start;" class="admin-location-layout">
        <!-- Hierarchy & Location Listing -->
        <div>
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <div>
                        <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;">Divisions & Coverage Network</h3>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0.2rem 0 0 0;">CareMate BD active operational footprint across Bangladesh.</p>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    @forelse ($divisions as $div)
                        <div style="border: 1px solid rgba(226, 232, 240, 0.8); border-radius: var(--radius-lg); background: rgba(255, 255, 255, 0.6); overflow: hidden;">
                            <!-- Division Header -->
                            <div style="padding: 1rem 1.25rem; background: rgba(10, 57, 74, 0.05); display: flex; justify-content: space-between; align-items: center;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #0a394a; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;">
                                        DIV
                                    </div>
                                    <div>
                                        <span style="font-weight: 800; font-size: 1.05rem; color: #0f172a;">{{ $div->name }} Division</span>
                                        <span style="font-size: 0.78rem; color: var(--text-muted); margin-left: 0.5rem;">({{ $div->children->count() }} districts)</span>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <x-badge :tone="$div->is_active ? 'success' : 'neutral'">
                                        {{ $div->is_active ? 'Active' : 'Inactive' }}
                                    </x-badge>
                                    <form method="POST" action="{{ route('admin.locations.toggle', $div->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary btn-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                            {{ $div->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Districts & Areas inside Division -->
                            <div style="padding: 1rem 1.25rem;">
                                @if ($div->children->isEmpty())
                                    <div style="font-size: 0.85rem; color: var(--text-muted); font-style: italic;">No districts added under this division yet.</div>
                                @else
                                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
                                        @foreach ($div->children as $dist)
                                            <div style="padding: 0.85rem 1rem; border-radius: var(--radius-md); background: #ffffff; border: 1px solid rgba(226, 232, 240, 0.7); box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                                    <div style="font-weight: 700; color: #0f172a; font-size: 0.92rem; display: flex; align-items: center; gap: 0.35rem;">
                                                        📍 {{ $dist->name }}
                                                    </div>
                                                    <form method="POST" action="{{ route('admin.locations.toggle', $dist->id) }}">
                                                        @csrf
                                                        <button type="submit" style="background: none; border: none; font-size: 0.75rem; font-weight: 600; cursor: pointer; color: {{ $dist->is_active ? '#059669' : '#94a3b8' }};">
                                                            {{ $dist->is_active ? '● Active' : '○ Off' }}
                                                        </button>
                                                    </form>
                                                </div>

                                                <!-- Areas / Thanas under District -->
                                                <div style="display: flex; flex-wrap: wrap; gap: 0.35rem; margin-top: 0.4rem;">
                                                    @forelse ($dist->children as $area)
                                                        <span style="font-size: 0.75rem; background: rgba(10, 57, 74, 0.07); color: #0a394a; padding: 0.2rem 0.5rem; border-radius: 4px; display: inline-flex; align-items: center; gap: 0.3rem;">
                                                            {{ $area->name }}
                                                            <form method="POST" action="{{ route('admin.locations.toggle', $area->id) }}" style="display: inline;">
                                                                @csrf
                                                                <button type="submit" style="background: none; border: none; padding: 0; font-size: 0.65rem; color: {{ $area->is_active ? '#059669' : '#ef4444' }}; cursor: pointer;" title="Toggle active">
                                                                    {{ $area->is_active ? '✓' : '✕' }}
                                                                </button>
                                                            </form>
                                                        </span>
                                                    @empty
                                                        <span style="font-size: 0.75rem; color: var(--text-muted); font-style: italic;">No specific areas listed.</span>
                                                    @endforelse
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; color: var(--text-muted); padding: 3rem;">No divisions configured yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Add Location Form Sidebar -->
        <div>
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); position: sticky; top: 1.5rem;">
                <h4 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-bottom: 0.3rem;">
                    Add New Location
                </h4>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.25rem;">
                    Expand service coverage to new divisions, districts, or residential areas.
                </p>

                <form method="POST" action="{{ route('admin.locations.store') }}">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Location Type *</label>
                        <select name="type" id="location-type-select" required class="glass-select" style="width: 100%;" onchange="handleTypeChange(this.value)">
                            <option value="division">Division (Top Level)</option>
                            <option value="district" selected>District (Under Division)</option>
                            <option value="area">Area / Thana (Under District)</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Location Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Uttara or Gazipur" class="glass-input" style="width: 100%;">
                    </div>

                    <div id="parent-container" style="margin-bottom: 1rem;">
                        <label id="parent-label" style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Parent Division *</label>
                        <select name="parent_id" id="parent-select" class="glass-select" style="width: 100%;">
                            <!-- Dynamically populated or toggled -->
                            @foreach ($divisions as $d)
                                <option value="{{ $d->id }}" data-type="division">{{ $d->name }} Division</option>
                            @endforeach
                            @foreach ($districts as $dis)
                                <option value="{{ $dis->id }}" data-type="district" style="display: none;">{{ $dis->name }} District</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Sort Order</label>
                        <input type="number" name="sort_order" value="0" min="0" class="glass-input" style="width: 100%;">
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                        Save Location
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function handleTypeChange(type) {
            const container = document.getElementById('parent-container');
            const label = document.getElementById('parent-label');
            const select = document.getElementById('parent-select');
            const options = select.options;

            if (type === 'division') {
                container.style.display = 'none';
                select.required = false;
            } else if (type === 'district') {
                container.style.display = 'block';
                label.innerText = 'Parent Division *';
                select.required = true;
                for (let i = 0; i < options.length; i++) {
                    options[i].style.display = options[i].getAttribute('data-type') === 'division' ? 'block' : 'none';
                }
                // select first division
                for (let i = 0; i < options.length; i++) {
                    if (options[i].getAttribute('data-type') === 'division') {
                        select.value = options[i].value;
                        break;
                    }
                }
            } else if (type === 'area') {
                container.style.display = 'block';
                label.innerText = 'Parent District *';
                select.required = true;
                for (let i = 0; i < options.length; i++) {
                    options[i].style.display = options[i].getAttribute('data-type') === 'district' ? 'block' : 'none';
                }
                // select first district
                for (let i = 0; i < options.length; i++) {
                    if (options[i].getAttribute('data-type') === 'district') {
                        select.value = options[i].value;
                        break;
                    }
                }
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            handleTypeChange(document.getElementById('location-type-select').value);
        });
    </script>
    @endpush
</x-layouts.dashboard>
