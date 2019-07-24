<?php

namespace Febalist\Laravel\Sentry\Http\Middleware;

use App\User;
use Closure;
use Febalist\Laravel\Sentry\Sentry;
use Illuminate\Http\Request;

class SentryContext
{
    public function handle(Request $request, Closure $next)
    {
        if (Sentry::enabled()) {
            /** @var User $user */
            $user = auth()->user();

            Sentry::user([
                'ip_address' => $request->ip(),
                'guest' => !$user,
            ]);

            if ($user) {
                Sentry::user($user->only(['id', 'login', 'username', 'email', 'name']));
            }

            Sentry::tags([
                'route' => $request->route()->getName(),
                'action' => $request->route()->getActionName(),
            ]);
        }

        return $next($request);
    }
}
