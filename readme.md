# **Navigation Service** 

[![Build Status](https://travis-ci.org/dluwang/navigator.svg?branch=master)](https://travis-ci.org/dluwang/navigator)
[![Latest Stable Version](https://poser.pugx.org/dluwang/navigator/v/stable)](https://packagist.org/packages/dluwang/navigator)
[![License](https://poser.pugx.org/dluwang/navigator/license)](https://packagist.org/packages/dluwang/navigator)

This is a package that provide service to manage navigations

## **Note** 

| Laravel Version | Package Version |
|--|-|
| 5.5 | 1.0.* |
| 5.6 | 1.1.* |
| 5.7 | 1.2.* |
| 5.8 | 1.3.* |
 
## **Installation**

```
composer require dluwang/navigation
```

## **Usage**

**Define Navigation**

`Dluwang/Navigator/Navigation` is a class to define your navigation. The `__construct` method has two mandatory arguments and four optional arguments

```php
public function __construct($id, $url, $order = 1, $parent = null, array $attributes = [], array $childs = [])
```

1.  `$id` is the id of the navigation

2.  `$url` is the url of the navigation

3.  `$order` is number for sorting the navigation

4.  `$parent` is the parent of the navigation

5.  `$attributes` is the additional attributes of navigation

6.  `$childs` is the childs of navigation

All of the properties above can be accessed via the object instance.

**Registering child**

There are two ways to register navigation's child.

-  **Eager child registration**

```php
$navigation->registerChild($childNavigation) // single child registration`

$navigation->registerChild([$childNavigation2, $childNavigation2]) // multiple childs registration
```

-  **Deferred child registration**

Sometimes you want to hook navigation and register the child somewhere in your app. You just need to specify the parent argument with the `navigation-id` you want to hook.

```php
$navigation = new Navigation('navigation-id', 'the-url', 'the-order', 'the-parent-navigation-id');
```

**Retrieveing all childs**  

```php
$navigation->childs()
```

**Retrievent child by id**

```php
$navigation->child('navigation-id-wanted')
```

**Define Navigator**

`Dluwang/Navigator/BaseNavigator` is a class that act as the repository of Navigations. All Navigator implementation should implements `Dluwang/Navigator/Navigator` interface.

```php
$navigator = new BaseNavigator()
```

Constructor has one optional argument which is the navigations you want to register.

**Registering navigations** 

```php
$navigator->register($navigation) // single navigation registration
```

```php
$navigator->register([$navigation1, $navigation2]) // mutiple navigations
```

As mentioned above, the deferred child can be registered casually to navigator.  

**Retrieve raw registered navigations**

This package under the hood do some data preparation such as sorting and collect deferred childs. To get raw data you can use.  

```php
$navigator->raw();
```

To specify parent, use:

```php
$navigator->raw('parent-id');
```

To get prepared data you can use the methods below.

**Retrieve all navigations**

```php
$navigator->navigations()
```

You can specify the navigations loaded by their parent.

```php
$navigator->navigations('parent-id');
```

**Retrieve navigation by id**

```php
$navigator->navigation('navigation-id')
```

## **Integration**

Currently, this package only integrated with **laravel framework**. You can register your defined navigation in you app service provider at the boot method. This integration add a caching mechanism when building navigation

```php
public function boot() {

	// define navigation
	$navigation = ...

	$this->app->navigator->register($navigation);

}
```

## **Tests**

To run test, run this following command

```
vendor/bin/phpunit
```