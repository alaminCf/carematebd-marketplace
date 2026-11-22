<x-layouts.dashboard>
    <x-slot:title>Admin & User Management — Admin | CareMate BD</x-slot:title>
    <x-slot:header>Admin & User Management</x-slot:header>
    <x-slot:subheading>Create and manage role-based administrator accounts, staff, and system users.</x-slot:subheading>

    @if (session('success'))
        <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.35); color: #065f46; padding: 0.9rem 1.25rem; border-radius: var(--radius-lg); margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; font-weight: 600; font-size: 0.9rem;">
            <span>✓ {{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; font-size: 1.1rem; cursor: pointer; color: #065f46;">✕</button>
        </div>
    @endif

    @if (session('error'))
        <div style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.35); color: #991b1b; padding: 0.9rem 1.25rem; border-radius: var(--radius-lg); margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; font-weight: 600; font-size: 0.9rem;">
            <span>⚠️ {{ session('error') }}</span>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; font-size: 1.1rem; cursor: pointer; color: #991b1b;">✕</button>
        </div>
    @endif

    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
            <span style="font-size: 0.88rem; color: var(--text-muted);">
                Total Registered: <strong style="color: #0f172a;">{{ $users->total() }}</strong> Users
            </span>
        </div>

        <button type="button" onclick="openCreateModal()" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 700; padding: 0.65rem 1.25rem; box-shadow: 0 4px 14px rgba(10, 57, 74, 0.2);">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>Create New User</span>
        </button>
    </div>

    <!-- Users Listing & Filtering Card -->
    <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); width: 100%; box-sizing: border-box;">
        <!-- Search & Role Filter Tabs -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 0.75rem; flex: 1; max-width: 440px;">
                @if ($roleFilter)
                    <input type="hidden" name="role" value="{{ $roleFilter }}">
                @endif
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, email, phone..." class="glass-input" style="font-size: 0.85rem; padding: 0.5rem 0.85rem;">
                <button type="submit" class="btn btn-secondary btn-sm">Search</button>
                @if ($search || $roleFilter)
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm" style="font-size: 0.8rem; padding: 0.4rem 0.65rem;">Reset</a>
                @endif
            </form>

            <!-- Role Quick Filter Pills -->
            <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                <a href="{{ route('admin.users.index', array_filter(['search' => $search])) }}" 
                   class="btn btn-sm {{ empty($roleFilter) ? 'btn-primary' : 'btn-secondary' }}" 
                   style="font-size: 0.78rem; padding: 0.35rem 0.75rem;">
                    All Users
                </a>
                <a href="{{ route('admin.users.index', array_filter(['role' => 'admin', 'search' => $search])) }}" 
                   class="btn btn-sm {{ $roleFilter === 'admin' ? 'btn-primary' : 'btn-secondary' }}" 
                   style="font-size: 0.78rem; padding: 0.35rem 0.75rem;">
                    👑 Admins
                </a>
                <a href="{{ route('admin.users.index', array_filter(['role' => 'caregiver', 'search' => $search])) }}" 
                   class="btn btn-sm {{ $roleFilter === 'caregiver' ? 'btn-primary' : 'btn-secondary' }}" 
                   style="font-size: 0.78rem; padding: 0.35rem 0.75rem;">
                    🩺 Caregivers
                </a>
                <a href="{{ route('admin.users.index', array_filter(['role' => 'client', 'search' => $search])) }}" 
                   class="btn btn-sm {{ $roleFilter === 'client' ? 'btn-primary' : 'btn-secondary' }}" 
                   style="font-size: 0.78rem; padding: 0.35rem 0.75rem;">
                    👨‍👩‍👧 Clients
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container" style="overflow-x: auto;">
            <table class="glass-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>User Account</th>
                        <th>Role</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th style="text-align: right; min-width: 220px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $u)
                        <tr>
                            <!-- Account -->
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <img src="{{ $u->avatarUrl() }}" alt="{{ $u->name }}" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 1px solid rgba(226, 232, 240, 0.8);">
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a; font-size: 0.92rem; display: flex; align-items: center; gap: 0.4rem;">
                                            {{ $u->name }}
                                            @if ($u->id === auth()->id())
                                                <span style="font-size: 0.65rem; background: rgba(10, 57, 74, 0.1); color: var(--primary, #0a394a); padding: 0.15rem 0.45rem; border-radius: 4px; font-weight: 800;">YOU</span>
                                            @endif
                                        </div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $u->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Role -->
                            <td>
                                @if ($u->isAdmin())
                                    <span style="display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; background: rgba(245, 158, 11, 0.15); color: #b45309; border: 1px solid rgba(245, 158, 11, 0.3);">
                                        👑 Admin
                                    </span>
                                @elseif ($u->isCaregiver())
                                    <span style="display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; background: rgba(16, 185, 129, 0.12); color: #047857; border: 1px solid rgba(16, 185, 129, 0.3);">
                                        🩺 Caregiver
                                    </span>
                                @else
                                    <span style="display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; background: rgba(59, 130, 246, 0.12); color: #1d4ed8; border: 1px solid rgba(59, 130, 246, 0.3);">
                                        👨‍👩‍👧 Client
                                    </span>
                                @endif
                            </td>

                            <!-- Phone -->
                            <td style="font-size: 0.85rem; color: #475569; font-family: monospace;">
                                {{ $u->phone }}
                            </td>

                            <!-- Status -->
                            <td>
                                @if ($u->isActive())
                                    <x-badge tone="success">Active</x-badge>
                                @elseif ($u->isSuspended())
                                    <x-badge tone="danger">Suspended</x-badge>
                                @else
                                    <x-badge tone="warning">{{ ucfirst($u->status->value) }}</x-badge>
                                @endif
                            </td>

                            <!-- Created & Edit Log -->
                            <td style="font-size: 0.78rem; color: var(--text-muted); white-space: nowrap;">
                                <div style="font-weight: 700; color: #0f172a;">
                                    📅 {{ $u->created_at?->format('d M Y, h:i A') }}
                                </div>
                                <div style="font-size: 0.72rem; color: #64748b;">
                                    Added {{ $u->created_at?->diffForHumans() }}
                                </div>
                                @php
                                    $lastUserAction = $u->entityAdminActions->first();
                                @endphp
                                @if ($lastUserAction && $lastUserAction->admin)
                                    <div style="font-size: 0.71rem; color: #0d9488; font-weight: 700; margin-top: 0.15rem;">
                                        ✏️ {{ $lastUserAction->actionLabel() }} by {{ $lastUserAction->admin->name }}
                                    </div>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td style="text-align: right;">
                                <div style="display: inline-flex; align-items: center; gap: 0.4rem; justify-content: flex-end;">
                                    <!-- Edit User Action -->
                                    <button type="button"
                                            onclick="openEditUserModal('{{ $u->id }}', '{{ addslashes($u->name) }}', '{{ addslashes($u->email) }}', '{{ addslashes($u->phone ?? '') }}', '{{ $u->role->value }}')"
                                            class="btn btn-secondary btn-sm"
                                            title="Edit User Details"
                                            style="padding: 0.3rem 0.55rem; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                                        ✏️ <span class="d-none d-sm-inline">Edit</span>
                                    </button>

                                    <!-- Change Password Action -->
                                    <button type="button" 
                                            onclick="openChangePasswordModal('{{ $u->id }}', '{{ addslashes($u->name) }}', '{{ addslashes($u->email) }}')"
                                            class="btn btn-secondary btn-sm" 
                                            title="Change Password"
                                            style="padding: 0.3rem 0.55rem; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                                        🔑 <span class="d-none d-sm-inline">Pass</span>
                                    </button>

                                    <!-- Status Toggle Action -->
                                    @if ($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggle-status', $u) }}" style="display: inline;" onsubmit="return confirm('{{ $u->isSuspended() ? 'Reactivate this account?' : 'Suspend this user account?' }}')">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary btn-sm" style="padding: 0.3rem 0.55rem; font-size: 0.75rem;">
                                                {{ $u->isSuspended() ? 'Activate' : 'Suspend' }}
                                            </button>
                                        </form>

                                        <!-- Delete Action -->
                                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete user \'{{ $u->name }}\'?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="padding: 0.3rem 0.45rem; background: rgba(239, 68, 68, 0.1); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.2);" title="Delete user">
                                                🗑️
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🔍</div>
                                <p style="margin: 0; font-size: 0.95rem;">No users found matching your search criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Recent Admin & User Account Edit History -->
    @if (isset($recentUserEdits) && $recentUserEdits->isNotEmpty())
        <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); margin-top: 1.75rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.15rem; flex-wrap: wrap; gap: 0.5rem;">
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0;">🕒 Recent Admin & User Edit History</h3>
                    <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0.15rem 0 0 0;">Log of user creation, role/profile edits, password resets, and status changes by administrators.</p>
                </div>
                <a href="{{ route('admin.audit-logs', ['entity' => 'User']) }}" style="font-size: 0.8rem; font-weight: 700; color: #0d9488; text-decoration: none;">
                    View All User Audit Logs →
                </a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @foreach ($recentUserEdits as $editLog)
                    @php
                        $targetInfo = $editLog->targetInfo();
                        $diffs = $editLog->changedFields();
                    @endphp
                    <div style="padding: 0.9rem 1.1rem; background: #f8fafc; border: 1px solid rgba(226, 232, 240, 0.95); border-radius: 14px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                <span style="font-weight: 800; font-size: 0.86rem; color: #0f172a;">
                                    👑 {{ $editLog->admin?->name ?? 'Admin' }}
                                </span>
                                <span style="font-size: 0.72rem; font-weight: 800; background: rgba(13, 148, 136, 0.12); color: #0f766e; padding: 0.14rem 0.55rem; border-radius: 999px;">
                                    {{ $editLog->actionLabel() }}
                                </span>
                                <span style="font-size: 0.82rem; color: #334155; font-weight: 700;">
                                    → {{ $targetInfo['label'] }}
                                </span>
                            </div>
                            <span style="font-size: 0.76rem; color: #475569; font-weight: 700;">
                                📅 {{ $editLog->created_at->format('d M Y, h:i:s A') }} ({{ $editLog->created_at->diffForHumans() }})
                            </span>
                        </div>
                        @if (count($diffs) > 0)
                            <div style="margin-top: 0.45rem; display: flex; flex-wrap: wrap; gap: 0.45rem;">
                                @foreach ($diffs as $d)
                                    <span style="font-size: 0.74rem; background: #ffffff; border: 1px solid #e2e8f0; padding: 0.18rem 0.55rem; border-radius: 8px; color: #334155;">
                                        <strong>{{ $d['field'] }}:</strong>
                                        @if ($d['from'] !== '—')
                                            <span style="color: #dc2626; text-decoration: line-through;">{{ $d['from'] }}</span> →
                                        @endif
                                        <strong style="color: #059669;">{{ $d['to'] }}</strong>
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Modal: Edit User Details -->
    <div id="edit-user-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(5px); z-index: 9999; align-items: center; justify-content: center; padding: 1.25rem;">
        <div class="glass-card" style="background: rgba(255, 255, 255, 0.98); max-width: 500px; width: 100%; border-radius: var(--radius-xl); padding: 2rem; box-shadow: 0 25px 50px rgba(0,0,0,0.25);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;">✏️ Edit User Account</h3>
                <button type="button" onclick="closeEditUserModal()" style="background: none; border: none; font-size: 1.6rem; cursor: pointer; color: var(--text-muted); line-height: 1;">&times;</button>
            </div>
            <form id="edit-user-form" method="POST" action="">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 0.9rem;">
                    <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">Full Name *</label>
                    <input type="text" name="name" id="edit-user-name" required class="glass-input" style="font-size: 0.88rem;">
                </div>
                <div style="margin-bottom: 0.9rem;">
                    <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">Email Address *</label>
                    <input type="email" name="email" id="edit-user-email" required class="glass-input" style="font-size: 0.88rem;">
                </div>
                <div style="margin-bottom: 0.9rem;">
                    <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">Phone Number *</label>
                    <input type="text" name="phone" id="edit-user-phone" required class="glass-input" style="font-size: 0.88rem;">
                </div>
                <div style="margin-bottom: 1.35rem;">
                    <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">Role *</label>
                    <select name="role" id="edit-user-role" required class="glass-input" style="font-size: 0.88rem;">
                        <option value="admin">👑 Administrator</option>
                        <option value="caregiver">🩺 Caregiver / Nurse</option>
                        <option value="client">👨‍👩‍👧 Client / Family</option>
                    </select>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" onclick="closeEditUserModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="font-weight: 700;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Create New User (Accessible, Responsive, Never clipped) -->
    <div id="create-user-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(5px); z-index: 9999; align-items: center; justify-content: center; padding: 1.25rem;">
        <div class="glass-card" style="background: rgba(255, 255, 255, 0.98); max-width: 540px; width: 100%; border-radius: var(--radius-xl); padding: 2rem; box-shadow: 0 25px 50px rgba(0,0,0,0.25); max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <div style="display: flex; align-items: center; gap: 0.6rem;">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #0a394a 0%, #0e7490 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                        👤
                    </div>
                    <h3 style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin: 0;">Create New User</h3>
                </div>
                <button type="button" onclick="closeCreateModal()" style="background: none; border: none; font-size: 1.6rem; cursor: pointer; color: var(--text-muted); line-height: 1;">&times;</button>
            </div>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                Create an administrator, caregiver, or client account with role-based access.
            </p>

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <!-- Full Name -->
                <div style="margin-bottom: 1rem;">
                    <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">Full Name <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Tanvir Ahmed" class="glass-input" style="font-size: 0.88rem;">
                    @error('name') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <!-- Email Address -->
                <div style="margin-bottom: 1rem;">
                    <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">Email Address <span style="color: #ef4444;">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="e.g. tanvir@carematebd.com" class="glass-input" style="font-size: 0.88rem;">
                    @error('email') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <!-- Role Selector -->
                <div style="margin-bottom: 1rem;">
                    <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">Role Assignment <span style="color: #ef4444;">*</span></label>
                    <select name="role" required class="glass-input" style="font-size: 0.88rem;">
                        <option value="admin" {{ old('role', 'admin') === 'admin' ? 'selected' : '' }}>👑 Administrator (Full Platform Access)</option>
                        <option value="caregiver" {{ old('role') === 'caregiver' ? 'selected' : '' }}>🩺 Caregiver (Care Provider)</option>
                        <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>👨‍👩‍👧 Client / Family (Family Account)</option>
                    </select>
                    @error('role') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <!-- Phone Number (Optional) -->
                <div style="margin-bottom: 1rem;">
                    <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">Mobile Phone Number <span style="font-size: 0.72rem; color: var(--text-muted); font-weight: 400;">(Optional)</span></label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="e.g. +880 1712-345678" class="glass-input" style="font-size: 0.88rem;">
                    <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 0.25rem;">Leave blank to auto-generate a unique system phone number.</div>
                    @error('phone') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <!-- Password Row -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">Password <span style="color: #ef4444;">*</span></label>
                        <input type="password" name="password" required placeholder="Min 6 characters" class="glass-input" style="font-size: 0.88rem;">
                        @error('password') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">Confirm Password <span style="color: #ef4444;">*</span></label>
                        <input type="password" name="password_confirmation" required placeholder="Repeat password" class="glass-input" style="font-size: 0.88rem;">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" onclick="closeCreateModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="font-weight: 700; display: inline-flex; align-items: center; gap: 0.4rem;">
                        <span>✓</span> Create User Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Change Password for User -->
    <div id="change-password-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(5px); z-index: 9999; align-items: center; justify-content: center; padding: 1.25rem;">
        <div class="glass-card" style="background: rgba(255, 255, 255, 0.98); max-width: 480px; width: 100%; border-radius: var(--radius-xl); padding: 2rem; box-shadow: 0 25px 50px rgba(0,0,0,0.25);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <div style="display: flex; align-items: center; gap: 0.6rem;">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(10, 57, 74, 0.1); color: var(--primary, #0a394a); display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                        🔑
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;">Change User Password</h3>
                </div>
                <button type="button" onclick="closeChangePasswordModal()" style="background: none; border: none; font-size: 1.6rem; cursor: pointer; color: var(--text-muted); line-height: 1;">&times;</button>
            </div>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem;">
                Set a new password for <strong id="modal-target-username" style="color: #0f172a;">User</strong> (<span id="modal-target-email"></span>).
            </p>

            <form id="change-password-form" method="POST" action="">
                @csrf

                <!-- New Password -->
                <div style="margin-bottom: 1rem;">
                    <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">New Password <span style="color: #ef4444;">*</span></label>
                    <input type="password" name="password" required placeholder="Minimum 6 characters" class="glass-input" style="font-size: 0.88rem;">
                </div>

                <!-- Confirm Password -->
                <div style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="font-size: 0.82rem; font-weight: 700;">Confirm New Password <span style="color: #ef4444;">*</span></label>
                    <input type="password" name="password_confirmation" required placeholder="Repeat new password" class="glass-input" style="font-size: 0.88rem;">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" onclick="closeChangePasswordModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="font-weight: 700;">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openCreateModal() {
            document.getElementById('create-user-modal').style.display = 'flex';
        }

        function closeCreateModal() {
            document.getElementById('create-user-modal').style.display = 'none';
        }

        function openChangePasswordModal(userId, userName, userEmail) {
            const form = document.getElementById('change-password-form');
            form.action = `/admin/users/${userId}/password`;
            document.getElementById('modal-target-username').innerText = userName;
            document.getElementById('modal-target-email').innerText = userEmail;
            document.getElementById('change-password-modal').style.display = 'flex';
        }

        function closeChangePasswordModal() {
            document.getElementById('change-password-modal').style.display = 'none';
        }

        function openEditUserModal(userId, name, email, phone, role) {
            const form = document.getElementById('edit-user-form');
            form.action = `/admin/users/${userId}`;
            document.getElementById('edit-user-name').value = name;
            document.getElementById('edit-user-email').value = email;
            document.getElementById('edit-user-phone').value = phone;
            document.getElementById('edit-user-role').value = role;
            document.getElementById('edit-user-modal').style.display = 'flex';
        }

        function closeEditUserModal() {
            document.getElementById('edit-user-modal').style.display = 'none';
        }

        // Close on backdrop click
        window.addEventListener('click', function(e) {
            const createModal = document.getElementById('create-user-modal');
            const passModal = document.getElementById('change-password-modal');
            const editModal = document.getElementById('edit-user-modal');
            if (e.target === createModal) {
                closeCreateModal();
            }
            if (e.target === passModal) {
                closeChangePasswordModal();
            }
            if (e.target === editModal) {
                closeEditUserModal();
            }
        });

        @if ($errors->has('name') || $errors->has('email') || $errors->has('role') || ($errors->has('password') && old('name')))
            document.addEventListener('DOMContentLoaded', function() {
                openCreateModal();
            });
        @endif
    </script>
    @endpush
</x-layouts.dashboard>
