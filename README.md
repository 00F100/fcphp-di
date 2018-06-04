# fcphp-di
Dependency Injection 4 FcPHP

[![Build Status](https://travis-ci.org/00F100/fcphp-di.svg?branch=master)](https://travis-ci.org/00F100/fcphp-di) [![codecov](https://codecov.io/gh/00F100/fcphp-di/branch/master/graph/badge.svg)](https://codecov.io/gh/00F100/fcphp-di)

```php
<?php

use FcPhp\Di\Factories\ContainerFactory;
use FcPhp\Di\Factories\DiFactory;
use FcPhp\Di\Factories\InstanceFactory;

$di = Di::getInstance(new DiFactory(), new ContainerFactory(), new InstanceFactory(), true);

// class Example(string $foo)
$di->set('Example', 'Namespace\To\Example', ['foo' => 'bar'], []);

// class ExampleB(Namespace\To\Example $example)
$di->set('ExampleB', 'Namespace\To\Class2', ['example' => $di->get('Example')]);

$class = $di->make('ExampleB')->example->foo
// Return: bar
```