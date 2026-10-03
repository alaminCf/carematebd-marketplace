<x-layouts.dashboard>
    <x-slot:title>Services Catalog — Admin | CareMate BD</x-slot:title>
    <x-slot:header>Care Service Categories</x-slot:header>
    <x-slot:subheading>Manage core offerings (Elderly, Child, Nursing, Medical Transport), descriptions, and marketplace catalog ordering.</x-slot:subheading>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 1rem; align-items: center;">
            <div style="font-size: 0.9rem; color: var(--text-muted);">
                Total Services: <strong style="color: #0f172a;">{{ $services->count() }}</strong> •
                Active on Marketplace: <strong style="color: #059669;">{{ $services->where('is_active', true)->count() }}</strong>
            </div>
        </div>

        <button onclick="document.getElementById('new-service-modal').style.display='flex'" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add New Service
        </button>
    </div>

    <!-- Services Grid / Table -->
    <div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl);">
        <div class="table-container">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Order</th>
                        <th>Category</th>
                        <th>Short Description</th>
                        <th>Icon Token</th>
                        <th>Caregivers</th>
                        <th>Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $srv)
                        <tr>
                            <td style="font-weight: 700; color: var(--text-muted); font-size: 0.95rem;">
                                #{{ $srv->sort_order }}
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    @if ($srv->image_path)
                                        <img src="{{ $srv->image_path }}" alt="" style="width: 44px; height: 44px; border-radius: 12px; object-fit: cover; border: 1px solid rgba(226, 232, 240, 0.8);">
                                    @else
                                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(10, 57, 74, 0.1); color: #0a394a; display: flex; align-items: center; justify-content: center; font-weight: 800;">
                                            {{ substr($srv->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight: 800; color: #0f172a; font-size: 1rem;">{{ $srv->name }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">Slug: {{ $srv->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="max-width: 320px; font-size: 0.88rem; color: var(--text-secondary); line-height: 1.4;">
                                {{ $srv->short_description }}
                            </td>
                            <td>
                                <code style="font-size: 0.8rem; background: rgba(0,0,0,0.04); padding: 0.2rem 0.5rem; border-radius: 6px;">{{ $srv->icon }}</code>
                            </td>
                            <td>
                                <span style="font-weight: 700; color: #0f172a;">{{ $srv->caregivers_count }}</span>
                                <span style="font-size: 0.78rem; color: var(--text-muted);">verified</span>
                            </td>
                            <td>
                                <x-badge :tone="$srv->is_active ? 'success' : 'neutral'">
                                    {{ $srv->is_active ? 'Active' : 'Hidden' }}
                                </x-badge>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.5rem; align-items: center;">
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="openEditModal({{ $srv->id }}, '{{ addslashes($srv->name) }}', '{{ addslashes($srv->short_description) }}', '{{ addslashes($srv->description ?? '') }}', '{{ $srv->icon }}', {{ $srv->sort_order }}, {{ $srv->is_active ? 'true' : 'false' }})">
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('admin.services.destroy', $srv->id) }}" onsubmit="return confirm('Delete this service category? This may affect existing caregiver profiles.');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); background: rgba(239, 68, 68, 0.05); padding: 0.35rem 0.6rem; border-radius: var(--radius-sm);">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 3rem;">No services defined yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Service Modal -->
    <div id="new-service-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
        <div class="glass-card" style="background: rgba(255, 255, 255, 0.95); max-width: 580px; width: 100%; border-radius: var(--radius-xl); padding: 2rem; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin: 0;">Add Service Category</h3>
                <button type="button" onclick="document.getElementById('new-service-modal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Service Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Elderly Care" class="glass-input" style="width: 100%;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Short Description (1 line) *</label>
                    <input type="text" name="short_description" required placeholder="Compassionate senior assistance, mobility and medication monitoring" class="glass-input" style="width: 100%;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Full Description / Care Protocols</label>
                    <textarea name="description" rows="3" class="glass-textarea" placeholder="Detailed scope of service, what is covered..." style="width: 100%; font-size: 0.85rem;"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Icon Identifier *</label>
                        <input type="text" name="icon" required placeholder="e.g. heart, user, ambulance" class="glass-input" style="width: 100%;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Sort Order *</label>
                        <input type="number" name="sort_order" required value="1" min="1" class="glass-input" style="width: 100%;">
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Category Banner Image</label>
                    <input type="file" name="image" accept="image/*" class="glass-input" style="width: 100%;">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" onclick="document.getElementById('new-service-modal').style.display='none'" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Service</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Service Modal -->
    <div id="edit-service-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
        <div class="glass-card" style="background: rgba(255, 255, 255, 0.95); max-width: 580px; width: 100%; border-radius: var(--radius-xl); padding: 2rem; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin: 0;">Edit Service Category</h3>
                <button type="button" onclick="document.getElementById('edit-service-modal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>

            <form id="edit-service-form" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Service Name *</label>
                    <input type="text" id="edit-name" name="name" required class="glass-input" style="width: 100%;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Short Description *</label>
                    <input type="text" id="edit-short-desc" name="short_description" required class="glass-input" style="width: 100%;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Full Description / Care Protocols</label>
                    <textarea id="edit-desc" name="description" rows="3" class="glass-textarea" style="width: 100%; font-size: 0.85rem;"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Icon Identifier *</label>
                        <input type="text" id="edit-icon" name="icon" required class="glass-input" style="width: 100%;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Sort Order *</label>
                        <input type="number" id="edit-sort-order" name="sort_order" required min="1" class="glass-input" style="width: 100%;">
                    </div>
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem;">Replace Banner Image</label>
                    <input type="file" name="image" accept="image/*" class="glass-input" style="width: 100%;">
                </div>

                <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" id="edit-is-active" name="is_active" value="1" style="width: 18px; height: 18px;">
                    <label for="edit-is-active" style="font-size: 0.9rem; font-weight: 700; color: #0f172a; cursor: pointer;">Active and visible on Marketplace</label>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" onclick="document.getElementById('edit-service-modal').style.display='none'" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Service</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openEditModal(id, name, shortDesc, desc, icon, sortOrder, isActive) {
            const form = document.getElementById('edit-service-form');
            form.action = `/admin/services/${id}`;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-short-desc').value = shortDesc;
            document.getElementById('edit-desc').value = desc;
            document.getElementById('edit-icon').value = icon;
            document.getElementById('edit-sort-order').value = sortOrder;
            document.getElementById('edit-is-active').checked = isActive;
            document.getElementById('edit-service-modal').style.display = 'flex';
        }
    </script>
    @endpush
</x-layouts.dashboard>
