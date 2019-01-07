<?php

namespace Kastengel\Navigator\Tests\Unit;

use Nagasari\Navigator\Navigation;
use Kastengel\Packdev\Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NavigationTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testItIsInitializable()
    {
        $navigation = new Navigation('test.id', 'url', 1, 'test.parent.id', []);

        $this->assertSame($navigation->id, 'test.id');
        $this->assertSame($navigation->url, 'url');
        $this->assertSame($navigation->order, 1);
        $this->assertSame($navigation->parent, 'test.parent.id');
        $this->assertSame($navigation->attributes, []);
    }

    /**
     * Test child can be registered
     *
     * @return void
     */
    public function testItCanRegisterChild()
    {
        $navigation = new Navigation('test.id', 'url', 1, 'test.parent.id', []);
        $childNavigation = new Navigation('test.child.id', 'url', 1, 'test.id', []);

        $navigation->registerChild($childNavigation);
        $childs = $navigation->childs();

        $this->assertTrue($childs instanceof Collection);
        $this->assertSame($childs->count(), 1);

        // It only register unique child
        $navigation->registerChild($childNavigation);
        $this->assertSame($navigation->childs()->count(), 1);
    }

    /**
     * Test it can retrieve child by id
     *
     * @return void
     */
    public function testItCanRetrieveChildById()
    {
        $navigation = new Navigation('test.id', 'url', 1, 'test.parent.id', []);
        $childNavigation1 = new Navigation('test.child.id.1', 'url', 1, 'test.id', []);
        $childNavigation2 = new Navigation('test.child.id.2', 'url', 2, 'test.id', []);

        $navigation->registerChild([$childNavigation1, $childNavigation2]);
        $childNavigation = $navigation->child('test.child.id.2');

        $this->assertSame($childNavigation->order, 2);
    }

    /**
     * Test it can be converted to string
     *
     * @return void
     */
    public function testItCanBeConvertedToString()
    {
        $navigation = new Navigation('test.id', 'url', 1, 'test.parent.id', []);
        $childNavigation1 = new Navigation('test.child.id.1', 'url', 1, 'test.id', []);
        $childNavigation2 = new Navigation('test.child.id.2', 'url', 2, 'test.id', []);

        $navigation->registerChild([$childNavigation1, $childNavigation2]);

        $this->assertSame((string)$navigation, $navigation->toJson());
    }
}
