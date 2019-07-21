<?php

namespace Febalist\Laravel\Sentry\Http\Middleware;

use Closure;
use Febalist\Laravel\Sentry\Sentry;
use Illuminate\Http\Request;

class SentryContext
{
    public function handle(Request $request, Closure $next)
    {
        if (Sentry::enabled()) {
            $user = auth()->user();

            Sentry::user([
                'ip_address' => $request->ip(),
                'id' => $user->id ?? 0,
                'guest' => !$user,
                'login' => $user->login ?? $user->username ?? null,
                'email' => $user->email ?? null,
                'name' => $user->name ?? null,
            ]);

            Sentry::tags([
                'route' => $request->route()->getName(),
                'action' => $request->route()->getActionName(),
            ]);
        }

        return $next($request);
    }
}
