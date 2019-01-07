<?php

namespace Nagasari\Navigator\Tests\Feature;

use Kastengel\Packdev\Tests\TestCase;
use Nagasari\Navigator\Laravel\NavigatorServiceProvider;
use Nagasari\Navigator\Laravel\Navigator as LNavigator;
use Nagasari\Navigator\Navigator;
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
