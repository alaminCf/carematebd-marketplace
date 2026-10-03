<x-layouts.dashboard>
    <x-slot:title>Family Profile & Address — CareMate BD</x-slot:title>
    <x-slot:header>Account & Family Profile</x-slot:header>
    <x-slot:subheading>Update your residence location, emergency contacts, and profile details.</x-slot:subheading>

    <div style="max-width: 680px; margin: 0 auto;">
        <div class="glass-card" style="padding: 2.25rem; border-radius: var(--radius-xl);">
            <form method="POST" action="{{ route('client.profile.update') }}" enctype="multipart/form-data">
                @csrf

                <div style="margin-bottom: 2rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                        1. Personal Profile
                    </h3>

                    <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}" style="width: 72px; height: 72px; border-radius: 50%; object-fit: cover;">
                        <div>
                            <label class="form-label">Update Profile Picture</label>
                            <input type="file" name="profile_photo" accept="image/*" class="glass-input" style="font-size: 0.82rem;">
                        </div>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label">Full Name <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="glass-input">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label class="form-label">Email Address (Read-only)</label>
                            <input type="email" value="{{ auth()->user()->email }}" disabled class="glass-input" style="opacity: 0.7; cursor: not-allowed;">
                        </div>
                        <div>
                            <label class="form-label">Contact Phone <span style="color: #ef4444;">*</span></label>
                            <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required class="glass-input">
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 2rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                        2. Care Residence Address
                    </h3>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label">Street Address & Flat # <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="present_address" value="{{ old('present_address', $client->present_address) }}" required class="glass-input">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                        <div>
                            <label class="form-label">Division</label>
                            <select name="division_id" id="profileDivSelect" class="glass-input">
                                <option value="">Select Division</option>
                                @foreach ($divisions as $div)
                                    <option value="{{ $div->id }}" {{ old('division_id', $client->division_id) == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">District</label>
                            <select name="district_id" id="profileDistSelect" class="glass-input">
                                <option value="">Select District</option>
                                @foreach ($districts as $dst)
                                    <option value="{{ $dst->id }}" data-division-id="{{ $dst->parent_id }}" {{ old('district_id', $client->district_id) == $dst->id ? 'selected' : '' }}>{{ $dst->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Area / Thana</label>
                            <select name="area_id" id="profileAreaSelect" class="glass-input">
                                <option value="">Select Area</option>
                                @foreach ($areas as $a)
                                    <option value="{{ $a->id }}" data-district-id="{{ $a->parent_id }}" {{ old('area_id', $client->area_id) == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 2rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                        3. Emergency Family Contact
                    </h3>

                    <div style="display: grid; grid-template-columns: 1.2fr 1fr 1fr; gap: 1rem;">
                        <div>
                            <label class="form-label">Contact Name</label>
                            <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $client->emergency_contact_name) }}" class="glass-input">
                        </div>
                        <div>
                            <label class="form-label">Emergency Phone</label>
                            <input type="tel" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $client->emergency_contact_phone) }}" class="glass-input">
                        </div>
                        <div>
                            <label class="form-label">Relationship</label>
                            <input type="text" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $client->emergency_contact_relationship) }}" class="glass-input">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Save Updated Profile
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const divSel = document.getElementById('profileDivSelect');
        const distSel = document.getElementById('profileDistSelect');
        const areaSel = document.getElementById('profileAreaSelect');

        if (divSel && distSel && areaSel) {
            const distOptions = Array.from(distSel.querySelectorAll('option')).filter(opt => opt.value !== '');
            const areaOptions = Array.from(areaSel.querySelectorAll('option')).filter(opt => opt.value !== '');

            const initialDist = "{{ old('district_id', $client->district_id) }}";
            const initialArea = "{{ old('area_id', $client->area_id) }}";

            function filterDistricts(reset) {
                const divId = divSel.value;
                const curDist = reset ? '' : (distSel.value || initialDist);

                distSel.innerHTML = '<option value="">' + (divId ? 'Select District' : 'Select Division First') + '</option>';

                let found = false;
                distOptions.forEach(function (opt) {
                    if (!divId || String(opt.getAttribute('data-division-id')) === String(divId)) {
                        const clone = opt.cloneNode(true);
                        if (String(clone.value) === String(curDist)) {
                            clone.selected = true;
                            found = true;
                        }
                        distSel.appendChild(clone);
                    }
                });

                if (!found && !reset && curDist) {
                    distSel.value = '';
                }

                filterAreas(reset);
            }

            function filterAreas(reset) {
                const distId = distSel.value;
                const curArea = reset ? '' : (areaSel.value || initialArea);

                areaSel.innerHTML = '<option value="">' + (distId ? 'Select Area / Thana' : 'Select District First') + '</option>';

                let found = false;
                areaOptions.forEach(function (opt) {
                    if (!distId || String(opt.getAttribute('data-district-id')) === String(distId)) {
                        const clone = opt.cloneNode(true);
                        if (String(clone.value) === String(curArea)) {
                            clone.selected = true;
                            found = true;
                        }
                        areaSel.appendChild(clone);
                    }
                });

                if (!found && !reset && curArea) {
                    areaSel.value = '';
                }
            }

            divSel.addEventListener('change', function () {
                filterDistricts(true);
            });

            distSel.addEventListener('change', function () {
                filterAreas(true);
            });

            if (divSel.value) {
                filterDistricts(false);
            }
        }
    });
    </script>
    @endpush
</x-layouts.dashboard>
