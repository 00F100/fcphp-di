# FcPhp Dependency Injection

Package to manage dependencies of project.

[![Build Status](https://travis-ci.org/00F100/fcphp-di.svg?branch=master)](https://travis-ci.org/00F100/fcphp-di) [![codecov](https://codecov.io/gh/00F100/fcphp-di/branch/master/graph/badge.svg)](https://codecov.io/gh/00F100/fcphp-di)

## How to install

Composer:
```sh
$ composer require 00f100/fcphp-di
```

or add in composer.json
```json
{
	"require": {
		"00f100/fcphp-di": "*"
	}
}
```

## How to use

```php
<?php

use FcPHP\Di\Facades\DiFacade;

$di = DiFacade::getInstance();

/**
 * Method to set new class
 *
 * @param string $id Identify of instance
 * @param string $namespace Namespace of class
 * @param array $args Args to construct class
 * @return void
 */
$di->set(string $id, string $namespace, array $args = [], array $setters = [], bool $singleton = true);

/**
 * Method to get instance of Container
 *
 * @param string $id Identify of instance
 * @param array $args Args to construct instance
 * @return FcPhp\Di\Interfaces\IContainer
 */
$di->get(string $id, array $args = [], array $setters = []);

/**
 * Method to configure setters to class
 *
 * @param string $id Identify instance
 * @param array $setters Setters to class
 * @return FcPhp\Di\Interfaces\IDi
 */
$di->setter(string $id, array $setters);

/**
 * Method instance of class
 *
 * @param string $id Identify of instance
 * @param array $args Args to construct instance
 * @return mixed
 */
$di->make(string $id, array $args = [], array $setters = []);
```

## Examples

```php
<?php

use FcPHP\Di\Facades\DiFacade;

$di = DiFacade::getInstance();

/*
namespace Namespace\To {
	class Example {
		public $foo;
		private $anotherFoo;
		public function __construct(string $foo) {
			$this->foo = $foo;
		}
		public function setAnotherFoo($foo) {
			$this->anotherFoo = $foo;
		}
		public functio getAnotherFoo() {
			return $this->anotherFoo;
		}
	}
	class ExampleBar {
		public $example;
		__construct(Example $example) {
			$this->example = $example;
		}
	}
}
*/
$di->set('Example', 'Namespace\To\Example', ['foo' => 'bar'], ['setAnotherFoo', 'AnotherBar']);
$di->set('ExampleBar', 'Namespace\To\ExampleBar', ['example' => $di->get('Example')]);

// Print "bar"
echo $di->make('ExampleBar')->example->foo

// Print "AnotherBar"
echo $di->make('ExampleBar')->example->getAnotherFoo();
```

## Events

```php
<?php

use FcPHP\Di\Facades\DiFacade;
use FcPhp\Di\Interfaces\IInstance;
use FcPhp\Di\Interfaces\IContainer;

$di = DiFacade::getInstance();

$di->event([
	'beforeSet' => function(string $id, string $namespace, array $args, array $setters, bool $singleton) {

	},
	'afterSet' => function(string $id, string $namespace, array $args, array $setters, bool $singleton, IInstance $instance) {

	},
	'beforeGet' => function(string $id, array $args, array $setters) {

	},
	'afterGet' => function(string $id, array $args, array $setters, IInstance $instance, IContainer $container) {

	},
	'beforeMake' => function(string $id, array $args, array $setters) {

	},
	'afterMake' => function(string $id, array $args, array $setters, IInstance $instance, IContainer $container, $class) {

	}
]);
```