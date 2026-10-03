<x-layouts.guest>
    <x-slot:title>Family Client Registration — CareMate BD</x-slot:title>
    <x-slot:description>Create a client account to find, book, and manage verified in-home care for your loved ones.</x-slot:description>

    <div class="container container-narrow" style="padding: 3rem 1.25rem 5rem 1.25rem;">
        <div style="max-width: 640px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <a href="{{ route('home') }}" style="display: inline-block; margin-bottom: 1rem;">
                    <img src="{{ asset('images/logo.png') }}" alt="CareMate BD" style="height: 48px; width: auto; object-fit: contain;">
                </a>
                <h1 style="font-size: 2.2rem; font-weight: 800; color: #092632; margin-top: 0.25rem;">
                    Register with CareMate BD
                </h1>
                <p style="color: var(--text-secondary); font-size: 0.95rem; margin-top: 0.35rem;">
                    Safe, admin-mediated in-home care with 100% privacy and escrow protection.
                </p>
            </div>

            <div class="glass-card" style="padding: 2.5rem; border-radius: var(--radius-xl); box-shadow: var(--glass-shadow-lg);">
                <form method="POST" action="{{ route('register.client.submit') }}">
                    @csrf

                    <!-- Account Info -->
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="font-size: 1.05rem; font-weight: 700; color: #0f172a; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                            1. Personal & Contact Details
                        </h4>

                        <div style="margin-bottom: 1rem;">
                            <label class="form-label">Full Name <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Farhana Yasmin" class="glass-input">
                            @error('name') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <label class="form-label">Email Address <span style="color: #ef4444;">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="farhana@example.com" class="glass-input">
                                @error('email') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="form-label">Phone Number <span style="color: #ef4444;">*</span></label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="+880 1712-000000" class="glass-input">
                                @error('phone') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div>
                                <label class="form-label">Password <span style="color: #ef4444;">*</span></label>
                                <input type="password" name="password" required placeholder="Min 6 characters" class="glass-input">
                                @error('password') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="form-label">Confirm Password <span style="color: #ef4444;">*</span></label>
                                <input type="password" name="password_confirmation" required placeholder="Retype password" class="glass-input">
                            </div>
                        </div>
                    </div>

                    <!-- Care Residence Address -->
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="font-size: 1.05rem; font-weight: 700; color: #0f172a; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                            2. Home Address (Where Care is Provided)
                        </h4>

                        <div style="margin-bottom: 1rem;">
                            <label class="form-label">Street Address & Flat / House # <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="present_address" value="{{ old('present_address') }}" required placeholder="e.g. House 14, Road 7, Block B" class="glass-input">
                            @error('present_address') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <label class="form-label">Division</label>
                                <select name="division_id" id="clientDivisionSelect" class="glass-input">
                                    <option value="">Select Division</option>
                                    @foreach ($divisions as $div)
                                        <option value="{{ $div->id }}" {{ old('division_id') == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">District</label>
                                <select name="district_id" id="clientDistrictSelect" class="glass-input">
                                    <option value="">Select District</option>
                                    @foreach ($districts as $dst)
                                        <option value="{{ $dst->id }}" data-division-id="{{ $dst->parent_id }}" {{ old('district_id') == $dst->id ? 'selected' : '' }}>{{ $dst->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Area / Thana</label>
                                <select name="area_id" id="clientAreaSelect" class="glass-input">
                                    <option value="">Select Area</option>
                                    @foreach ($areas as $a)
                                        <option value="{{ $a->id }}" data-district-id="{{ $a->parent_id }}" {{ old('area_id') == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div style="margin-bottom: 2rem;">
                        <h4 style="font-size: 1.05rem; font-weight: 700; color: #0f172a; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                            3. Emergency Family Contact (Encrypted & Private)
                        </h4>

                        <div style="display: grid; grid-template-columns: 1.2fr 1fr 1fr; gap: 1rem;">
                            <div>
                                <label class="form-label">Contact Name</label>
                                <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" placeholder="Relative Name" class="glass-input">
                            </div>
                            <div>
                                <label class="form-label">Emergency Phone</label>
                                <input type="tel" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" placeholder="+880 1..." class="glass-input">
                            </div>
                            <div>
                                <label class="form-label">Relationship</label>
                                <input type="text" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship') }}" placeholder="e.g. Son / Daughter" class="glass-input">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                        Create Family Account
                    </button>
                </form>
            </div>

            <div style="text-align: center; margin-top: 1.75rem; font-size: 0.9rem; color: var(--text-secondary);">
                Already have an account? <a href="{{ route('login') }}" style="font-weight: 700;">Log in here</a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const divSel = document.getElementById('clientDivisionSelect');
        const distSel = document.getElementById('clientDistrictSelect');
        const areaSel = document.getElementById('clientAreaSelect');

        if (divSel && distSel && areaSel) {
            const distOptions = Array.from(distSel.querySelectorAll('option')).filter(opt => opt.value !== '');
            const areaOptions = Array.from(areaSel.querySelectorAll('option')).filter(opt => opt.value !== '');

            const savedDist = "{{ old('district_id') }}";
            const savedArea = "{{ old('area_id') }}";

            function filterDistricts(reset) {
                const divId = divSel.value;
                const curDist = reset ? '' : (distSel.value || savedDist);

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
                const curArea = reset ? '' : (areaSel.value || savedArea);

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

            // Initialize on load
            if (divSel.value) {
                filterDistricts(false);
            }
        }
    });
    </script>
    @endpush
</x-layouts.guest>
