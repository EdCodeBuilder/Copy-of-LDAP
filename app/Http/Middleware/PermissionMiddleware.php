<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $permission
     * @param null $guard
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next, $permission, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            throw new AuthenticationException(__('validation.handler.unauthenticated'));
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $permission) {
            $perm = explode('$', $permission);
            if (count($perm) == 1) {
                if ($authGuard->user()->can(Arr::first($perm))) {
                    return $next($request);
                }
            } else {
                // First argument permission name. "create-user"
                // Second argument model name. "App\Model\User"
                if ($authGuard->user()->can(Arr::first($perm), Arr::last($perm))) {
                    return $next($request);
                }
            }
        }

        throw new AccessDeniedHttpException(__('validation.handler.unauthorized'));
    }
}
