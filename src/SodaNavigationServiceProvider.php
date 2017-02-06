<?php

namespace Soda\Navigation;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Soda\Navigation\Console\Migrate;
use Soda\Navigation\Console\Seed;

class SodaNavigationServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Soda\Navigation\Controllers';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__.'/../views', 'soda-navigation');
        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        $this->app['soda.menu']->menu('sidebar', function ($menu) {
            $menu->addItem('Navigation', [
                'url'         => route('soda.navigation.index'),
                'label'       => 'Navigation',
                'isCurrent'   => soda_request_is('navigation*'),
                'icon'        => 'fa fa-compass',
                'permissions' => 'manage-navigation',
            ]);
        });
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            Migrate::class,
            Seed::class,
        ]);
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        Route::group([
            'middleware' => 'web',
            'namespace'  => $this->namespace,
        ], function ($router) {
            require __DIR__.'/../routes/web.php';
        });
    }
}
