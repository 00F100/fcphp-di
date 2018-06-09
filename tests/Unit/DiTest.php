<?php

use FcPhp\Di\Di;
use FcPhp\Di\Exceptions\InstanceNotFound;
use FcPHP\Di\Test\Unit\Mock\ClassTest;
use PHPUnit\Framework\TestCase;
use FcPhp\Di\Interfaces\IContainer;

use FcPhp\Di\Factories\ContainerFactory;
use FcPhp\Di\Factories\DiFactory;
use FcPhp\Di\Factories\InstanceFactory;

use FcPhp\Di\Interfaces\IInstance;

class DiTest extends TestCase
{
	public $di;

	public function setUp()
	{

		if(!$this->di instanceof Di) {

			$instanceFactory = $this
				->createMock('\FcPhp\Di\Interfaces\IInstanceFactory');

			$instanceFactory
				->expects($this->any())
				->method('getInstance')
				->will($this->returnCallback(function(string $namespace, array $args = [], array $setters = [], bool $singleton = true) {

					$instance = $this
						->createMock('\FcPhp\Di\Interfaces\IInstance');

					$instance
						->expects($this->any())
						->method('getNamespace')
						->will($this->returnValue($namespace));
					
					$instance
						->expects($this->any())
						->method('getArgs')
						->will($this->returnValue($args));
					
					$instance
						->expects($this->any())
						->method('getSetters')
						->will($this->returnValue($setters));

					$instance
						->expects($this->any())
						->method('getIsSingleton')
						->will($this->returnValue($singleton));

					return $instance;
				}));

			$this->di = new Di(new ContainerFactory(), $instanceFactory, true);
		}

		// $this->di->event([
		// 	'beforeSet' => function(string $id, string $namespace, array $args, array $setters, bool $singleton) {

		// 	},
		// 	'afterSet' => function(string $id, string $namespace, array $args, array $setters, bool $singleton, IInstance $instance) {

		// 	},
		// 	'beforeGet' => function(string $id, array $args, array $setters) {

		// 	},
		// 	'afterGet' => function(string $id, array $args, array $setters, IInstance $instance, IContainer $container) {

		// 	},
		// 	'beforeMake' => function(string $id, array $args, array $setters) {

		// 	},
		// 	'afterMake' => function(string $id, array $args, array $setters, IInstance $instance, IContainer $container, $class) {

		// 	}
		// ]);

		// $this->di->event('beforeMake', function() {

		// });

	}

	public function testConstruct()
	{
		$this->assertTrue($this->di instanceof Di);
	}

	public function testSetGetContainer()
	{
		$args = [
			'param1' => 'param1value'
		];
		$this->di->set('ClassTest', 'FcPHP\Di\Test\Unit\Mock\ClassTest', $args);
		$this->di->setNonSingleton('ClassTestNonSingleton', 'FcPHP\Di\Test\Unit\Mock\ClassTest', $args);
		$this->assertTrue($this->di->get('ClassTest') instanceof IContainer);
	}

	public function testSetters()
	{
		$valueParam = 'valueParam1';
		$args = [
			'param1' => 'testeParam1'
		];
		$setters = [
			'setValue' => $valueParam
		];
		$this->di->set('testSetters', 'FcPHP\Di\Test\Unit\Mock\ClassTest', $args, $setters);
		$this->assertEquals($this->di->make('testSetters')->getValue(), $valueParam);
	}

	public function testSetteronGetNonSingleton()
	{
		$this->di->set('ClassTest', 'FcPHP\Di\Test\Unit\Mock\ClassTest');
		$class = $this->di->getNonSingleton('ClassTest', [], ['setValue' => 'param111']);
		$this->assertEquals($class->getClass()->getValue(), 'param111');
	}

	/**
     * @expectedException FcPhp\Di\Exceptions\ClassBusy
     */
	public function testSetteronGetClassBusy()
	{
		$this->di->set('ClassTest', 'FcPHP\Di\Test\Unit\Mock\ClassTest');
		$class = $this->di->get('ClassTest', [], ['setValue' => 'param111']);
		$this->assertEquals($class->getClass()->getValue(), 'param111');
		$class = $this->di->get('ClassTest', [], ['setValue' => 'param222']);
	}

