<?php

use FcPhp\Di\Container;
use FcPhp\Di\Interfaces\IContainer;

require_once('Mock.php');

class ContainerTest extends Mock
{
    private $instance;
	private $container;

	public function setUp()
	{
		$this->instance = $this->createMock('FcPhp\Di\Interfaces\IInstance');

		$this->instance
			->expects($this->any())
			->method('getArgs')
			->will($this->returnValue([]));

		$this->instance
			->expects($this->any())
			->method('getSetters')
			->will($this->returnValue([]));

		$this->instance
			->expects($this->any())
			->method('getNamespace')
			->will($this->returnValue('\MockCallbackParams'));

		$container = new Container($this->instance, ['value' => 'param'], ['setTest' => 'value']);
		$this->container = new Container($this->instance, ['value' => $container], ['setTest' => $container]);
	}

	public function testInstance()
	{
		$this->assertTrue($this->container instanceof IContainer);
	}

	public function testGetClass()
	{
		$this->assertTrue($this->container->getClass() instanceof \MockCallbackParams);
	}

    public function testArgsNull()
    {
        $container = new Container($this->instance);
        $class = $container->getClass();
        $this->assertNull($class->GetArgs());
    }

}
