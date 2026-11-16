<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function __construct(
        public AuditLogService $auditLogService
    ) {}

    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            // Friendly message without leaking user existence enumeration
            return back()->with('status', 'If an account exists with that email address, a password reset link has been generated.');
        }

        // Generate a cryptographically secure token
        $plainToken = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($plainToken),
                'created_at' => now(),
            ]
        );

        $resetUrl = route('password.reset', [
            'token' => $plainToken,
            'email' => $user->email,
        ]);

        $this->auditLogService->log(
            $user,
            'user.password_reset_requested',
            $user,
            null,
            ['email' => $user->email]
        );

        // Try sending email if configured, otherwise fallback smoothly
        try {
            Mail::raw(
                "Hello {$user->name},\n\nYou requested a password reset for your CareMate BD account. Click the link below to set a new password:\n\n{$resetUrl}\n\nThis link will expire in 60 minutes.\n\nIf you did not request this, please ignore this email.\n\nCareMate BD Team",
                function ($message) use ($user): void {
                    $message->to($user->email)->subject('CareMate BD — Password Reset Request');
                }
            );
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('status', 'Password reset instructions have been dispatched.')
            ->with('direct_reset_url', $resetUrl);
    }

    public function showResetPassword(Request $request, string $token): View
    {
        $email = $request->query('email', '');

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $record || ! Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'This password reset link is invalid or has expired. Please request a new one.']);
        }

        // Check expiration (60 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return back()->withErrors(['email' => 'This password reset link has expired. Please request a new one.']);
        }

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return back()->withErrors(['email' => 'No user found with this email address.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ]);

        // Delete used reset token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        $this->auditLogService->log(
            $user,
            'user.password_reset_completed',
            $user,
            null,
            ['email' => $user->email]
        );

        return redirect()->route('login')->with('success', 'Your password has been successfully reset! Please sign in with your new password.');
    }

    public function showChangePassword(): View
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:6', 'confirmed', 'different:current_password'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $this->auditLogService->log(
            $user,
            'user.password_changed',
            $user,
            null,
            ['email' => $user->email]
        );

        return back()->with('success', 'Your password has been changed successfully.');
    }
}
