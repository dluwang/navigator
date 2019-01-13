<?php

namespace Dluwang\Navigator\Tests\Feature;

use Kastengel\Packdev\Tests\TestCase;
use Dluwang\Navigator\Laravel\NavigatorServiceProvider;
use Dluwang\Navigator\Laravel\Navigator as LNavigator;
use Dluwang\Navigator\Navigator;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NavigatorServiceProviderTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testItisLoaded()
    {
        $providers = $this->app->getLoadedProviders();

        $this->assertTrue(isset($providers[NavigatorServiceProvider::class]));
        $this->assertTrue( $this->app->make(Navigator::class) instanceof LNavigator);
    }
}
