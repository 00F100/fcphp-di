<?php

use FcPhp\Di\Container;
use FcPhp\Di\Interfaces\IContainer;

require_once('Mock.php');

class ContainerTest extends Mock
{
	public $container;
	// public $instance;
	// public $args;
	// public $setters;

	public function setUp()
	{
		$instance = $this->createMock('FcPhp\Di\Interfaces\IInstance');

		$instance
			->expects($this->any())
			->method('getArgs')
			->will($this->returnValue([]));

		$instance
			->expects($this->any())
			->method('getSetters')
			->will($this->returnValue([]));

		$instance
			->expects($this->any())
			->method('getNamespace')
			->will($this->returnValue('\MockCallbackParams'));

		$container = new Container($instance, ['value' => 'param'], ['setTest' => 'value']);
		$this->container = new Container($instance, ['value' => $container], ['setTest' => $container]);
	}

	public function testInstance()
	{
		$this->assertTrue($this->container instanceof IContainer);
	}

	public function testGetClass()
	{
		$this->assertTrue($this->container->getClass() instanceof \MockCallbackParams);
	}

}