	public function testSetGetNonSingletonContainer()
	{
		$this->di->set('ClassTest', 'FcPHP\Di\Test\Unit\Mock\ClassTest');
		$this->assertTrue($this->di->getNonSingleton('ClassTest') instanceof IContainer);
	}

	public function testMakeSingletonClass()
	{
		$value = time();
		$this->di->set('ClassTest', 'FcPHP\Di\Test\Unit\Mock\ClassTest');
		$class = $this->di->make('ClassTest');
		$this->assertTrue($class instanceof ClassTest);
		$class->setValue($value);
		$classTestSingleton = $this->di->make('ClassTest');
		$this->AssertEquals($classTestSingleton->getValue(), $value);
		$param1 = 'param1change';
		$classNewArgs = $this->di->make('ClassTest', ['param1' => $param1]);
		$this->assertEquals($classNewArgs->getParam1(), $param1);
		$this->assertEquals($classNewArgs->getValue(), null);
		$value2 = 'test';
		$classNewArgs->setValue($value2);
		$classNewArgs2 = $this->di->make('ClassTest', ['param1' => $param1]);
		$this->assertEquals($classNewArgs2->getParam1(), $param1);
		$this->assertEquals($classNewArgs2->getValue(), $value2);
	}

	public function testMakeNonSingletonClass()
	{
		$this->di->setNonSingleton('ClassTestNonSingleton', 'FcPHP\Di\Test\Unit\Mock\ClassTest');
		$instance = $this->di->make('ClassTestNonSingleton');
		$instance->setValue('value');
		$instance2 = $this->di->make('ClassTestNonSingleton');
		$this->assertEquals($instance2->getValue(), null);
	}

	public function testContainerGenerate()
	{
		$value = 'value';
		$this->di->set('ClassTest', 'FcPHP\Di\Test\Unit\Mock\ClassTest');
		$container = $this->di->get('ClassTest');
		$this->assertTrue($container instanceof IContainer);
		$this->assertTrue($container->getClass() instanceof ClassTest);
		$container->getClass()->setValue($value);
		$this->assertEquals($container->getClass()->getValue(), $value);
		$container2 = $this->di->get('ClassTest');
		$this->assertEquals($container2->getClass()->getValue(), $value);
	}

	public function testContainerGeneratedNonSingleton()
	{
		$value = 'value';
		$this->di->setNonSingleton('ClassTestNonSingleton', 'FcPHP\Di\Test\Unit\Mock\ClassTest');
		$container = $this->di->get('ClassTestNonSingleton');
		$this->assertTrue($container instanceof IContainer);
		$this->assertTrue($container->getClass() instanceof ClassTest);
		$container->getClass()->setValue($value);
		$this->assertEquals($container->getClass()->getValue(), $value);
		$container2 = $this->di->get('ClassTestNonSingleton');
		$this->assertEquals($container2->getClass()->getValue(), null);
	}

	public function testInjectDependency()
	{
		$this->di->set('ClassTest', 'FcPHP\Di\Test\Unit\Mock\ClassTest');
		$class = $this->di->make('ClassTest', ['param1' => $this->di->get('ClassTest', ['param1' => $this->di->get('ClassTest')])]);
		$this->assertTrue($class->getParam1() instanceof ClassTest);
		$this->assertTrue($class->getParam1()->getParam1() instanceof ClassTest);
	}

	/**
     * @expectedException FcPhp\Di\Exceptions\InstanceNotFound
     */
	public function testInstancesNotFound()
	{
		$this->di->get('test');
	}

	/**
     * @expectedException FcPhp\Di\Exceptions\InstanceNotFound
     */
	public function testInstancesMakeNotFound()
	{
		$this->di->make('test');
	}

	/**
     * @expectedException FcPhp\Di\Exceptions\InstanceNotFound
     */
	public function testInstancesNewNotFound()
	{
		$this->di->getNonSingleton('test');
	}
}
