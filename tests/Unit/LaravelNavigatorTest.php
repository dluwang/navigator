<?php

namespace Dluwang\Navigator\Tests\Unit;

use Mockery;
use Dluwang\Navigator\Navigation;
use Dluwang\Navigator\Laravel\Navigator;
use Kastengel\Packdev\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Cache\Repository as Cache;

class LaravelNavigatorTest extends TestCase
{
    /**
     * @var array
     */
    protected $navigations = [];

    /**
     * Setup test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $navigation1 = new Navigation('test.navigation.1', 'url', 1);
        $navigation2 = new Navigation('test.navigation.2', 'url', 3);
        $navigation3 = new Navigation('test.navigation.3', 'url', 2);

        $this->navigations = [$navigation1, $navigation2, $navigation3];

        $this->cache = Mockery::mock(Cache::class);
    }

    /**
     * Test it is initializable
     *
     * @return void
     */
    public function testItIsInitializable()
    {
        $this->cache->shouldReceive('get')
                  ->once()
                  ->with(md5('nav-test.navigation.1'))
                  ->andReturn($this->navigations[0])
                  ->shouldReceive('get')
                  ->once()
                  ->with(md5('nav-test.navigation.2'))
                  ->andReturn($this->navigations[1])
                  ->shouldReceive('get')
                  ->once()
                  ->with(md5('nav-test.navigation.3'))
                  ->andReturn($this->navigations[2]);

        $navigator = new Navigator($this->cache, $this->navigations);
        $this->assertSame($navigator->navigations()->count(), 3);
    }
}
