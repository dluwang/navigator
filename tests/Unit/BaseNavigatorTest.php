<?php

namespace Nagasari\Navigator\Tests\Unit;

use Nagasari\Navigator\Navigation;
use Nagasari\Navigator\BaseNavigator;
use Kastengel\Packdev\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

class BaseNavigatorTest extends TestCase
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
    public function setUp()
    {
        parent::setUp();

        $navigation1 = new Navigation('test.navigation.1', 'url', 1);
        $navigation2 = new Navigation('test.navigation.2', 'url', 3);
        $navigation3 = new Navigation('test.navigation.3', 'url', 2);

        $this->navigations = [$navigation1, $navigation2, $navigation3];
    }

    /**
     * Test it is initializable
     *
     * @return void
     */
    public function testItIsInitializable()
    {
        $navigator = new BaseNavigator($this->navigations);

        $this->assertSame($navigator->raw()->count(), 3);
    }

    /**
     * Test it can find navigation by id
     *
     * @return void
     */
    public function testItCanFindNavigationById()
    {
        $navigator = new BaseNavigator($this->navigations);
        $navigation = $navigator->navigation('test.navigation.3');

        $this->assertSame($navigation->order, 2);
    }

    /**
     * Test it can register navigation
     *
     * @return void
     */
    public function testItCanRegisterNavigation()
    {
        $navigation = new Navigation('test.navigation', 'url', 1);
        $navigation1 = new Navigation('test.navigation.1', 'url', 1);

        $navigator = new BaseNavigator($this->navigations);
        $chain = $navigator->register($navigation);

        // It is chainable
        $this->assertSame($navigator, $chain);
        $this->assertSame($chain->raw()->count(), 4);

        // It only register unique navigation
        $chain->register($navigation1);
        $this->assertSame($chain->raw()->count(), 4);
    }

    /**
     * Test it can build data
     *
     * @return void
     */
    public function testItCanBuildData()
    {
        $navigator = new BaseNavigator($this->navigations);

        // First level child
        $navigationChild1 = new Navigation('test.navigation.child.1', 'to.test.child', 1, 'test.navigation.2');
        $navigationChild2 = new Navigation('test.navigation.child.1', 'to.test.child.not-included', 2, 'test.navigation.2');

        // Second level child
        $navigationChild1Child1 = new Navigation('test.navigation.child.1', 'to.test.child', 2, 'test.navigation.child.1');
        $navigationChild1Child2 = new Navigation('test.navigation.child.2', 'to.test.child', 1, 'test.navigation.child.1');

        $navigationChild1->registerChild($navigationChild1Child1);
        $navigator->register($navigationChild1)->register([$navigationChild2, $navigationChild1Child2]);

        $loaded = $navigator->navigations();
        $otherLoaded = $navigator->navigations();

        $secondNavigation = $loaded->get(2);
        $secondNavigationChild1 = $secondNavigation->childs()->first();
        $secondNavigationChild1Child1 = $secondNavigationChild1->childs()->first();

        $this->assertTrue($loaded instanceof Collection);

        // Assert that object is cloned, they are equal but not same
        $this->assertEquals($secondNavigation, $otherLoaded[2]);
        $this->assertNotSame($secondNavigation, $otherLoaded[2]);

        // Assert Sorting capabilities
        $this->assertSame($secondNavigation->id, 'test.navigation.2');
        $this->assertSame($secondNavigationChild1->id, 'test.navigation.child.1');
        $this->assertSame($secondNavigationChild1Child1->id, 'test.navigation.child.2');

        $this->assertSame($secondNavigationChild1->parent, $secondNavigation);
        $this->assertSame($secondNavigationChild1->parent->parent, $secondNavigation->parent);
        $this->assertSame($secondNavigationChild1Child1->parent, $secondNavigationChild1);

        // Ensure no infinite loop
        $this->assertSame((string)$secondNavigation, $secondNavigation->toJson());
    }
}
