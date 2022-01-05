#Rutas Pasarela de pagos

Para registar las rutas en el proyecto se debe abrir el archivo `app/Providers/RouteServiceProvider.php`

Archivo de rutas `app/Modules/PaymentGateway/src/routes/api.php`

```php
    use Illuminate\Support\Facades\Route;

    Route::prefix('name')->group(function () {
        Route::get('router', [Controller::class, 'index']);
    });
```

Crear la función `mapPaymentGatewayRoutes` al final del archivo `RouteServiceProvider` y relacionar el archivo de las rutas del proyecto.

```php
    public function mapPaymentGatewayRoutes()
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('app/Modules/PaymentGateway/src/routes/api.php'));
    }
```

Luego de crear la función, se debe registrar en el método `map` del `RouteServiceProvider`

```php
    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        //...
        $this->mapPaymentGatewayRoutes();
    }
```
