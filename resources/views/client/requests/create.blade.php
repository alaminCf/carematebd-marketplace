<x-layouts.dashboard>
    <x-slot:title>Request Caregiver Booking — CareMate BD</x-slot:title>
    <x-slot:header>Submit Care Booking Request</x-slot:header>
    <x-slot:subheading>CareMate Admin will review patient requirements, verify schedule, and confirm caregiver assignment.</x-slot:subheading>

    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 2rem; align-items: flex-start;" class="request-grid">
        <!-- Main Form -->
        <div class="glass-card" style="padding: 2.25rem; border-radius: var(--radius-xl);">
            <form method="POST" action="{{ route('client.requests.store', $caregiver->id) }}">
                @csrf

                <!-- Section 1: Patient Details -->
                <div style="margin-bottom: 2rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                        1. Care Recipient (Patient) Information
                    </h3>

                    <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label class="form-label">Patient Full Name <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="care_recipient_name" value="{{ old('care_recipient_name') }}" required placeholder="e.g. Haji Abdul Jalil" class="glass-input">
                            @error('care_recipient_name') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="form-label">Relationship to You <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="care_recipient_relationship" value="{{ old('care_recipient_relationship', 'Father') }}" required placeholder="e.g. Father, Mother, Child" class="glass-input">
                            @error('care_recipient_relationship') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label class="form-label">Patient Age <span style="color: #ef4444;">*</span></label>
                            <input type="number" name="care_recipient_age" value="{{ old('care_recipient_age', 72) }}" min="0" max="120" required class="glass-input">
                            @error('care_recipient_age') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="form-label">Patient Gender <span style="color: #ef4444;">*</span></label>
                            <select name="care_recipient_gender" required class="glass-input">
                                <option value="male" {{ old('care_recipient_gender') === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('care_recipient_gender') === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('care_recipient_gender') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label">Mobility Status <span style="color: #ef4444;">*</span></label>
                        <select name="mobility_status" required class="glass-input">
                            <option value="Independent">Independent (Can walk, needs companion)</option>
                            <option value="Assisted Walking">Assisted (Uses walker / stick)</option>
                            <option value="Wheelchair">Wheelchair Bound</option>
                            <option value="Bedridden">Bedridden (Requires total nursing care)</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label">Medical & Health Conditions</label>
                        <input type="text" name="health_conditions" value="{{ old('health_conditions') }}" placeholder="e.g. Hypertension, Diabetes, Stroke recovery, Alzheimer's" class="glass-input">
                    </div>
                </div>

                <!-- Section 2: Service & Schedule -->
                <div style="margin-bottom: 2rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                        2. Care Service & Shift Timing
                    </h3>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label">Care Service Required <span style="color: #ef4444;">*</span></label>
                        <select name="service_id" required class="glass-input">
                            @foreach ($caregiver->services as $svc)
                                <option value="{{ $svc->id }}" {{ old('service_id') == $svc->id ? 'selected' : '' }}>
                                    {{ $svc->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label class="form-label">Care Start Date <span style="color: #ef4444;">*</span></label>
                            <input type="date" name="start_date" value="{{ old('start_date', now()->addDay()->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}" required class="glass-input">
                            @error('start_date') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="form-label">Care End Date <span style="color: #ef4444;">*</span></label>
                            <input type="date" name="end_date" value="{{ old('end_date', now()->addDays(7)->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}" required class="glass-input">
                            @error('end_date') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label class="form-label">Shift Arrangement <span style="color: #ef4444;">*</span></label>
                            <select name="shift_type" required class="glass-input">
                                <option value="day">Day Shift (8-10 Hours)</option>
                                <option value="night">Night Shift (8-10 Hours)</option>
                                <option value="24_hours">24/7 Continuous Live-In</option>
                                <option value="customized">Customized Shift</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Daily Hours</label>
                            <input type="number" name="daily_hours" value="{{ old('daily_hours', 10) }}" min="1" max="24" class="glass-input">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Care Address -->
                <div style="margin-bottom: 2rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                        3. Service Address & Instructions
                    </h3>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label">Street Address & Flat # <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="care_address" value="{{ old('care_address', $client->present_address) }}" required placeholder="e.g. House 22, Road 4, Sector 3, Uttara, Dhaka" class="glass-input">
                        @error('care_address') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label class="form-label">Division</label>
                            <select name="division_id" id="reqDivSelect" class="glass-input">
                                <option value="">Select Division</option>
                                @foreach ($divisions as $d)
                                    <option value="{{ $d->id }}" {{ old('division_id', $client->division_id) == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">District</label>
                            <select name="district_id" id="reqDistSelect" class="glass-input">
                                <option value="">Select District</option>
                                @foreach ($districts as $dst)
                                    <option value="{{ $dst->id }}" data-division-id="{{ $dst->parent_id }}" {{ old('district_id', $client->district_id) == $dst->id ? 'selected' : '' }}>{{ $dst->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Area / Thana</label>
                            <select name="area_id" id="reqAreaSelect" class="glass-input">
                                <option value="">Select Area</option>
                                @foreach ($areas as $a)
                                    <option value="{{ $a->id }}" data-district-id="{{ $a->parent_id }}" {{ old('area_id', $client->area_id) == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label">Special Care Instructions / Medication Regimen</label>
                        <textarea name="special_instructions" rows="3" placeholder="Any specific requirements (e.g. insulin timing, pureed diet, gentle walking assistance)..." class="glass-input">{{ old('special_instructions') }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                    Submit Care Request to CareMate Admin →
                </button>
            </form>
        </div>

        <!-- Caregiver Summary Sidebar -->
        <div style="position: sticky; top: 90px;">
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
                <div style="font-size: 0.78rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); margin-bottom: 0.75rem;">
                    Selected Caregiver
                </div>

                <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1.25rem;">
                    <img src="{{ $caregiver->avatarUrl() }}" alt="{{ $caregiver->user->name }}" style="width: 58px; height: 58px; border-radius: 50%; object-fit: cover;">
                    <div>
                        <div style="font-weight: 800; font-size: 1.1rem; color: #0f172a;">{{ $caregiver->user->name }}</div>
                        <div style="font-size: 0.82rem; color: var(--text-muted);">
                            📍 {{ $caregiver->area?->name ?? $caregiver->city }}, {{ $caregiver->district?->name }}
                        </div>
                        <x-badge tone="success" style="margin-top: 0.25rem;">Verified Staff</x-badge>
                    </div>
                </div>

                <div style="border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 1rem; margin-bottom: 1.25rem;">
                    <div style="font-size: 0.85rem; color: var(--text-secondary); display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span>Standard Daily Rate:</span>
                        <strong style="color: #0f172a;">৳{{ number_format($caregiver->daily_rate) }}</strong>
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary); display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span>Experience:</span>
                        <strong style="color: #0f172a;">{{ $caregiver->years_experience }} Years</strong>
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary); display: flex; justify-content: space-between;">
                        <span>Satisfaction:</span>
                        <strong style="color: #0f172a;">★ {{ number_format($caregiver->rating_avg, 1) }}</strong>
                    </div>
                </div>

                <!-- Escrow Guarantee Box -->
                <div style="background: rgba(10, 57, 74, 0.06); border: 1px solid rgba(10, 57, 74, 0.18); border-radius: var(--radius-md); padding: 1rem; font-size: 0.82rem; color: var(--text-secondary); line-height: 1.55;">
                    <strong style="color: #0a394a; display: block; margin-bottom: 0.25rem;">🛡️ Next Steps</strong>
                    Once submitted, our Care Coordinator verifies schedules within 2 hours. You only pay after the booking is officially confirmed.
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const divSel = document.getElementById('reqDivSelect');
        const distSel = document.getElementById('reqDistSelect');
        const areaSel = document.getElementById('reqAreaSelect');

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
