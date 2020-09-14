<?php

namespace App\Http;

use App\Http\Middleware\AdminCheck;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\VerifyCsrfToken;
use Barryvdh\Cors\HandleCors;
use Cog\Laravel\Ban\Http\Middleware\ForbidBannedUser;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Spatie\Permission\Middlewares\RoleMiddleware;
use Spatie\ResponseCache\Middlewares\CacheResponse;
use Spatie\ResponseCache\Middlewares\DoNotCacheResponse;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware
        = [
            CheckForMaintenanceMode::class,
            ValidatePostSize::class,
            TrimStrings::class,
            ConvertEmptyStringsToNull::class,
            TrustProxies::class,
            HandleCors::class,

        ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups
        = [
            'web' => [
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                // \Illuminate\Session\Middleware\AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                CacheResponse::class,
            ],

            'api' => [
                'throttle:60,1',
                'bindings',
            ],

            'firewall' => [

            ],

        ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware
        = [
            'auth'                 => Authenticate::class,
            'admin'                => AdminCheck::class,
            'auth.basic'           => AuthenticateWithBasicAuth::class,
            'bindings'             => SubstituteBindings::class,
            'can'                  => Authorize::class,
            'guest'                => RedirectIfAuthenticated::class,
            'signed'               => ValidateSignature::class,
            'throttle'             => ThrottleRequests::class,
            'role'                 => RoleMiddleware::class,
            'permission'           => PermissionMiddleware::class,
            'doNotCacheResponse'   => DoNotCacheResponse::class,
            'cacheResponse'        => CacheResponse::class,
            'fw-block-attacks'     => BlockAttacks::class,
            'forbid-banned-user'   => ForbidBannedUser::class,
        ];
}
