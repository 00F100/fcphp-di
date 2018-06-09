<?php

use FcPhp\Di\Di;
use PHPUnit\Framework\TestCase;
use FcPhp\Di\Interfaces\IDi;
use FcPhp\Di\Interfaces\IContainer;
use FcPhp\Di\Interfaces\Instance;

require_once('Mock.php');

class DiTest extends Mock
{
	public $di;

	public function setUp()
	{
		$this->di = new Di($this->getContainerMock(), $this->getInstanceMock(), true);
	}

	public function testInstanceDi()
	{
		$this->assertTrue($this->di instanceof IDi);
	}

	public function testSetInstance()
	{
		$this->assertTrue($this->di->set('ClassTest', '/StdClass') instanceof IDi);
	}

	public function testSetNonSingletonInstance()
	{
		$this->assertTrue($this->di->setNonSingleton('ClassTest', '/StdClass') instanceof IDi);
	}

	public function testGetInstance()
	{
		$this->di->set('ClassTeste', '/StdClass');
		$this->assertTrue($this->di->get('ClassTeste') instanceof IContainer);
	}

	public function testGetNonSingletonInstance()
	{
		$this->di->set('ClassTeste', '/StdClass');
		$this->assertTrue($this->di->getNonSingleton('ClassTeste') instanceof IContainer);
	}

	public function testMake()
	{
		$this->di->set('ClassTeste', '/StdClass');
		$this->assertTrue($this->di->make('ClassTeste') instanceof \StdClass);
	}

	public function testGetInstanceOfDi()
	{
		$this->assertTrue(Di::getInstance($this->createMock('FcPhp\Di\Interfaces\IDiFactory'), $this->createMock('FcPhp\Di\Interfaces\IContainerFactory'), $this->createMock('FcPhp\Di\Interfaces\IInstanceFactory')) instanceof IDi);
	}

	public function testEvent()
	{
		$this->di->event([
			'beforeSet' => function(string $id, string $namespace, array $args, array $setters, bool $singleton) {
				$this->assertEquals($id, 'StdClass');
				$this->assertEquals($namespace, '\StdClass');
				$this->assertEquals($args, []);
				$this->assertEquals($setters, []);
				$this->assertEquals($singleton, true);
			},
			// 'afterSet' => function(string $id, string $namespace, array $args, array $setters, bool $singleton, ?IInstance $instance) {
			// 	$this->assertEquals($id, 'StdClass');
			// 	$this->assertEquals($namespace, '\StdClass');
			// 	$this->assertEquals($args, []);
			// 	$this->assertEquals($setters, []);
			// 	$this->assertEquals($singleton, true);
			// 	$this->assertTrue($instance instanceof IInstance);
			// },
		]);
		$this->di->set('StdClass', '\StdClass');
	}

	public function testMakeGetNonSingleton()
	{
		$di = new Di($this->getContainerMock(), $this->getInstanceNonSingletonMock());
		$di->set('StdClass', '\StdClass', [], [], false);
		$this->assertTrue($di->make('StdClass') instanceof \StdClass);
		$this->assertTrue($di->get('StdClass') instanceof IContainer);
	}

	/**
     * @expectedException FcPhp\Di\Exceptions\ClassBusy
     */
	// public function testGetNewParamsAfterInstance()
	// {
	// 	$this->di->set('StdClassT', '\StdClass');
	// 	$this->di->get('StdClassT')->getClass();
	// 	$this->di->make('StdClassT', ['param' => 'value']);
	// }

	/**
     * @expectedException FcPhp\Di\Exceptions\InstanceNotFound
     */
	public function testGetNonSingleInstanceError()
	{
		$this->di->getNonSingleton('test');
	}

	/**
     * @expectedException FcPhp\Di\Exceptions\InstanceNotFound
     */
	public function testGetInstanceError()
	{
		$this->di->get('test');
	}

	/**
     * @expectedException FcPhp\Di\Exceptions\InstanceNotFound
     */
	public function testMakeInstanceError()
	{
		$this->di->make('test');
	}
}
