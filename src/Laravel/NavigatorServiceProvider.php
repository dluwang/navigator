<?php

namespace Dluwang\Navigator\Laravel;

use Illuminate\Support\ServiceProvider;
use Dluwang\Navigator\Navigator;
use Dluwang\Navigator\Laravel\Navigator as LNavigator;

class NavigatorServiceProvider extends ServiceProvider
{
    /**           
    * Indicates if loading of the provider is deferred.                  
    *            
    * @var bool  
    */           
    protected $defer = true; 

    /**
     * Register service provider
     *
     * @return void
     */
    public function register() {
        $this->app->singleton(Navigator::class, function($app) {
            $cache = $app['cache.store'];

            return new LNavigator($cache);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Navigator::class];
    }
}
