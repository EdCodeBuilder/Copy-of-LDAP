<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RoleOrPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roleOrPermission, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            throw new AuthenticationException(__('validation.handler.unauthenticated'));
        }
        $rolesOrPermissions = is_array($roleOrPermission)
            ? $roleOrPermission
            : explode('|', $roleOrPermission);

        if ($authGuard->user()->isNotAn(...$rolesOrPermissions) && ! $authGuard->user()->hasAnyPermission($rolesOrPermissions)) {
            throw new AccessDeniedHttpException(__('validation.handler.unauthorized'));
        }

        return $next($request);
    }
}
