<x-layouts.guest>
    <x-slot:title>Caregiver Registration Academy — Step {{ $step }} of 9 | CareMate BD</x-slot:title>
    <x-slot:description>Join CareMate BD verified caregiver network. 9-step accredited onboarding with dignity and guaranteed payouts.</x-slot:description>

    <div class="container" style="padding: 2.5rem 1.25rem 5rem 1.25rem;">
        <div style="max-width: 780px; margin: 0 auto;">
            <!-- Header -->
            <div style="text-align: center; margin-bottom: 2rem;">
                <a href="{{ route('home') }}" style="display: inline-block; margin-bottom: 1rem;">
                    <img src="{{ asset('images/logo.png') }}" alt="CareMate BD" style="height: 48px; width: auto; object-fit: contain;">
                </a>
                <h1 style="font-size: 2.2rem; font-weight: 800; color: #092632; margin-top: 0.25rem;">
                    Caregiver Verification Academy
                </h1>
                <p style="color: var(--text-secondary); font-size: 0.95rem; margin-top: 0.35rem;">
                    Complete our accredited 9-step verification to receive continuous family care assignments.
                </p>
            </div>

            <!-- Stepper Progress Bar -->
            <div class="glass-card" style="padding: 1.25rem; border-radius: var(--radius-lg); margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; font-size: 0.85rem; font-weight: 700; color: #0f172a;">
                    <span>Step {{ $step }} of 9: 
                        @switch($step)
                            @case(1) Account & Personal Info @break
                            @case(2) Government NID Verification @break
                            @case(3) Residence & Emergency Contact @break
                            @case(4) Professional Specialty & Services @break
                            @case(5) Academic Education @break
                            @case(6) Clinical Certifications @break
                            @case(7) Rates & Schedule Preferences @break
                            @case(8) Public Bio & Experience Story @break
                            @case(9) Final Review & Verification Submit @break
                        @endswitch
                    </span>
                    <span style="color: var(--brand-primary);">{{ round(($step / 9) * 100) }}% Completed</span>
                </div>
                <div style="width: 100%; height: 8px; background: rgba(226, 232, 240, 0.8); border-radius: 999px; overflow: hidden;">
                    <div style="width: {{ ($step / 9) * 100 }}%; height: 100%; background: linear-gradient(90deg, #0ea5e9 0%, #10b981 100%); transition: width 0.3s ease;"></div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="glass-card" style="padding: 2.5rem; border-radius: var(--radius-xl); box-shadow: var(--glass-shadow-lg);">
                <form method="POST" action="{{ route('caregiver.register.step') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="step" value="{{ $step }}">

                    @if ($errors->any())
                        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: var(--radius-md); padding: 1rem 1.25rem; margin-bottom: 1.5rem; color: #b91c1c;">
                            <strong style="display: block; font-weight: 700; margin-bottom: 0.35rem;">⚠️ Please review the following errors:</strong>
                            <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.88rem; line-height: 1.5;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($step === 1)
                        <!-- STEP 1: ACCOUNT -->
                        <h3 style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem;">Step 1: Basic Account & Personal Info</h3>
                        
                        <div style="margin-bottom: 1.25rem;">
                            <label class="form-label">Full Name (as per National ID) <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $caregiver?->user?->name) }}" required placeholder="e.g. Nusrat Jahan" class="glass-input">
                            @error('name') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                            <div>
                                <label class="form-label">Email Address <span style="color: #ef4444;">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $caregiver?->user?->email) }}" required placeholder="nusrat@example.com" class="glass-input">
                                @error('email') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="form-label">Mobile Phone Number <span style="color: #ef4444;">*</span></label>
                                <input type="tel" name="phone" value="{{ old('phone', $caregiver?->user?->phone) }}" required placeholder="+880 1712-000000" class="glass-input">
                                @error('phone') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                            <div>
                                <label class="form-label">Gender <span style="color: #ef4444;">*</span></label>
                                <select name="gender" required class="glass-input">
                                    <option value="">Select Gender</option>
                                    <option value="female" {{ old('gender', $caregiver?->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="male" {{ old('gender', $caregiver?->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="other" {{ old('gender', $caregiver?->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="form-label">Date of Birth (Must be 18+) <span style="color: #ef4444;">*</span></label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $caregiver?->date_of_birth?->format('Y-m-d')) }}" required class="glass-input">
                                @error('date_of_birth') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                            <div>
                                <label class="form-label">Password {{ Auth::check() ? '(Leave blank to keep unchanged)' : '*' }}</label>
                                <input type="password" name="password" {{ Auth::check() ? '' : 'required' }} placeholder="Min 6 characters" class="glass-input">
                                @error('password') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" {{ Auth::check() ? '' : 'required' }} placeholder="Repeat password" class="glass-input">
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label">Professional Profile Photo (Clear headshot)</label>
                            <input type="file" name="profile_photo" accept="image/*" class="glass-input">
                            <span style="font-size: 0.78rem; color: var(--text-muted);">Please upload a friendly, high-resolution front-facing photo in professional attire.</span>
                            @error('profile_photo') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                    @elseif ($step === 2)
                        <!-- STEP 2: NID -->
                        <h3 style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">Step 2: National ID Verification</h3>
                        <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 1.5rem;">
                            Your National ID number and scans are stored on our strictly encrypted private storage. They will <strong>never</strong> be displayed publicly.
                        </p>

                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label">Government NID Number (Smart Card or 17-digit) <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="nid_number" value="{{ old('nid_number', $caregiver?->nid_number) }}" required placeholder="e.g. 199226955000000" class="glass-input">
                            @error('nid_number') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div style="background: rgba(255, 255, 255, 0.6); border: 2px dashed rgba(203, 213, 225, 0.9); padding: 1.5rem; border-radius: var(--radius-md); text-align: center;">
                                <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🪪</div>
                                <label class="form-label">NID Front Scan / Photo <span style="color: #ef4444;">*</span></label>
                                <input type="file" name="nid_front" accept="image/*,.pdf" class="glass-input" style="font-size: 0.8rem;">
                                @error('nid_front') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div style="background: rgba(255, 255, 255, 0.6); border: 2px dashed rgba(203, 213, 225, 0.9); padding: 1.5rem; border-radius: var(--radius-md); text-align: center;">
                                <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🪪</div>
                                <label class="form-label">NID Back Scan / Photo <span style="color: #ef4444;">*</span></label>
                                <input type="file" name="nid_back" accept="image/*,.pdf" class="glass-input" style="font-size: 0.8rem;">
                                @error('nid_back') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                    @elseif ($step === 3)
                        <!-- STEP 3: ADDRESS & EMERGENCY -->
                        <h3 style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem;">Step 3: Residence & Emergency Contact</h3>

                        <div style="margin-bottom: 1.25rem;">
                            <label class="form-label">Present Address (Where you currently live in Bangladesh) <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="present_address" value="{{ old('present_address', $caregiver?->present_address) }}" required placeholder="House, Road, Area..." class="glass-input">
                            @error('present_address') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 1.25rem;">
                            <label class="form-label">Permanent Address (Village/Thana as per NID) <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="permanent_address" value="{{ old('permanent_address', $caregiver?->permanent_address) }}" required placeholder="Permanent address..." class="glass-input">
                            @error('permanent_address') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                            <div>
                                <label class="form-label">Division <span style="color: #ef4444;">*</span></label>
                                <select name="division_id" id="divisionSelect" required class="glass-input">
                                    <option value="">Select Division</option>
                                    @foreach ($divisions as $div)
                                        <option value="{{ $div->id }}" {{ old('division_id', $caregiver?->division_id) == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                                    @endforeach
                                </select>
                                @error('division_id') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="form-label">District <span style="color: #ef4444;">*</span></label>
                                <select name="district_id" id="districtSelect" required class="glass-input">
                                    <option value="">Select District</option>
                                    @foreach ($districts as $dst)
                                        <option value="{{ $dst->id }}" data-division-id="{{ $dst->parent_id }}" {{ old('district_id', $caregiver?->district_id) == $dst->id ? 'selected' : '' }}>{{ $dst->name }}</option>
                                    @endforeach
                                </select>
                                @error('district_id') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="form-label">Primary City / Thana</label>
                                <input type="text" name="city" value="{{ old('city', $caregiver?->city ?? 'Dhaka') }}" placeholder="e.g. Dhaka, Bhola Sadar..." class="glass-input">
                                @error('city') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div style="border-top: 1px solid rgba(226, 232, 240, 0.8); padding-top: 1.25rem; margin-bottom: 1.5rem;">
                            <h4 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem;">Family Emergency Contact</h4>
                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                                <div>
                                    <label class="form-label">Name <span style="color: #ef4444;">*</span></label>
                                    <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $caregiver?->emergency_contact_name) }}" required placeholder="Contact name" class="glass-input">
                                </div>
                                <div>
                                    <label class="form-label">Phone <span style="color: #ef4444;">*</span></label>
                                    <input type="tel" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $caregiver?->emergency_contact_phone) }}" required placeholder="+880 1..." class="glass-input">
                                </div>
                                <div>
                                    <label class="form-label">Relationship <span style="color: #ef4444;">*</span></label>
                                    <input type="text" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $caregiver?->emergency_contact_relationship) }}" required placeholder="e.g. Brother, Mother" class="glass-input">
                                </div>
                            </div>
                        </div>

                    @elseif ($step === 4)
                        <!-- STEP 4: PROFESSIONAL -->
                        <h3 style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem;">Step 4: Professional Specialties & Experience</h3>

                        <div style="margin-bottom: 1.25rem;">
                            <label class="form-label">Professional Title / Caregiver Type <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="caregiver_type" value="{{ old('caregiver_type', $caregiver?->caregiver_type) }}" required placeholder="e.g. Certified Senior Care Specialist & Registered Nurse" class="glass-input">
                            @error('caregiver_type') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 1.25rem;">
                            <label class="form-label">Care Services You Provide (Select all that apply) <span style="color: #ef4444;">*</span></label>
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                                @php $selectedServices = $caregiver ? $caregiver->services->pluck('id')->toArray() : []; @endphp
                                @foreach ($services as $srv)
                                    <label style="display: flex; align-items: center; gap: 0.6rem; padding: 0.75rem; background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(226, 232, 240, 0.9); border-radius: var(--radius-md); cursor: pointer;">
                                        <input type="checkbox" name="service_ids[]" value="{{ $srv->id }}" {{ in_array($srv->id, old('service_ids', $selectedServices)) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: var(--brand-primary);">
                                        <span style="font-weight: 600; font-size: 0.9rem;">{{ $srv->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('service_ids') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 1.25rem;">
                            <label class="form-label">Primary Service Specialty <span style="color: #ef4444;">*</span></label>
                            <select name="primary_service_id" required class="glass-input">
                                <option value="">Select your main strength</option>
                                @foreach ($services as $srv)
                                    <option value="{{ $srv->id }}" {{ old('primary_service_id', $caregiver?->primaryService()?->id) == $srv->id ? 'selected' : '' }}>
                                        {{ $srv->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                            <div>
                                <label class="form-label">Years of Experience <span style="color: #ef4444;">*</span></label>
                                <input type="number" name="years_experience" value="{{ old('years_experience', $caregiver?->years_experience ?? 2) }}" min="0" max="40" required class="glass-input">
                            </div>
                            <div>
                                <label class="form-label">Preferred Client Gender <span style="color: #ef4444;">*</span></label>
                                <select name="preferred_client_gender" required class="glass-input">
                                    <option value="any" {{ old('preferred_client_gender', $caregiver?->preferred_client_gender) === 'any' ? 'selected' : '' }}>Any Gender</option>
                                    <option value="female" {{ old('preferred_client_gender', $caregiver?->preferred_client_gender) === 'female' ? 'selected' : '' }}>Female Patients Only</option>
                                    <option value="male" {{ old('preferred_client_gender', $caregiver?->preferred_client_gender) === 'male' ? 'selected' : '' }}>Male Patients Only</option>
                                </select>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                            <div>
                                <label class="form-label">Previous Workplace / Hospital / Agency (Optional)</label>
                                <input type="text" name="previous_workplace" value="{{ old('previous_workplace', $caregiver?->previous_workplace) }}" placeholder="e.g. Square Hospital, Dhaka Medical College, or In-Home Care Agency" class="glass-input">
                                @error('previous_workplace') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="form-label">Summary of Prior Experience (Optional)</label>
                                <textarea name="previous_experience" rows="2" placeholder="Briefly describe past caregiving duties and conditions you have managed..." class="glass-input">{{ old('previous_experience', $caregiver?->previous_experience) }}</textarea>
                                @error('previous_experience') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div style="margin-bottom: 1.25rem;">
                            <label class="form-label">Skills (Comma separated)</label>
                            <input type="text" name="skills" value="{{ old('skills', is_array($caregiver?->skills) ? implode(', ', $caregiver->skills) : '') }}" placeholder="e.g. Blood pressure monitoring, Diabetes care, Catheter management, CPR" class="glass-input">
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label">Languages Spoken (Comma separated)</label>
                            <input type="text" name="languages" value="{{ old('languages', is_array($caregiver?->languages) ? implode(', ', $caregiver->languages) : 'Bengali, English') }}" placeholder="e.g. Bengali, English, Sylheti, Chittagonian" class="glass-input">
                        </div>

                    @elseif ($step === 5)
                        <!-- STEP 5: EDUCATION -->
                        <h3 style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem;">Step 5: Academic Education</h3>

                        <div style="margin-bottom: 1.25rem;">
                            <label class="form-label">Highest Educational Qualification <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="education_qualification" value="{{ old('education_qualification', $caregiver?->education_qualification) }}" required placeholder="e.g. Diploma in Nursing / B.Sc in Nursing / HSC" class="glass-input">
                        </div>

                        <div style="margin-bottom: 1.25rem;">
                            <label class="form-label">Institution / College / University <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="education_institution" value="{{ old('education_institution', $caregiver?->education_institution) }}" required placeholder="e.g. Dhaka Nursing College" class="glass-input">
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label">Passing Year</label>
                            <input type="number" name="education_passing_year" value="{{ old('education_passing_year', $caregiver?->education_passing_year) }}" min="1980" max="{{ date('Y') }}" placeholder="e.g. 2020" class="glass-input">
                        </div>

                    @elseif ($step === 6)
                        <!-- STEP 6: CERTIFICATES -->
                        <h3 style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">Step 6: Clinical Training & Certificates</h3>
                        <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 1.5rem;">
                            Add nursing diplomas, caregiving certifications, or Red Crescent first-aid badges. These boost your hourly rate and hiring priority.
                        </p>

                        <div style="margin-bottom: 1.25rem;">
                            <label class="form-label">Certificate Title</label>
                            <input type="text" name="cert_name" placeholder="e.g. Certified Geriatric Care Nurse" class="glass-input">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                            <div>
                                <label class="form-label">Issuing Authority / Body</label>
                                <input type="text" name="cert_institution" placeholder="e.g. Bangladesh Nursing Council" class="glass-input">
                            </div>
                            <div>
                                <label class="form-label">Registration / Certificate Number</label>
                                <input type="text" name="cert_number" placeholder="Optional" class="glass-input">
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label">Upload Certificate Document (PDF or Photo)</label>
                            <input type="file" name="cert_file" accept="image/*,.pdf" class="glass-input">
                        </div>

                    @elseif ($step === 7)
                        <!-- STEP 7: RATES & SCHEDULE -->
                        <h3 style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem;">Step 7: Service Rates & Availability</h3>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                            <div>
                                <label class="form-label">Standard Daily Rate (৳ BDT) <span style="color: #ef4444;">*</span></label>
                                <input type="number" name="daily_rate" value="{{ old('daily_rate', $caregiver?->daily_rate ?? 1500) }}" min="500" max="30000" required class="glass-input">
                                <span style="font-size: 0.75rem; color: var(--text-muted);">Standard 8-10 hour shift rate</span>
                            </div>
                            <div>
                                <label class="form-label">Monthly Rate (৳ BDT)</label>
                                <input type="number" name="monthly_rate" value="{{ old('monthly_rate', $caregiver?->monthly_rate ?? 32000) }}" min="10000" class="glass-input">
                                <span style="font-size: 0.75rem; color: var(--text-muted);">For continuous monthly family arrangements</span>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                            <div>
                                <label class="form-label">Employment Type <span style="color: #ef4444;">*</span></label>
                                <select name="employment_type" required class="glass-input">
                                    <option value="full_time" {{ old('employment_type', $caregiver?->employment_type) === 'full_time' ? 'selected' : '' }}>Full-Time</option>
                                    <option value="part_time" {{ old('employment_type', $caregiver?->employment_type) === 'part_time' ? 'selected' : '' }}>Part-Time</option>
                                    <option value="both" {{ old('employment_type', $caregiver?->employment_type) === 'both' ? 'selected' : '' }}>Both / Flexible</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Living Arrangement <span style="color: #ef4444;">*</span></label>
                                <select name="live_type" required class="glass-input">
                                    <option value="live_out" {{ old('live_type', $caregiver?->live_type) === 'live_out' ? 'selected' : '' }}>Live-Out (Day / Night Shifts)</option>
                                    <option value="live_in" {{ old('live_type', $caregiver?->live_type) === 'live_in' ? 'selected' : '' }}>Live-In (24/7 at Client Home)</option>
                                    <option value="both" {{ old('live_type', $caregiver?->live_type) === 'both' ? 'selected' : '' }}>Both Arrangements Accepted</option>
                                </select>
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label">Available Days in a Week <span style="color: #ef4444;">*</span></label>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                @php $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']; @endphp
                                @foreach ($days as $idx => $d)
                                    <label style="display: flex; align-items: center; gap: 0.35rem; padding: 0.5rem 0.85rem; background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(203, 213, 225, 0.8); border-radius: var(--radius-sm); font-size: 0.85rem; cursor: pointer;">
                                        <input type="checkbox" name="available_days[]" value="{{ $idx }}" checked style="accent-color: var(--brand-primary);">
                                        <span>{{ $d }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    @elseif ($step === 8)
                        <!-- STEP 8: BIO & EXPERIENCE -->
                        <h3 style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem;">Step 8: Public Bio & Experience Story</h3>

                        <div style="margin-bottom: 1.25rem;">
                            <label class="form-label">Short Summary (Shown on marketplace cards) <span style="color: #ef4444;">*</span></label>
                            <textarea name="about" rows="3" required placeholder="Describe your dedication and care philosophy in 2-3 sentences..." class="glass-input">{{ old('about', $caregiver?->about) }}</textarea>
                            @error('about') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label">Detailed Professional Bio <span style="color: #ef4444;">*</span></label>
                            <textarea name="bio" rows="6" required placeholder="Share your clinical background, hands-on experience, compassionate mindset, and how you assist families..." class="glass-input">{{ old('bio', $caregiver?->bio) }}</textarea>
                            @error('bio') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                    @elseif ($step === 9)
                        <!-- STEP 9: FINAL SUBMIT -->
                        <h3 style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">Step 9: Final Review & Submission</h3>
                        <p style="font-size: 0.95rem; color: var(--text-secondary); margin-bottom: 1.5rem;">
                            Congratulations on completing your application! Please attach any additional police clearances or reference letters before submitting to the CareMate Verification Desk.
                        </p>

                        <div style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.25); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 2rem;">
                            <h4 style="font-weight: 700; color: #065f46; margin-bottom: 0.5rem;">🛡️ CareMate Code of Conduct & Honor Pledge</h4>
                            <p style="font-size: 0.88rem; color: var(--text-secondary); line-height: 1.6;">
                                By submitting this application, you pledge to maintain patient privacy, adhere to scheduled shifts without unannounced absence, and report any medical emergencies immediately to CareMate Support.
                            </p>
                        </div>

                        <!-- Optional Supporting Document Upload -->
                        <div style="background: rgba(248, 250, 252, 0.8); border: 1px dashed rgba(203, 213, 225, 0.9); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 1.5rem;">
                            <h4 style="font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">Optional Supporting Document (Police Clearance, Recommendation, Certificate)</h4>
                            <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1rem;">
                                If you have a police clearance certificate or recommendation letter, attaching it will speed up verification.
                            </p>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                                <div>
                                    <label class="form-label" style="font-size: 0.82rem;">Document Type</label>
                                    <select name="supporting_doc_type" class="glass-input" style="font-size: 0.85rem;">
                                        <option value="police_verification">Police Verification Clearance</option>
                                        <option value="training_certificate">Specialized Training Certificate</option>
                                        <option value="experience_letter">Experience / Recommendation Letter</option>
                                        <option value="other">Other Supporting Credential</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label" style="font-size: 0.82rem;">Document Title</label>
                                    <input type="text" name="supporting_doc_title" placeholder="e.g. DMP Police Clearance Certificate" class="glass-input" style="font-size: 0.85rem;">
                                </div>
                            </div>
                            <div>
                                <label class="form-label" style="font-size: 0.82rem;">Upload File (PDF, PNG, JPG max 5MB)</label>
                                <input type="file" name="supporting_document" accept=".pdf,.png,.jpg,.jpeg" class="glass-input" style="font-size: 0.85rem;">
                            </div>
                        </div>
                    @endif

                    <!-- Navigation Action Buttons -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem; pt: 1rem; border-top: 1px solid rgba(226, 232, 240, 0.8);">
                        @if ($step > 1)
                            <a href="{{ route('caregiver.register', ['step' => $step - 1]) }}" class="btn btn-secondary">
                                ← Previous Step
                            </a>
                        @else
                            <div></div>
                        @endif

                        <button type="submit" class="btn btn-primary btn-lg">
                            @if ($step === 9)
                                Submit Application for Verification →
                            @else
                                Save & Proceed to Step {{ $step + 1 }} →
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const divisionSelect = document.getElementById('divisionSelect');
        const districtSelect = document.getElementById('districtSelect');

        if (divisionSelect && districtSelect) {
            // Collect all district options with their division mapping
            const options = Array.from(districtSelect.querySelectorAll('option')).filter(function (opt) {
                return opt.value !== '';
            });

            const savedDistrictId = "{{ old('district_id', $caregiver?->district_id) }}";

            function filterDistricts(resetSelected) {
                const selectedDivisionId = divisionSelect.value;
                const currentSelected = resetSelected ? '' : (districtSelect.value || savedDistrictId);

                districtSelect.innerHTML = '';

                const defaultOpt = document.createElement('option');
                defaultOpt.value = '';
                defaultOpt.textContent = selectedDivisionId ? 'Select District' : 'Select Division First';
                districtSelect.appendChild(defaultOpt);

                if (!selectedDivisionId) {
                    return;
                }

                let matchFound = false;
                options.forEach(function (opt) {
                    if (String(opt.getAttribute('data-division-id')) === String(selectedDivisionId)) {
                        const cloned = opt.cloneNode(true);
                        if (String(cloned.value) === String(currentSelected)) {
                            cloned.selected = true;
                            matchFound = true;
                        }
                        districtSelect.appendChild(cloned);
                    }
                });

                if (!matchFound && !resetSelected && currentSelected) {
                    districtSelect.value = '';
                }
            }

            divisionSelect.addEventListener('change', function () {
                filterDistricts(true);
            });

            // Run initial filter on load if division is chosen
            if (divisionSelect.value) {
                filterDistricts(false);
            }
        }
    });
    </script>
    @endpush
</x-layouts.guest>
