<x-layouts.dashboard>
    <x-slot:title>Edit Caregiver: {{ $caregiver->user->name }} — Admin | CareMate BD</x-slot:title>
    <x-slot:header>Edit Caregiver: {{ $caregiver->user->name }}</x-slot:header>
    <x-slot:subheading>Update provider rates, marketplace sequencing, verification status, and clinical specialties.</x-slot:subheading>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <a href="{{ route('admin.caregivers.show', $caregiver->id) }}" class="btn btn-secondary btn-sm">
            ← Back to Caregiver Profile
        </a>
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            <x-badge :tone="$caregiver->status->badgeTone()">{{ $caregiver->status->label() }}</x-badge>
            @if ($caregiver->sort_order > 0)
                <span class="badge" style="background: rgba(21, 121, 142, 0.12); color: var(--brand-primary); font-weight: 700;">
                    Sequence #{{ $caregiver->sort_order }}
                </span>
            @endif
        </div>
    </div>

    <form method="POST" action="{{ route('admin.caregivers.update', $caregiver->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: var(--radius-md); padding: 1rem 1.25rem; margin-bottom: 2rem; color: #b91c1c;">
                <strong style="display: block; font-weight: 700; margin-bottom: 0.35rem;">⚠️ Please review the following errors:</strong>
                <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.88rem; line-height: 1.5;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: flex-start;" class="edit-layout">
            
            <!-- Left Main Column -->
            <div style="display: flex; flex-direction: column; gap: 2rem;">

                <!-- 1. Marketplace Ordering & Status (Highlighted) -->
                <div class="glass-card" style="padding: 2rem; border: 2px solid rgba(21, 121, 142, 0.3);">
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">
                        ⚡ Marketplace Priority & Visibility Controls
                    </h3>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                        Configure which caregiver appears first, their ranking position, and platform status.
                    </p>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div>
                            <label class="form-label" style="font-weight: 700; color: var(--brand-primary);">
                                Display Sequence / Sort Order <span style="font-weight: 400; font-size: 0.78rem; color: var(--text-muted);">(1 = Top First, 2 = Next)</span>
                            </label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $caregiver->sort_order ?? 0) }}" min="0" max="9999" class="glass-input" style="font-size: 1.1rem; font-weight: 800; color: var(--brand-primary);">
                            <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">
                                Lower numbers appear first in marketplace listings. Enter 0 for standard algorithm.
                            </span>
                        </div>

                        <div>
                            <label class="form-label" style="font-weight: 700;">Account & Verification Status <span style="color: #ef4444;">*</span></label>
                            <select name="status" class="glass-input" required>
                                @foreach (\App\Enums\CaregiverStatus::cases() as $st)
                                    <option value="{{ $st->value }}" {{ old('status', $caregiver->status->value) === $st->value ? 'selected' : '' }}>
                                        {{ $st->label() }} ({{ strtoupper($st->value) }})
                                    </option>
                                @endforeach
                            </select>
                            <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">
                                Note: Only Published caregivers appear in public search.
                            </span>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.25); border-radius: var(--radius-md);">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $caregiver->is_featured) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #d97706;">
                            <label for="is_featured" style="font-weight: 700; color: #92400e; cursor: pointer; margin: 0; font-size: 0.9rem;">
                                ★ Featured Top Provider (Pinned Badge)
                            </label>
                        </div>

                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.25); border-radius: var(--radius-md);">
                            <input type="checkbox" name="is_available" id="is_available" value="1" {{ old('is_available', $caregiver->is_available) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #059669;">
                            <label for="is_available" style="font-weight: 700; color: #065f46; cursor: pointer; margin: 0; font-size: 0.9rem;">
                                Available for New Hiring Assignments
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Status Change Note / Reason (Optional)</label>
                        <input type="text" name="status_reason" value="{{ old('status_reason', $caregiver->status_reason) }}" placeholder="e.g. Approved after physical document audit at Dhaka office" class="glass-input">
                    </div>
                </div>

                <!-- 2. Basic Account & Contact -->
                <div class="glass-card" style="padding: 2rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                        1. Personal Identity & Account Details
                    </h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div>
                            <label class="form-label">Full Name <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $caregiver->user->name) }}" required class="glass-input">
                        </div>
                        <div>
                            <label class="form-label">Mobile Phone Number <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', $caregiver->user->phone) }}" required class="glass-input">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div>
                            <label class="form-label">Email Address <span style="color: #ef4444;">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $caregiver->user->email) }}" required class="glass-input">
                        </div>
                        <div>
                            <label class="form-label">Reset Password (Leave blank to keep current)</label>
                            <input type="password" name="password" placeholder="New secure password" class="glass-input">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div>
                            <label class="form-label">Gender <span style="color: #ef4444;">*</span></label>
                            <select name="gender" required class="glass-input">
                                <option value="female" {{ old('gender', $caregiver->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="male" {{ old('gender', $caregiver->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="other" {{ old('gender', $caregiver->gender) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $caregiver->date_of_birth?->format('Y-m-d')) }}" class="glass-input">
                        </div>
                        <div>
                            <label class="form-label">Replace Avatar</label>
                            <input type="file" name="profile_photo" accept="image/*" class="glass-input" style="font-size: 0.85rem;">
                        </div>
                    </div>
                </div>

                <!-- 3. Professional Profile & Education -->
                <div class="glass-card" style="padding: 2rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                        2. Professional Background & Services
                    </h3>

                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div>
                            <label class="form-label">Professional Caregiver Title / Type <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="caregiver_type" value="{{ old('caregiver_type', $caregiver->caregiver_type) }}" required class="glass-input" placeholder="e.g. Senior Geriatric Nurse & Patient Attendant">
                        </div>
                        <div>
                            <label class="form-label">Years of Experience <span style="color: #ef4444;">*</span></label>
                            <input type="number" name="years_experience" value="{{ old('years_experience', $caregiver->years_experience) }}" min="0" max="60" required class="glass-input">
                        </div>
                    </div>

                    <!-- Care Services Selector -->
                    <div style="margin-bottom: 1.5rem;">
                        <label class="form-label">Services Provided (Check all that apply)</label>
                        @php $assignedServices = $caregiver->services->pluck('id')->toArray(); @endphp
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; margin-bottom: 1rem;">
                            @foreach ($services as $srv)
                                <label style="display: flex; align-items: center; gap: 0.6rem; padding: 0.75rem; background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(226, 232, 240, 0.9); border-radius: var(--radius-md); cursor: pointer;">
                                    <input type="checkbox" name="service_ids[]" value="{{ $srv->id }}" {{ in_array($srv->id, old('service_ids', $assignedServices)) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: var(--brand-primary);">
                                    <span style="font-weight: 600; font-size: 0.9rem;">{{ $srv->name }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div>
                            <label class="form-label">Primary Service Specialty</label>
                            <select name="primary_service_id" class="glass-input">
                                <option value="">Select Primary Service</option>
                                @foreach ($services as $srv)
                                    <option value="{{ $srv->id }}" {{ old('primary_service_id', $caregiver->primaryService()?->id) == $srv->id ? 'selected' : '' }}>
                                        {{ $srv->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div>
                            <label class="form-label">Educational Qualification</label>
                            <input type="text" name="education_qualification" value="{{ old('education_qualification', $caregiver->education_qualification) }}" placeholder="e.g. Diploma in Nursing Science" class="glass-input">
                        </div>
                        <div>
                            <label class="form-label">Institution / University</label>
                            <input type="text" name="education_institution" value="{{ old('education_institution', $caregiver->education_institution) }}" placeholder="e.g. Dhaka Nursing College" class="glass-input">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div>
                            <label class="form-label">Previous Workplace / Hospital</label>
                            <input type="text" name="previous_workplace" value="{{ old('previous_workplace', $caregiver->previous_workplace) }}" placeholder="e.g. Square Hospital" class="glass-input">
                        </div>
                        <div>
                            <label class="form-label">Passing Year</label>
                            <input type="number" name="education_passing_year" value="{{ old('education_passing_year', $caregiver->education_passing_year) }}" min="1970" max="{{ date('Y') }}" class="glass-input">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Summary of Prior Experience</label>
                        <textarea name="previous_experience" rows="2" class="glass-input">{{ old('previous_experience', $caregiver->previous_experience) }}</textarea>
                    </div>
                </div>

                <!-- 4. Bio & Description -->
                <div class="glass-card" style="padding: 2rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                        3. Public Bio & About
                    </h3>

                    <div style="margin-bottom: 1.25rem;">
                        <label class="form-label">Short Summary (About) <span style="color: #ef4444;">*</span></label>
                        <textarea name="about" rows="3" required class="glass-input">{{ old('about', $caregiver->about) }}</textarea>
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label class="form-label">Detailed Biography</label>
                        <textarea name="bio" rows="5" class="glass-input">{{ old('bio', $caregiver->bio) }}</textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div>
                            <label class="form-label">Skills (Comma-separated)</label>
                            <input type="text" name="skills" value="{{ old('skills', is_array($caregiver->skills) ? implode(', ', $caregiver->skills) : '') }}" class="glass-input" placeholder="CPR, Diabetes, Catheter">
                        </div>
                        <div>
                            <label class="form-label">Languages (Comma-separated)</label>
                            <input type="text" name="languages" value="{{ old('languages', is_array($caregiver->languages) ? implode(', ', $caregiver->languages) : '') }}" class="glass-input" placeholder="Bengali, English">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div style="display: flex; flex-direction: column; gap: 2rem;">
                
                <!-- Pricing & Rates -->
                <div class="glass-card" style="padding: 1.75rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                        Pricing & Work Terms
                    </h3>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label">Daily Care Rate (BDT) <span style="color: #ef4444;">*</span></label>
                        <input type="number" name="daily_rate" value="{{ old('daily_rate', $caregiver->daily_rate) }}" min="0" required class="glass-input" style="font-weight: 800; color: #059669;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                        <div>
                            <label class="form-label" style="font-size: 0.8rem;">Hourly Rate</label>
                            <input type="number" name="hourly_rate" value="{{ old('hourly_rate', $caregiver->hourly_rate) }}" min="0" class="glass-input">
                        </div>
                        <div>
                            <label class="form-label" style="font-size: 0.8rem;">Weekly Rate</label>
                            <input type="number" name="weekly_rate" value="{{ old('weekly_rate', $caregiver->weekly_rate) }}" min="0" class="glass-input">
                        </div>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label" style="font-size: 0.8rem;">Monthly Rate</label>
                        <input type="number" name="monthly_rate" value="{{ old('monthly_rate', $caregiver->monthly_rate) }}" min="0" class="glass-input">
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label" style="font-size: 0.8rem;">Employment Type</label>
                        <select name="employment_type" required class="glass-input">
                            <option value="full_time" {{ old('employment_type', $caregiver->employment_type) === 'full_time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part_time" {{ old('employment_type', $caregiver->employment_type) === 'part_time' ? 'selected' : '' }}>Part Time</option>
                            <option value="both" {{ old('employment_type', $caregiver->employment_type) === 'both' ? 'selected' : '' }}>Both Full/Part Time</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label" style="font-size: 0.8rem;">Living Style</label>
                        <select name="live_type" required class="glass-input">
                            <option value="live_in" {{ old('live_type', $caregiver->live_type) === 'live_in' ? 'selected' : '' }}>Live-In</option>
                            <option value="live_out" {{ old('live_type', $caregiver->live_type) === 'live_out' ? 'selected' : '' }}>Live-Out</option>
                            <option value="both" {{ old('live_type', $caregiver->live_type) === 'both' ? 'selected' : '' }}>Both</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Preferred Client Gender</label>
                        <select name="preferred_client_gender" required class="glass-input">
                            <option value="any" {{ old('preferred_client_gender', $caregiver->preferred_client_gender) === 'any' ? 'selected' : '' }}>Any Gender</option>
                            <option value="female" {{ old('preferred_client_gender', $caregiver->preferred_client_gender) === 'female' ? 'selected' : '' }}>Female Only</option>
                            <option value="male" {{ old('preferred_client_gender', $caregiver->preferred_client_gender) === 'male' ? 'selected' : '' }}>Male Only</option>
                        </select>
                    </div>
                </div>

                <!-- Geographic Location (Cascading Selects) -->
                <div class="glass-card" style="padding: 1.75rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                        Location & Address
                    </h3>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label" style="font-size: 0.8rem;">Division</label>
                        <select name="division_id" id="divisionSelect" class="glass-input">
                            <option value="">Select Division</option>
                            @foreach ($divisions as $div)
                                <option value="{{ $div->id }}" {{ old('division_id', $caregiver->division_id) == $div->id ? 'selected' : '' }}>
                                    {{ $div->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label" style="font-size: 0.8rem;">District</label>
                        <select name="district_id" id="districtSelect" class="glass-input">
                            <option value="">Select District</option>
                            @foreach ($districts as $dst)
                                <option value="{{ $dst->id }}" data-parent="{{ $dst->parent_id }}" {{ old('district_id', $caregiver->district_id) == $dst->id ? 'selected' : '' }}>
                                    {{ $dst->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label" style="font-size: 0.8rem;">Area / Upazila</label>
                        <select name="area_id" id="areaSelect" class="glass-input">
                            <option value="">Select Upazila / Area</option>
                            @foreach ($areas as $ar)
                                <option value="{{ $ar->id }}" data-parent="{{ $ar->parent_id }}" {{ old('area_id', $caregiver->area_id) == $ar->id ? 'selected' : '' }}>
                                    {{ $ar->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label" style="font-size: 0.8rem;">City / Specific Locality</label>
                        <input type="text" name="city" value="{{ old('city', $caregiver->city) }}" placeholder="e.g. Mirpur-10, Dhaka" class="glass-input">
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label" style="font-size: 0.8rem;">Present Address</label>
                        <input type="text" name="present_address" value="{{ old('present_address', $caregiver->present_address) }}" class="glass-input">
                    </div>

                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Permanent Address</label>
                        <input type="text" name="permanent_address" value="{{ old('permanent_address', $caregiver->permanent_address) }}" class="glass-input">
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="glass-card" style="padding: 1.75rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                        Emergency Contact
                    </h3>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label" style="font-size: 0.8rem;">Contact Name</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $caregiver->emergency_contact_name) }}" class="glass-input">
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label" style="font-size: 0.8rem;">Phone Number</label>
                        <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $caregiver->emergency_contact_phone) }}" class="glass-input">
                    </div>

                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Relationship</label>
                        <input type="text" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $caregiver->emergency_contact_relationship) }}" placeholder="e.g. Spouse, Brother, Father" class="glass-input">
                    </div>
                </div>

                <!-- Submit Button Bar -->
                <div class="glass-card" style="padding: 1.5rem; text-align: center;">
                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">
                        💾 Save Caregiver Changes
                    </button>
                    <a href="{{ route('admin.caregivers.show', $caregiver->id) }}" class="btn btn-secondary" style="width: 100%; justify-content: center; margin-top: 0.75rem;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const divisionSelect = document.getElementById('divisionSelect');
        const districtSelect = document.getElementById('districtSelect');
        const areaSelect = document.getElementById('areaSelect');

        if (divisionSelect && districtSelect) {
            const districtOptions = Array.from(districtSelect.querySelectorAll('option')).filter(o => o.value !== '');
            const areaOptions = areaSelect ? Array.from(areaSelect.querySelectorAll('option')).filter(o => o.value !== '') : [];

            const savedDistrictId = "{{ old('district_id', $caregiver->district_id) }}";
            const savedAreaId = "{{ old('area_id', $caregiver->area_id) }}";

            function filterDistricts(resetSelected) {
                const selectedDivisionId = divisionSelect.value;
                const currentSelected = resetSelected ? '' : (districtSelect.value || savedDistrictId);

                districtSelect.innerHTML = '<option value="">Select District</option>';

                districtOptions.forEach(opt => {
                    if (!selectedDivisionId || opt.dataset.parent === selectedDivisionId) {
                        const clone = opt.cloneNode(true);
                        if (clone.value === currentSelected) {
                            clone.selected = true;
                        }
                        districtSelect.appendChild(clone);
                    }
                });

                filterAreas(resetSelected);
            }

            function filterAreas(resetSelected) {
                if (!areaSelect) return;
                const selectedDistrictId = districtSelect.value;
                const currentArea = resetSelected ? '' : (areaSelect.value || savedAreaId);

                areaSelect.innerHTML = '<option value="">Select Upazila / Area</option>';

                areaOptions.forEach(opt => {
                    if (!selectedDistrictId || opt.dataset.parent === selectedDistrictId) {
                        const clone = opt.cloneNode(true);
                        if (clone.value === currentArea) {
                            clone.selected = true;
                        }
                        areaSelect.appendChild(clone);
                    }
                });
            }

            divisionSelect.addEventListener('change', () => filterDistricts(true));
            districtSelect.addEventListener('change', () => filterAreas(true));

            // Initial filtering if division is preselected
            if (divisionSelect.value) {
                filterDistricts(false);
            }
        }
    });
    </script>
    @endpush
</x-layouts.dashboard>
