<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\AdminAction;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function __construct(
        public AuditLogService $auditLogService
    ) {}

    public function index(Request $request): View
    {
        $search = $request->query('search');
        $roleFilter = $request->query('role');

        $query = User::with(['entityAdminActions.admin', 'caregiver'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($roleFilter && in_array($roleFilter, array_column(UserRole::cases(), 'value'), true)) {
            $query->where('role', $roleFilter);
        }

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::all();
        $userRoleCases = UserRole::cases();
        $recentUserEdits = AdminAction::with('admin')
            ->where('entity_type', User::class)
            ->latest()
            ->take(10)
            ->get();

        return view('admin.users.index', compact('users', 'search', 'roleFilter', 'roles', 'userRoleCases', 'recentUserEdits'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30', 'unique:users,phone'],
            'role' => ['required', 'string', Rule::enum(UserRole::class)],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $phone = $validated['phone'] ?? null;
        if (! $phone) {
            do {
                $phone = '+88017'.random_int(10000000, 99999999);
            } while (User::where('phone', $phone)->exists());
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $phone,
            'role' => UserRole::from($validated['role']),
            'status' => UserStatus::Active,
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        $this->auditLogService->log(
            Auth::user(),
            'admin.user.created',
            $user,
            null,
            [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role->value,
            ],
            "Created new {$user->role->value} account for {$user->name}"
        );

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' ({$user->role->label()}) was created successfully.");
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['required', 'string', 'max:30', 'unique:users,phone,'.$user->id],
            'role' => ['required', 'string', Rule::enum(UserRole::class)],
        ]);

        $oldState = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role->value,
        ];

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => UserRole::from($validated['role']),
        ]);

        $newState = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role->value,
        ];

        $prevDiff = [];
        $newDiff = [];
        foreach ($newState as $k => $v) {
            if (($oldState[$k] ?? null) !== $v) {
                $prevDiff[$k] = $oldState[$k] ?? null;
                $newDiff[$k] = $v;
            }
        }

        if (! empty($newDiff)) {
            $changedFields = implode(', ', array_keys($newDiff));
            $this->auditLogService->log(
                Auth::user(),
                'admin.user.updated',
                $user,
                $prevDiff,
                $newDiff,
                "Updated user {$user->name} ({$changedFields})"
            );
        }

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' details updated successfully.");
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot suspend your own administrative account.');
        }

        $previousStatus = $user->status;
        $newStatus = $user->isSuspended() ? UserStatus::Active : UserStatus::Suspended;

        $user->update([
            'status' => $newStatus,
            'suspended_at' => $newStatus === UserStatus::Suspended ? now() : null,
            'suspension_reason' => $newStatus === UserStatus::Suspended ? 'Suspended by admin '.Auth::user()->name : null,
        ]);

        $this->auditLogService->log(
            Auth::user(),
            'admin.user.status_toggled',
            $user,
            ['status' => $previousStatus->value],
            ['status' => $newStatus->value]
        );

        $actionWord = $newStatus === UserStatus::Active ? 'reactivated' : 'suspended';

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' account has been {$actionWord}.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own administrative account.');
        }

        $userName = $user->name;
        $userRole = $user->role->value;

        $this->auditLogService->log(
            Auth::user(),
            'admin.user.deleted',
            $user,
            ['name' => $userName, 'email' => $user->email, 'role' => $userRole],
            null
        );

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', "User '{$userName}' has been deleted successfully.");
    }

    public function updatePassword(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->auditLogService->log(
            Auth::user(),
            'admin.user.password_reset',
            $user,
            null,
            ['target_user' => $user->email, 'changed_by' => Auth::user()->email]
        );

        return redirect()->route('admin.users.index')->with('success', "Password for user '{$user->name}' ({$user->email}) was updated successfully.");
    }
}
