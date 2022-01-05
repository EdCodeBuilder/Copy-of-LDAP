<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapOrfeoRoutes();

        $this->mapParksRoutes();

        $this->mapContractorsRoutes();

        $this->mapPayrollRoutes();

        $this->mapPassportRoutes();

        $this->mapCitizenRoutes();

        $this->mapPaymentGatewayRoutes();

    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    public function mapOrfeoRoutes()
    {
        Route::middleware('api')
            ->group(base_path('routes/sub_routes/orfeo/orfeo.php'));
    }

    public function mapParksRoutes()
    {
        Route::middleware('api')
            ->group(base_path('app/Modules/Parks/src/routes/api.php'));
    }

    public function mapContractorsRoutes()
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('app/Modules/Contractors/src/routes/api.php'));
    }

    public function mapPayrollRoutes()
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/sub_routes/payroll/payroll.php'));
    }

    public function mapPassportRoutes()
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('app/Modules/Passport/src/routes/api.php'));
    }

    public function mapCitizenRoutes()
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('app/Modules/CitizenPortal/src/routes/api.php'));
    }

    public function mapPaymentGatewayRoutes()
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('app/Modules/PaymentGateway/src/routes/api.php'));
    }

}
