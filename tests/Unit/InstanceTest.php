<?php

use FcPhp\Di\Instance;
// use FcPhp\Di\Factories\ContainerFactory;
// use FcPhp\Di\Factories\InstanceFactory;
// use PHPUnit\Framework\TestCase;
// use FcPhp\Di\Interfaces\IDi;
// use FcPhp\Di\Interfaces\IContainer;
use FcPhp\Di\Interfaces\IInstance;

require_once('Mock.php');

class InstanceTest extends Mock
{
	public $namespace;
	public $args = [];
	public $setters;
	public $instance;

	public function setUp()
	{
		$this->namespace = '\MockCallback';
		$this->args = ['foo' => 'bar'];
		$this->setters = ['setFoo' => 'val'];
		$this->instance = new Instance($this->namespace, $this->args, $this->setters, true);
	}

	public function testInstance()
	{
		$this->assertTrue($this->instance instanceof IInstance);
	}

	public function testGetNamespace()
	{
		$this->assertEquals($this->instance->getNamespace(), $this->namespace);
	}

	public function testGetArgs()
	{
		$this->assertEquals($this->instance->getArgs(), $this->args);
	}

	public function testGetSetters()
	{
		$this->assertEquals($this->instance->getSetters(), $this->setters);
	}

	public function testIsNonSinglet()
	{
		$this->instance->isNonSingleton();
		$this->assertTrue(!$this->instance->getIsSingleton());
	}

}