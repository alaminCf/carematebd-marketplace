<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CaregiverStatus;
use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Models\Caregiver;
use App\Models\CaregiverVerificationLog;
use App\Models\Location;
use App\Models\Service;
use App\Models\User;
use App\Notifications\CareMateDatabaseNotification;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminCaregiverController extends Controller
{
    public function __construct(
        public AuditLogService $auditLogService
    ) {}

    public function applications(Request $request): View
    {
        $status = $request->input('status', 'all');

        $query = Caregiver::with(['user', 'services', 'district', 'area']);

        if ($status === 'all') {
            $query->whereIn('status', [CaregiverStatus::PendingVerification, CaregiverStatus::UnderReview, CaregiverStatus::ChangesRequired]);
        } else {
            $query->where('status', $status);
        }

        $applications = $query->latest('submitted_at')->paginate(12)->withQueryString();

        return view('admin.caregivers.applications', compact('applications', 'status'));
    }

    public function showApplication(Caregiver $caregiver): View
    {
        $caregiver->load([
            'user',
            'services',
            'division',
            'district',
            'area',
            'documents',
            'certificates',
            'experiences',
            'availabilities',
            'verificationLogs.admin',
        ]);

        return view('admin.caregivers.show-application', compact('caregiver'));
    }

    public function approveAndPublish(Caregiver $caregiver): RedirectResponse
    {
        $admin = Auth::user();
        $prev = $caregiver->status->value;

        DB::transaction(function () use ($caregiver, $admin, $prev): void {
            $caregiver->update([
                'status' => CaregiverStatus::Published,
                'status_reason' => null,
                'approved_at' => now(),
                'published_at' => now(),
                'identity_verified_at' => now(),
                'reviewed_by' => $admin->id,
            ]);

            // Mark pending documents as verified
            $caregiver->documents()->where('status', DocumentStatus::Pending)->update([
                'status' => DocumentStatus::Verified,
                'verified_by' => $admin->id,
                'verified_at' => now(),
            ]);

            CaregiverVerificationLog::create([
                'caregiver_id' => $caregiver->id,
                'admin_id' => $admin->id,
                'action' => 'approved_and_published',
                'from_status' => $prev,
                'to_status' => CaregiverStatus::Published->value,
                'reason' => 'Admin verified NID, qualifications, and published profile to CareMate marketplace.',
            ]);

            $this->auditLogService->log(
                $admin,
                'caregiver.approved_and_published',
                $caregiver,
                ['status' => $prev],
                ['status' => CaregiverStatus::Published->value],
                'Approved caregiver application and published profile'
            );

            $caregiver->user->notify(new CareMateDatabaseNotification(
                title: 'Congratulations! Your Profile is Approved & Published',
                body: 'Your identity and credentials have been verified by CareMate BD. Your profile is now live on our marketplace for families to request care.',
                actionUrl: route('caregiver.dashboard'),
                type: 'application_approved'
            ));
        });

        return redirect()->route('admin.applications.index')
            ->with('success', "Caregiver {$caregiver->user->name} has been approved and published to the marketplace.");
    }

    public function requestChanges(Request $request, Caregiver $caregiver): RedirectResponse
    {
        $admin = Auth::user();
        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $prev = $caregiver->status->value;

        DB::transaction(function () use ($caregiver, $admin, $validated, $prev): void {
            $caregiver->update([
                'status' => CaregiverStatus::ChangesRequired,
                'status_reason' => $validated['reason'],
                'reviewed_by' => $admin->id,
            ]);

            CaregiverVerificationLog::create([
                'caregiver_id' => $caregiver->id,
                'admin_id' => $admin->id,
                'action' => 'changes_requested',
                'from_status' => $prev,
                'to_status' => CaregiverStatus::ChangesRequired->value,
                'reason' => $validated['reason'],
            ]);

            $this->auditLogService->log(
                $admin,
                'caregiver.changes_requested',
                $caregiver,
                ['status' => $prev],
                ['status' => CaregiverStatus::ChangesRequired->value],
                $validated['reason']
            );

            $caregiver->user->notify(new CareMateDatabaseNotification(
                title: 'Action Required: Verification Changes Requested',
                body: "CareMate Admin requested changes on your application: {$validated['reason']}. Please update your application.",
                actionUrl: route('caregiver.register'),
                type: 'changes_requested'
            ));
        });

        return back()->with('info', 'Changes request notification sent to caregiver.');
    }

    public function reject(Request $request, Caregiver $caregiver): RedirectResponse
    {
        $admin = Auth::user();
        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $prev = $caregiver->status->value;

        DB::transaction(function () use ($caregiver, $admin, $validated, $prev): void {
            $caregiver->update([
                'status' => CaregiverStatus::Rejected,
                'status_reason' => $validated['reason'],
                'reviewed_by' => $admin->id,
            ]);

            CaregiverVerificationLog::create([
                'caregiver_id' => $caregiver->id,
                'admin_id' => $admin->id,
                'action' => 'rejected',
                'from_status' => $prev,
                'to_status' => CaregiverStatus::Rejected->value,
                'reason' => $validated['reason'],
            ]);

            $this->auditLogService->log(
                $admin,
                'caregiver.rejected',
                $caregiver,
                ['status' => $prev],
                ['status' => CaregiverStatus::Rejected->value],
                $validated['reason']
            );

            $caregiver->user->notify(new CareMateDatabaseNotification(
                title: 'Application Status Update',
                body: "Your CareMate caregiver application could not be approved at this time: {$validated['reason']}",
                actionUrl: route('caregiver.dashboard'),
                type: 'application_rejected'
            ));
        });

        return redirect()->route('admin.applications.index')
            ->with('warning', 'Caregiver application marked as rejected.');
    }

    public function suspend(Request $request, Caregiver $caregiver): RedirectResponse
    {
        $admin = Auth::user();
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $prev = $caregiver->status->value;

        DB::transaction(function () use ($caregiver, $admin, $validated, $prev): void {
            $caregiver->update([
                'status' => CaregiverStatus::Suspended,
                'status_reason' => $validated['reason'],
            ]);

            $caregiver->user->update([
                'suspended_at' => now(),
                'suspension_reason' => $validated['reason'],
            ]);

            CaregiverVerificationLog::create([
                'caregiver_id' => $caregiver->id,
                'admin_id' => $admin->id,
                'action' => 'suspended',
                'from_status' => $prev,
                'to_status' => CaregiverStatus::Suspended->value,
                'reason' => $validated['reason'],
            ]);

            $this->auditLogService->log(
                $admin,
                'caregiver.suspended',
                $caregiver,
                ['status' => $prev],
                ['status' => CaregiverStatus::Suspended->value],
                $validated['reason']
            );
        });

        return back()->with('warning', 'Caregiver account suspended.');
    }

    public function reactivate(Caregiver $caregiver): RedirectResponse
    {
        $admin = Auth::user();
        $prev = $caregiver->status->value;

        DB::transaction(function () use ($caregiver, $admin, $prev): void {
            $caregiver->update([
                'status' => CaregiverStatus::Published,
                'status_reason' => null,
            ]);

            $caregiver->user->update([
                'suspended_at' => null,
                'suspension_reason' => null,
            ]);

            CaregiverVerificationLog::create([
                'caregiver_id' => $caregiver->id,
                'admin_id' => $admin->id,
                'action' => 'reactivated',
                'from_status' => $prev,
                'to_status' => CaregiverStatus::Published->value,
                'reason' => 'Admin reactivated caregiver profile.',
            ]);

            $this->auditLogService->log(
                $admin,
                'caregiver.reactivated',
                $caregiver,
                ['status' => $prev],
                ['status' => CaregiverStatus::Published->value],
                'Reactivated caregiver profile'
            );
        });

        return back()->with('success', 'Caregiver reactivated and published.');
    }

    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Caregiver::with(['user', 'services', 'district', 'area']);

        if ($search) {
            $query->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                ->orWhere('city', 'like', "%{$search}%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        $caregivers = $query->orderByRaw('CASE WHEN sort_order > 0 THEN sort_order ELSE 999999 END ASC')
            ->orderByDesc('is_featured')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.caregivers.index', compact('caregivers', 'search', 'status'));
    }

    public function show(Caregiver $caregiver): View
    {
        $caregiver->load([
            'user',
            'services',
            'division',
            'district',
            'area',
            'documents',
            'certificates',
            'experiences',
            'availabilities',
            'bookings.client.user',
            'reviews.client.user',
            'verificationLogs.admin',
        ]);

        return view('admin.caregivers.show', compact('caregiver'));
    }

    public function edit(Caregiver $caregiver): View
    {
        $caregiver->load([
            'user',
            'services',
            'division',
            'district',
            'area',
        ]);

        $services = Service::where('is_active', true)->orderBy('name')->get();
        $divisions = Location::where('type', 'division')->where('is_active', true)->orderBy('name')->get();
        $districts = Location::where('type', 'district')->where('is_active', true)->orderBy('name')->get();
        $areas = Location::where('type', 'area')->where('is_active', true)->orderBy('name')->get();

        return view('admin.caregivers.edit', compact('caregiver', 'services', 'divisions', 'districts', 'areas'));
    }

    public function update(Request $request, Caregiver $caregiver): RedirectResponse
    {
        $admin = Auth::user();

        $validated = $request->validate([
            // User credentials
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,'.$caregiver->user_id],
            'phone' => ['required', 'string', 'max:25', 'unique:users,phone,'.$caregiver->user_id],
            'password' => ['nullable', 'string', 'min:8'],
            'profile_photo' => ['nullable', 'image', 'max:4096'],

            // Caregiver attributes
            'gender' => ['required', 'in:female,male,other'],
            'date_of_birth' => ['nullable', 'date'],
            'caregiver_type' => ['required', 'string', 'max:120'],
            'years_experience' => ['required', 'integer', 'min:0', 'max:60'],
            'previous_workplace' => ['nullable', 'string', 'max:150'],
            'previous_experience' => ['nullable', 'string', 'max:1500'],
            'education_qualification' => ['nullable', 'string', 'max:150'],
            'education_institution' => ['nullable', 'string', 'max:150'],
            'education_passing_year' => ['nullable', 'integer', 'min:1970', 'max:'.date('Y')],

            // Pricing & Employment
            'daily_rate' => ['required', 'numeric', 'min:0'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0'],
            'weekly_rate' => ['nullable', 'numeric', 'min:0'],
            'monthly_rate' => ['nullable', 'numeric', 'min:0'],
            'employment_type' => ['required', 'in:full_time,part_time,both'],
            'live_type' => ['required', 'in:live_in,live_out,both'],
            'preferred_client_gender' => ['required', 'in:any,female,male'],
            'preferred_hours' => ['nullable', 'string', 'max:100'],
            'is_available' => ['nullable', 'boolean'],

            // Location
            'division_id' => ['nullable', 'exists:locations,id'],
            'district_id' => ['nullable', 'exists:locations,id'],
            'area_id' => ['nullable', 'exists:locations,id'],
            'city' => ['nullable', 'string', 'max:100'],
            'present_address' => ['nullable', 'string', 'max:255'],
            'permanent_address' => ['nullable', 'string', 'max:255'],

            // Emergency contact
            'emergency_contact_name' => ['nullable', 'string', 'max:100'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:25'],
            'emergency_contact_relationship' => ['nullable', 'string', 'max:60'],

            // Marketplace Sequencing & Status
            'status' => ['required', 'string', 'in:draft,pending_verification,under_review,changes_required,approved,published,suspended,rejected'],
            'status_reason' => ['nullable', 'string', 'max:500'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],

            // Bio & Skills
            'skills' => ['nullable', 'string'],
            'specializations' => ['nullable', 'string'],
            'languages' => ['nullable', 'string'],
            'about' => ['nullable', 'string', 'max:1500'],
            'bio' => ['nullable', 'string', 'max:3000'],
            'special_skills' => ['nullable', 'string', 'max:1000'],

            // Services
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['exists:services,id'],
            'primary_service_id' => ['nullable', 'exists:services,id'],
        ]);

        $prevStatus = $caregiver->status->value;

        DB::transaction(function () use ($caregiver, $request, $validated, $admin, $prevStatus): void {
            // Update User details
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ];
            if (! empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }
            if ($request->hasFile('profile_photo')) {
                $userData['avatar_path'] = $request->file('profile_photo')->store('avatars', 'public');
            }
            $caregiver->user->update($userData);

            // Parse comma-separated arrays
            $skillsArray = ! empty($validated['skills']) ? array_values(array_filter(array_map('trim', explode(',', $validated['skills'])))) : $caregiver->skills;
            $specArray = ! empty($validated['specializations']) ? array_values(array_filter(array_map('trim', explode(',', $validated['specializations'])))) : $caregiver->specializations;
            $langArray = ! empty($validated['languages']) ? array_values(array_filter(array_map('trim', explode(',', $validated['languages'])))) : $caregiver->languages;

            $newStatus = CaregiverStatus::from($validated['status']);

            $updateData = [
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'caregiver_type' => $validated['caregiver_type'],
                'years_experience' => $validated['years_experience'],
                'previous_workplace' => $validated['previous_workplace'] ?? null,
                'previous_experience' => $validated['previous_experience'] ?? null,
                'education_qualification' => $validated['education_qualification'] ?? null,
                'education_institution' => $validated['education_institution'] ?? null,
                'education_passing_year' => $validated['education_passing_year'] ?? null,
                'daily_rate' => $validated['daily_rate'],
                'hourly_rate' => $validated['hourly_rate'] ?? null,
                'weekly_rate' => $validated['weekly_rate'] ?? null,
                'monthly_rate' => $validated['monthly_rate'] ?? null,
                'employment_type' => $validated['employment_type'],
                'live_type' => $validated['live_type'],
                'preferred_client_gender' => $validated['preferred_client_gender'],
                'preferred_hours' => $validated['preferred_hours'] ?? null,
                'is_available' => $request->boolean('is_available', true),
                'division_id' => $validated['division_id'] ?? null,
                'district_id' => $validated['district_id'] ?? null,
                'area_id' => $validated['area_id'] ?? null,
                'city' => $validated['city'] ?? null,
                'present_address' => $validated['present_address'] ?? null,
                'permanent_address' => $validated['permanent_address'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
                'emergency_contact_relationship' => $validated['emergency_contact_relationship'] ?? null,
                'status' => $newStatus,
                'status_reason' => $validated['status_reason'] ?? null,
                'is_featured' => $request->boolean('is_featured'),
                'sort_order' => (int) ($validated['sort_order'] ?? 0),
                'skills' => $skillsArray,
                'specializations' => $specArray,
                'languages' => $langArray,
                'about' => $validated['about'] ?? $caregiver->about,
                'bio' => $validated['bio'] ?? $caregiver->bio,
                'special_skills' => $validated['special_skills'] ?? null,
                'search_text' => implode(' ', array_filter([
                    $validated['name'],
                    $validated['caregiver_type'],
                    $validated['city'] ?? '',
                    $validated['about'] ?? '',
                    implode(' ', (array) $skillsArray),
                ])),
            ];

            if ($newStatus === CaregiverStatus::Published && ! $caregiver->published_at) {
                $updateData['published_at'] = now();
            }
            if ($newStatus === CaregiverStatus::Approved && ! $caregiver->approved_at) {
                $updateData['approved_at'] = now();
            }

            $caregiver->update($updateData);

            // Sync services
            if (isset($validated['service_ids']) && is_array($validated['service_ids'])) {
                $syncData = [];
                $primaryId = (int) ($validated['primary_service_id'] ?? 0);
                foreach ($validated['service_ids'] as $svcId) {
                    $syncData[$svcId] = ['is_primary' => ((int) $svcId === $primaryId)];
                }
                $caregiver->services()->sync($syncData);
            }

            // Sync user suspension if status is suspended
            if ($newStatus === CaregiverStatus::Suspended) {
                $caregiver->user->update([
                    'suspended_at' => now(),
                    'suspension_reason' => $validated['status_reason'] ?? 'Administrative compliance update',
                ]);
            } elseif ($prevStatus === CaregiverStatus::Suspended->value && $newStatus !== CaregiverStatus::Suspended) {
                $caregiver->user->update([
                    'suspended_at' => null,
                    'suspension_reason' => null,
                ]);
            }

            $this->auditLogService->log(
                $admin,
                'caregiver.profile_updated_by_admin',
                $caregiver,
                ['status' => $prevStatus],
                ['status' => $newStatus->value, 'sort_order' => $updateData['sort_order']],
                "Admin updated caregiver profile and marketplace settings for {$caregiver->user->name}"
            );
        });

        return redirect()->route('admin.caregivers.show', $caregiver)
            ->with('success', "Caregiver profile for {$caregiver->user->name} has been successfully updated.");
    }

    public function updateSortOrder(Request $request, Caregiver $caregiver): RedirectResponse
    {
        $validated = $request->validate([
            'sort_order' => ['required', 'integer', 'min:0', 'max:99999'],
        ]);

        $caregiver->update([
            'sort_order' => (int) $validated['sort_order'],
        ]);

        $this->auditLogService->log(
            Auth::user(),
            'caregiver.sort_order_updated',
            $caregiver,
            [],
            ['sort_order' => $caregiver->sort_order],
            "Admin updated display position of {$caregiver->user->name} to {$caregiver->sort_order}"
        );

        return back()->with('success', "Display order for {$caregiver->user->name} updated to #{$caregiver->sort_order}.");
    }

    public function toggleFeatured(Caregiver $caregiver): RedirectResponse
    {
        $caregiver->update(['is_featured' => ! $caregiver->is_featured]);

        return back()->with('success', 'Caregiver featured status updated.');
    }
}
