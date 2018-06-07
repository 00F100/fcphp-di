# fcphp-di
Dependency Injection 4 FcPHP

[![Build Status](https://travis-ci.org/00F100/fcphp-di.svg?branch=master)](https://travis-ci.org/00F100/fcphp-di) [![codecov](https://codecov.io/gh/00F100/fcphp-di/branch/master/graph/badge.svg)](https://codecov.io/gh/00F100/fcphp-di)

## How to use

```php
<?php

use FcPHP\Di\Facades\DiFacade;

$di = DiFacade::getInstance();

// Method set()
// Return void
$di->set(string $id, string $namespace, array $args = [], array $setters = [], bool $singleton = true);

// Method get()
// Return new instance of Container
$di->get(string $id, array $args = [], array $setters = []);

// Method make()
// Return instance of class
$di->make(string $id, array $args = [], array $setters = []);
```

## Examples

```php
<?php

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