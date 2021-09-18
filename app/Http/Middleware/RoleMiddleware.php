<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $role
     * @param null $guard
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next, $role, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            throw new AuthenticationException(__('validation.handler.unauthenticated'));
        }

        $roles = is_array($role)
            ? $role
            : explode('|', $role);

        if ($authGuard->user()->isNotAn(...$roles)) {
            throw new AccessDeniedHttpException(__('validation.handler.unauthorized'));
        }

        return $next($request);
    }
}
