<x-layouts.dashboard>
    <x-slot:title>Caregiver Profile & Rates — CareMate BD</x-slot:title>
    <x-slot:header>Caregiver Profile & Service Rates</x-slot:header>
    <x-slot:subheading>Update your public biography, daily rates, work arrangements, and professional service offerings.</x-slot:subheading>

    <div style="max-width: 820px; margin: 0 auto;">
        <!-- Validation Error Alert -->
        @if ($errors->any())
            <div class="alert alert-error" style="margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 0.75rem;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; margin-top: 0.15rem;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                <div>
                    <strong style="display: block; font-weight: 700; margin-bottom: 0.25rem;">Please correct the errors below:</strong>
                    <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.88rem; line-height: 1.5;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="glass-card" style="padding: 2.5rem; border-radius: var(--radius-xl);">
            <form method="POST" action="{{ route('caregiver.profile.update') }}" enctype="multipart/form-data">
                @csrf

                <!-- Basic Profile -->
                <div style="margin-bottom: 2.5rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem; display: flex; align-items: center; justify-content: space-between;">
                        <span>1. Personal Information & Headshot</span>
                        <span style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted);">Public Caregiver Identity</span>
                    </h3>

                    <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <img src="{{ $caregiver->avatarUrl() }}" alt="{{ $caregiver->user->name }}" style="width: 84px; height: 84px; border-radius: 50%; object-fit: cover; border: 3px solid #fff; box-shadow: 0 4px 12px rgba(10, 57, 74, 0.15);">
                        <div style="flex: 1;">
                            <label class="form-label" style="font-weight: 700; color: #0f172a;">Update Headshot Photo</label>
                            <input type="file" name="profile_photo" accept="image/*" class="glass-input" style="font-size: 0.82rem;">
                            <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">Supported: JPG, PNG, WebP (Max 5MB). Professional portraits build family trust.</span>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1rem;">
                        <div>
                            <label class="form-label">Full Name <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $caregiver->user->name) }}" required class="glass-input">
                        </div>
                        <div>
                            <label class="form-label">Contact Phone <span style="color: #ef4444;">*</span></label>
                            <input type="tel" name="phone" value="{{ old('phone', $caregiver->user->phone) }}" required class="glass-input">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div>
                            <label class="form-label">Email (Account Login — Read-only)</label>
                            <input type="email" value="{{ $caregiver->user->email }}" disabled class="glass-input" style="opacity: 0.7; cursor: not-allowed; background: rgba(0,0,0,0.02);">
                        </div>
                        <div>
                            <label class="form-label">National ID Number (Verified)</label>
                            <input type="text" value="{{ $caregiver->nid_number ? '•••• •••• ' . substr($caregiver->nid_number, -4) : 'Under Verification' }}" disabled class="glass-input" style="opacity: 0.7; cursor: not-allowed; background: rgba(0,0,0,0.02); font-family: monospace;">
                        </div>
                    </div>
                </div>

                <!-- Location & Service Region -->
                <div style="margin-bottom: 2.5rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem; display: flex; align-items: center; justify-content: space-between;">
                        <span>2. Location & Service Territory</span>
                        <span style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted);">Deployment Division & District</span>
                    </h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div>
                            <label class="form-label">Division</label>
                            <select name="division_id" id="cgDivSelect" class="glass-input">
                                <option value="">Select Division</option>
                                @foreach ($divisions as $div)
                                    <option value="{{ $div->id }}" {{ old('division_id', $caregiver->division_id) == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">District</label>
                            <select name="district_id" id="cgDistSelect" class="glass-input">
                                <option value="">Select District</option>
                                @foreach ($districts as $dst)
                                    <option value="{{ $dst->id }}" data-division-id="{{ $dst->parent_id }}" {{ old('district_id', $caregiver->district_id) == $dst->id ? 'selected' : '' }}>{{ $dst->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Primary City / Thana</label>
                            <input type="text" name="city" value="{{ old('city', $caregiver->city) }}" placeholder="e.g. Dhaka, Bhola Sadar..." class="glass-input">
                        </div>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label">Present Residence Address</label>
                        <input type="text" name="present_address" value="{{ old('present_address', $caregiver->present_address) }}" placeholder="House, Road, Area..." class="glass-input">
                    </div>
                </div>

                <!-- Professional Rates & Schedule -->
                <div style="margin-bottom: 2.5rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem; display: flex; align-items: center; justify-content: space-between;">
                        <span>3. Service Rates & Arrangements</span>
                        <span style="font-size: 0.75rem; font-weight: 600; color: var(--brand-accent);">All Rates in BDT (৳)</span>
                    </h3>

                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem;" class="rates-grid">
                        <div>
                            <label class="form-label">Daily Rate (৳) <span style="color: #ef4444;">*</span></label>
                            <input type="number" name="daily_rate" value="{{ old('daily_rate', $caregiver->daily_rate) }}" required min="100" class="glass-input" placeholder="e.g. 1500" style="font-weight: 700; color: var(--brand-primary);">
                            <span style="font-size: 0.72rem; color: var(--text-muted);">Standard per-day fee</span>
                        </div>
                        <div>
                            <label class="form-label">Hourly Rate (৳)</label>
                            <input type="number" name="hourly_rate" value="{{ old('hourly_rate', $caregiver->hourly_rate) }}" min="0" class="glass-input" placeholder="e.g. 200">
                            <span style="font-size: 0.72rem; color: var(--text-muted);">For part-time shifts</span>
                        </div>
                        <div>
                            <label class="form-label">Weekly Rate (৳)</label>
                            <input type="number" name="weekly_rate" value="{{ old('weekly_rate', $caregiver->weekly_rate) }}" min="0" class="glass-input" placeholder="e.g. 9000">
                            <span style="font-size: 0.72rem; color: var(--text-muted);">Optional package</span>
                        </div>
                        <div>
                            <label class="form-label">Monthly Rate (৳)</label>
                            <input type="number" name="monthly_rate" value="{{ old('monthly_rate', $caregiver->monthly_rate) }}" min="0" class="glass-input" placeholder="e.g. 35000">
                            <span style="font-size: 0.72rem; color: var(--text-muted);">Long-term contracts</span>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                        <div>
                            <label class="form-label">Employment Type</label>
                            <select name="employment_type" class="glass-input">
                                <option value="full_time" {{ old('employment_type', $caregiver->employment_type) === 'full_time' ? 'selected' : '' }}>Full-Time Duty</option>
                                <option value="part_time" {{ old('employment_type', $caregiver->employment_type) === 'part_time' ? 'selected' : '' }}>Part-Time Duty</option>
                                <option value="both" {{ old('employment_type', $caregiver->employment_type) === 'both' ? 'selected' : '' }}>Both (Flexible)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Living Arrangement</label>
                            <select name="live_type" class="glass-input">
                                <option value="live_out" {{ old('live_type', $caregiver->live_type) === 'live_out' ? 'selected' : '' }}>Live-Out (Day / Night)</option>
                                <option value="live_in" {{ old('live_type', $caregiver->live_type) === 'live_in' ? 'selected' : '' }}>Live-In (24/7 Resident)</option>
                                <option value="both" {{ old('live_type', $caregiver->live_type) === 'both' ? 'selected' : '' }}>Both (Available for either)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Patient Gender Preference</label>
                            <select name="preferred_client_gender" class="glass-input">
                                <option value="any" {{ old('preferred_client_gender', $caregiver->preferred_client_gender) === 'any' ? 'selected' : '' }}>Any Gender</option>
                                <option value="female" {{ old('preferred_client_gender', $caregiver->preferred_client_gender) === 'female' ? 'selected' : '' }}>Female Patients Only</option>
                                <option value="male" {{ old('preferred_client_gender', $caregiver->preferred_client_gender) === 'male' ? 'selected' : '' }}>Male Patients Only</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Professional Title & Services -->
                <div style="margin-bottom: 2.5rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem; display: flex; align-items: center; justify-content: space-between;">
                        <span>4. Professional Experience & Bio</span>
                        <span style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted);">Clinical & Care Credentials</span>
                    </h3>

                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                        <div>
                            <label class="form-label">Professional Title / Designation <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="caregiver_type" value="{{ old('caregiver_type', $caregiver->caregiver_type) }}" required class="glass-input" placeholder="e.g. Senior Registered Nurse, Certified Elderly Care Aide">
                        </div>
                        <div>
                            <label class="form-label">Years of Experience <span style="color: #ef4444;">*</span></label>
                            <input type="number" name="years_experience" value="{{ old('years_experience', $caregiver->years_experience) }}" required min="0" max="60" class="glass-input" placeholder="e.g. 5">
                        </div>
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label class="form-label">Short Summary (Headline) <span style="color: #ef4444;">*</span></label>
                        <textarea name="about" rows="3" required class="glass-input" placeholder="Describe your primary caregiving skills and dedication in 2-3 sentences...">{{ old('about', $caregiver->about) }}</textarea>
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label class="form-label">Full Professional Biography</label>
                        <textarea name="bio" rows="5" class="glass-input" placeholder="Detail your background, patient care history, certifications, and caring philosophy...">{{ old('bio', $caregiver->bio) }}</textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                        <div>
                            <label class="form-label">Skills (Comma-separated)</label>
                            <input type="text" name="skills" value="{{ old('skills', is_array($caregiver->skills) ? implode(', ', $caregiver->skills) : $caregiver->skills) }}" class="glass-input" placeholder="e.g. Wound Dressing, Vital Monitoring, Dementia Care">
                        </div>
                        <div>
                            <label class="form-label">Specializations (Comma-separated)</label>
                            <input type="text" name="specializations" value="{{ old('specializations', is_array($caregiver->specializations) ? implode(', ', $caregiver->specializations) : $caregiver->specializations) }}" class="glass-input" placeholder="e.g. Stroke Recovery, Post-Operative, Pediatric">
                        </div>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label class="form-label">Languages Spoken (Comma-separated)</label>
                        <input type="text" name="languages" value="{{ old('languages', is_array($caregiver->languages) ? implode(', ', $caregiver->languages) : $caregiver->languages) }}" class="glass-input" placeholder="e.g. Bengali, English, Hindi">
                    </div>

                    <div>
                        <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #0f172a;">
                            Services Provided on Platform <span style="color: #ef4444;">*</span>
                        </label>
                        <span style="font-size: 0.78rem; color: var(--text-muted); display: block; margin-bottom: 0.75rem;">Select all categories where you are qualified and ready to take duty shifts:</span>
                        
                        @php $activeSvcIds = $caregiver->services->pluck('id')->toArray(); @endphp
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                            @foreach ($services as $s)
                                <label style="display: flex; align-items: center; gap: 0.65rem; padding: 0.75rem 1rem; background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(226, 232, 240, 0.9); border-radius: var(--radius-md); cursor: pointer; font-size: 0.9rem; transition: all 0.2s ease;">
                                    <input type="checkbox" name="service_ids[]" value="{{ $s->id }}" {{ in_array($s->id, old('service_ids', $activeSvcIds)) ? 'checked' : '' }} style="accent-color: var(--brand-primary); width: 18px; height: 18px;">
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a;">{{ $s->name }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">Starting ৳{{ number_format($s->base_rate_daily) }}/day</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div style="border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 1.5rem; display: flex; justify-content: flex-end; gap: 1rem;">
                    <a href="{{ route('caregiver.dashboard') }}" class="btn btn-secondary" style="padding: 0.75rem 1.75rem;">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2.5rem; font-size: 1.05rem;">
                        Save Profile & Rates Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const divSel = document.getElementById('cgDivSelect');
        const distSel = document.getElementById('cgDistSelect');

        if (divSel && distSel) {
            const distOptions = Array.from(distSel.querySelectorAll('option')).filter(opt => opt.value !== '');
            const initialDist = "{{ old('district_id', $caregiver->district_id) }}";

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
            }

            divSel.addEventListener('change', function () {
                filterDistricts(true);
            });

            if (divSel.value) {
                filterDistricts(false);
            }
        }
    });
    </script>
    @endpush
</x-layouts.dashboard>
