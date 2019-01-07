###  **Navigation**

This is a package that provide service to manage navigations

#### **Version**

    1.1.0

#### **Instalation**

    composer require nagasari/navigation

#### **Usage**
#### **Define Navigation**
**Nagasari/Navigator/Navigation**  is  a class to define your navigation. The __construct method has two mandatory arguments and four optional arguments

    public function __construct($id, $url, $order = 1, $parent = null, array $attributes = [], array $childs = [])

 1. **$id** is the id of the navigation
 2. **$url** is the url of the navigation
 3. **$order** is number for sorting the navigation
 4. **$parent** is the parent of the navigation
 5. **$attributes** is the additional attributes of navigation
 6. **$childs** is the childs of navigation

All of the properties above can be accessed via the object instance.

**Registering child**
There are two methods to register navigation's child.

 - **Eager child registration**
    `$navigation->registerChild($childNavigation) // single child registration
    $navigation->registerChild([$childNavigation2, $childNavigation2]) //  multiple childs registration `
 - **Deferred child registration**
	Sometimes you want to hook navigation and register the child somewhere in your app. You just need to specify the parent argument with the **navigation id** you want to hook.

    `$navigation = new Navigation('the-id', 'the-url', 'the-order', 'the-parent-navigation-id');`

**Retrieveing all childs**

    $navigation->childs()

**Retrievent child by id**

    $navigation->child('navigation-id-wanted')
#### **Define Navigator**
**Nagasari/Navigator/BaseNavigator**  is a class that act as the repository of Navigations. All Navigator implementation should implements **Nagasari/Navigator/Navigator** interface.

    $navigator = new BaseNavigator()
Constructor has one optional argument which is the navigations that registered.

**Registering navigations**

    $navigator->register($navigation) // single navigation registration
    $navigator->register([$navigation1, $navigation2]) // mutiple navigations

As mentioned above, the deferred child can be registered casually to navigator.

**Retrieve raw registered navigations**

This package under the hood do some data preparation such as sorting and collect deferred childs. To get raw data you can use.

	$navigator->row();

	To specify parent, use:
	$navigator->row('parent-id');

To get prepared data you can use the methods below.

**Retrieve all navigations**

    $navigator->navigations()

    You can specify the navigations loaded by their parent.
    $navigator->navigations('parent-id');

**Retrieve navigation by id**

    $navigator->navigation('navigation-id')

#### **Integration**

Currently, this package only integrated with **laravel framework**. You can register your defined navigation in you app service provider at the boot method. This is integration add a caching mechanism when building navigation

    public function boot() {
	    // define navigation
	    $navigation = ...

	    $this->app->navigator->register($navigation);
    }

#### **Tests**
To run test, run this following command

    phpunit
