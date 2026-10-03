<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->isSuspended()) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been suspended: '.($user->suspension_reason ?? 'Please contact CareMate support.'),
            ]);
        }

        if (! in_array($user->role->value, $roles, true)) {
            // Redirect them to their own dashboard
            return redirect()->route($user->role->dashboardRoute())
                ->with('error', 'You do not have access to that area.');
        }

        return $next($request);
    }
}
