<?php

namespace App\Providers;

use App\Models\Security\AuthCode;
use App\Models\Security\Client;
use App\Models\Security\PersonalAccessClient;
use App\Models\Security\Token;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes(function ($router) {
            $router->forAccessTokens();
            $router->forTransientTokens();
        }, ['middleware' => 'api']);
        Passport::tokensExpireIn(now()->addHours(8));
        Passport::refreshTokensExpireIn(now()->addHours(16));
        Passport::$ignoreCsrfToken = true;
        Passport::$pruneRevokedTokens = true;
        Passport::$revokeOtherTokens = true;
        Passport::$cookie = "dashboard_idrd_token";
        Passport::useAuthCodeModel(AuthCode::class);
        Passport::useClientModel( Client::class );
        Passport::usePersonalAccessClientModel(PersonalAccessClient::class);
        Passport::useTokenModel( Token::class );
    }
}
