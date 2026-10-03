<?php

namespace App\Http\Controllers;

use App\Enums\CaregiverStatus;
use App\Enums\DocumentType;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Caregiver;
use App\Models\CaregiverAvailability;
use App\Models\CaregiverVerificationLog;
use App\Models\Location;
use App\Models\Service;
use App\Models\User;
use App\Notifications\CareMateDatabaseNotification;
use App\Services\SecureDocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CaregiverRegistrationController extends Controller
{
    public function __construct(
        public SecureDocumentService $documentService
    ) {}

    public function show(Request $request): View|RedirectResponse
    {
        $step = (int) $request->query('step', 1);

        // If user is already logged in as caregiver, check current application
        $caregiver = null;
        if (Auth::check()) {
            if (! Auth::user()->isCaregiver()) {
                return redirect()->route(Auth::user()->role->dashboardRoute());
            }
            $caregiver = Auth::user()->caregiver;
            if ($caregiver && ! in_array($caregiver->status, [CaregiverStatus::Draft, CaregiverStatus::ChangesRequired], true)) {
                return redirect()->route('caregiver.dashboard')->with('info', 'Your application has already been submitted for verification.');
            }
            if ($caregiver && $step < $caregiver->application_step && ! $request->has('step')) {
                $step = $caregiver->application_step;
            }
        }

        $step = max(1, min(9, $step));

        $divisions = Location::where('type', 'division')->where('is_active', true)->orderBy('name')->get();
        $districts = Location::where('type', 'district')->where('is_active', true)->orderBy('name')->get();
        $areas = Location::where('type', 'area')->where('is_active', true)->orderBy('name')->get();
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();

        return view('auth.register-caregiver', compact('step', 'caregiver', 'divisions', 'districts', 'areas', 'services'));
    }

    public function saveStep(Request $request): RedirectResponse
    {
        $step = (int) $request->input('step', 1);

        return match ($step) {
            1 => $this->handleStep1Account($request),
            2 => $this->handleStep2Identity($request),
            3 => $this->handleStep3Personal($request),
            4 => $this->handleStep4Professional($request),
            5 => $this->handleStep5Education($request),
            6 => $this->handleStep6Certifications($request),
            7 => $this->handleStep7Preferences($request),
            8 => $this->handleStep8Profile($request),
            9 => $this->handleStep9Submit($request),
            default => redirect()->route('caregiver.register', ['step' => 1]),
        };
    }

    protected function handleStep1Account(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,'.($userId ?: 'NULL')],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone,'.($userId ?: 'NULL')],
            'gender' => ['required', 'in:female,male,other'],
            'date_of_birth' => ['required', 'date', 'before:-18 years'],
            'password' => $userId ? ['nullable', 'confirmed', 'min:6'] : ['required', 'confirmed', 'min:6'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        return DB::transaction(function () use ($validated, $request, $userId): RedirectResponse {
            if ($userId) {
                $user = User::findOrFail($userId);
                $userData = [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                ];
                if (! empty($validated['password'])) {
                    $userData['password'] = Hash::make($validated['password']);
                }
            } else {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'role' => UserRole::Caregiver,
                    'status' => UserStatus::Active,
                    'password' => Hash::make($validated['password']),
                    'email_verified_at' => now(),
                ]);
                Auth::login($user);
            }

            if ($request->hasFile('profile_photo')) {
                $photoPath = $request->file('profile_photo')->store('avatars', 'public');
                $user->update(['avatar_path' => $photoPath]);
            }

            $caregiver = Caregiver::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'gender' => $validated['gender'],
                    'date_of_birth' => $validated['date_of_birth'],
                    'status' => CaregiverStatus::Draft,
                    'application_step' => max(2, $user->caregiver?->application_step ?? 2),
                ]
            );

            return redirect()->route('caregiver.register', ['step' => 2])
                ->with('success', 'Step 1 completed: Account info saved.');
        });
    }

    protected function handleStep2Identity(Request $request): RedirectResponse
    {
        $this->ensureCaregiverAuth();
        $caregiver = Auth::user()->caregiver;

        $validated = $request->validate([
            'nid_number' => ['required', 'string', 'min:10', 'max:20'],
            'nid_front' => [$caregiver->documents()->where('type', DocumentType::NidFront)->exists() ? 'nullable' : 'required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
            'nid_back' => [$caregiver->documents()->where('type', DocumentType::NidBack)->exists() ? 'nullable' : 'required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
        ]);

        $caregiver->update([
            'nid_number' => $validated['nid_number'],
            'nid_hash' => hash('sha256', $validated['nid_number']),
            'application_step' => max(3, $caregiver->application_step),
        ]);

        if ($request->hasFile('nid_front')) {
            $this->documentService->storePrivateDocument($caregiver, $request->file('nid_front'), DocumentType::NidFront, 'National ID (Front)');
        }
        if ($request->hasFile('nid_back')) {
            $this->documentService->storePrivateDocument($caregiver, $request->file('nid_back'), DocumentType::NidBack, 'National ID (Back)');
        }

        return redirect()->route('caregiver.register', ['step' => 3])
            ->with('success', 'Step 2 completed: Identity credentials stored securely.');
    }

    protected function handleStep3Personal(Request $request): RedirectResponse
    {
        $this->ensureCaregiverAuth();
        $caregiver = Auth::user()->caregiver;

        $validated = $request->validate([
            'present_address' => ['required', 'string', 'max:255'],
            'permanent_address' => ['required', 'string', 'max:255'],
            'division_id' => ['required', 'exists:locations,id'],
            'district_id' => ['required', 'exists:locations,id'],
            'area_id' => ['nullable', 'exists:locations,id'],
            'city' => ['nullable', 'string', 'max:80'],
            'emergency_contact_name' => ['required', 'string', 'max:100'],
            'emergency_contact_phone' => ['required', 'string', 'max:20'],
            'emergency_contact_relationship' => ['required', 'string', 'max:60'],
        ]);

        $caregiver->update(array_merge($validated, [
            'application_step' => max(4, $caregiver->application_step),
        ]));

        return redirect()->route('caregiver.register', ['step' => 4])
            ->with('success', 'Step 3 completed: Personal address & emergency contact saved.');
    }

    protected function handleStep4Professional(Request $request): RedirectResponse
    {
        $this->ensureCaregiverAuth();
        $caregiver = Auth::user()->caregiver;

        $validated = $request->validate([
            'caregiver_type' => ['required', 'string', 'max:100'],
            'service_ids' => ['required', 'array', 'min:1'],
            'service_ids.*' => ['exists:services,id'],
            'primary_service_id' => ['required', 'exists:services,id'],
            'years_experience' => ['required', 'integer', 'min:0', 'max:50'],
            'previous_workplace' => ['nullable', 'string', 'max:150'],
            'previous_experience' => ['nullable', 'string', 'max:1000'],
            'skills' => ['nullable', 'string'],
            'specializations' => ['nullable', 'string'],
            'languages' => ['nullable', 'string'],
            'preferred_client_gender' => ['required', 'in:any,female,male'],
        ]);

        $skillsArray = array_values(array_filter(array_map('trim', explode(',', $validated['skills'] ?? ''))));
        $specArray = array_values(array_filter(array_map('trim', explode(',', $validated['specializations'] ?? ''))));
        $langArray = array_values(array_filter(array_map('trim', explode(',', $validated['languages'] ?? ''))));

        $caregiver->update([
            'caregiver_type' => $validated['caregiver_type'],
            'years_experience' => $validated['years_experience'],
            'previous_workplace' => $validated['previous_workplace'] ?? null,
            'previous_experience' => $validated['previous_experience'] ?? null,
            'skills' => $skillsArray,
            'specializations' => $specArray,
            'languages' => $langArray,
            'preferred_client_gender' => $validated['preferred_client_gender'],
            'application_step' => max(5, $caregiver->application_step),
        ]);

        // Sync services
        $syncData = [];
        foreach ($validated['service_ids'] as $svcId) {
            $syncData[$svcId] = ['is_primary' => ((int) $svcId === (int) $validated['primary_service_id'])];
        }
        $caregiver->services()->sync($syncData);

        return redirect()->route('caregiver.register', ['step' => 5])
            ->with('success', 'Step 4 completed: Professional profile and services saved.');
    }

    protected function handleStep5Education(Request $request): RedirectResponse
    {
        $this->ensureCaregiverAuth();
        $caregiver = Auth::user()->caregiver;

        $validated = $request->validate([
            'education_qualification' => ['required', 'string', 'max:150'],
            'education_institution' => ['required', 'string', 'max:150'],
            'education_passing_year' => ['nullable', 'integer', 'min:1970', 'max:'.date('Y')],
        ]);

        $caregiver->update(array_merge($validated, [
            'application_step' => max(6, $caregiver->application_step),
        ]));

        return redirect()->route('caregiver.register', ['step' => 6])
            ->with('success', 'Step 5 completed: Education details recorded.');
    }

    protected function handleStep6Certifications(Request $request): RedirectResponse
    {
        $this->ensureCaregiverAuth();
        $caregiver = Auth::user()->caregiver;

        $validated = $request->validate([
            'cert_name' => ['nullable', 'string', 'max:150'],
            'cert_institution' => ['nullable', 'string', 'max:150'],
            'cert_number' => ['nullable', 'string', 'max:80'],
            'cert_year' => ['nullable', 'integer', 'min:1970', 'max:'.date('Y')],
            'cert_file' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
        ]);

        if (! empty($validated['cert_name']) && ! empty($validated['cert_institution']) && $request->hasFile('cert_file')) {
            $this->documentService->storeCertificate($caregiver, $request->file('cert_file'), [
                'name' => $validated['cert_name'],
                'institution' => $validated['cert_institution'],
                'certificate_number' => $validated['cert_number'] ?? null,
                'issue_year' => $validated['cert_year'] ?? null,
                'is_public' => true,
            ]);
        }

        $caregiver->update([
            'application_step' => max(7, $caregiver->application_step),
        ]);

        return redirect()->route('caregiver.register', ['step' => 7])
            ->with('success', 'Step 6 completed: Certificates updated.');
    }

    protected function handleStep7Preferences(Request $request): RedirectResponse
    {
        $this->ensureCaregiverAuth();
        $caregiver = Auth::user()->caregiver;

        $validated = $request->validate([
            'hourly_rate' => ['nullable', 'numeric', 'min:50', 'max:5000'],
            'daily_rate' => ['required', 'numeric', 'min:500', 'max:50000'],
            'weekly_rate' => ['nullable', 'numeric', 'min:1000'],
            'monthly_rate' => ['nullable', 'numeric', 'min:5000'],
            'employment_type' => ['required', 'in:full_time,part_time,both'],
            'live_type' => ['required', 'in:live_in,live_out,both'],
            'preferred_hours' => ['nullable', 'string', 'max:100'],
            'available_days' => ['required', 'array', 'min:1'],
            'available_days.*' => ['integer', 'between:0,6'],
        ]);

        $caregiver->update([
            'hourly_rate' => $validated['hourly_rate'] ?? null,
            'daily_rate' => $validated['daily_rate'],
            'weekly_rate' => $validated['weekly_rate'] ?? null,
            'monthly_rate' => $validated['monthly_rate'] ?? null,
            'employment_type' => $validated['employment_type'],
            'live_type' => $validated['live_type'],
            'preferred_hours' => $validated['preferred_hours'] ?? null,
            'application_step' => max(8, $caregiver->application_step),
        ]);

        // Sync weekly availability
        $caregiver->availabilities()->delete();
        foreach ($validated['available_days'] as $day) {
            CaregiverAvailability::create([
                'caregiver_id' => $caregiver->id,
                'day_of_week' => (int) $day,
                'start_time' => '08:00',
                'end_time' => '18:00',
                'is_full_day' => false,
            ]);
        }

        return redirect()->route('caregiver.register', ['step' => 8])
            ->with('success', 'Step 7 completed: Pricing and schedule preferences saved.');
    }

    protected function handleStep8Profile(Request $request): RedirectResponse
    {
        $this->ensureCaregiverAuth();
        $caregiver = Auth::user()->caregiver;

        $validated = $request->validate([
            'about' => ['required', 'string', 'min:30', 'max:1000'],
            'bio' => ['required', 'string', 'min:30', 'max:2000'],
            'experience_description' => ['nullable', 'string', 'max:1500'],
            'special_skills' => ['nullable', 'string', 'max:1000'],
        ]);

        $caregiver->update(array_merge($validated, [
            'application_step' => max(9, $caregiver->application_step),
            'search_text' => implode(' ', array_filter([
                $caregiver->user->name,
                $caregiver->caregiver_type,
                $caregiver->city,
                $validated['about'],
                implode(' ', $caregiver->skills ?? []),
            ])),
        ]));

        return redirect()->route('caregiver.register', ['step' => 9])
            ->with('success', 'Step 8 completed: Public profile bio saved.');
    }

    protected function handleStep9Submit(Request $request): RedirectResponse
    {
        $this->ensureCaregiverAuth();
        $caregiver = Auth::user()->caregiver;

        // Allow any extra supporting document upload (nursing license, training certificate)
        if ($request->hasFile('supporting_document')) {
            $request->validate([
                'supporting_doc_type' => ['required', 'string'],
                'supporting_doc_title' => ['required', 'string', 'max:120'],
                'supporting_document' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
            ]);

            $docType = DocumentType::tryFrom($request->input('supporting_doc_type')) ?? DocumentType::Other;

            $this->documentService->storePrivateDocument(
                $caregiver,
                $request->file('supporting_document'),
                $docType,
                $request->input('supporting_doc_title')
            );
        }

        // Final submission transition
        $caregiver->update([
            'status' => CaregiverStatus::PendingVerification,
            'submitted_at' => now(),
            'application_step' => 9,
        ]);

        CaregiverVerificationLog::create([
            'caregiver_id' => $caregiver->id,
            'admin_id' => null,
            'action' => 'application_submitted',
            'from_status' => CaregiverStatus::Draft->value,
            'to_status' => CaregiverStatus::PendingVerification->value,
            'reason' => 'Caregiver submitted complete 9-step application for CareMate verification.',
        ]);

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new CareMateDatabaseNotification(
                title: 'New Caregiver Application: '.$caregiver->user->name,
                body: "{$caregiver->user->name} has submitted their verification documents for review.",
                actionUrl: route('admin.applications.show', $caregiver),
                type: 'new_caregiver_application'
            ));
        }

        return redirect()->route('caregiver.dashboard')->with('success', 'Congratulations! Your caregiver verification application has been submitted to CareMate Operations. Our compliance team will review your NID and credentials within 24-48 hours.');
    }

    protected function ensureCaregiverAuth(): void
    {
        abort_unless(Auth::check() && Auth::user()->isCaregiver() && Auth::user()->caregiver, 403, 'Unauthorized.');
    }
}
