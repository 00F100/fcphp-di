<?php

namespace FcPHP\Di\Test\Unit
{
	use FcPhp\Di\Di;
	use FcPhp\Di\Exceptions\InstanceNotFound;
	use FcPHP\Di\Test\Unit\Mock\ClassTest;
	use PHPUnit\Framework\TestCase;
	use FcPhp\Di\Interfaces\IContainer;

	class DiTest extends TestCase
	{
		public $di;

		public function setUp()
		{
			if(!$this->di instanceof Di) {
				$this->di = Di::getInstance();
			}
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

		public function testSetGetNonSingletonContainer()
		{
			$this->assertTrue($this->di->getNonSingleton('ClassTest') instanceof IContainer);
		}

		public function testInstancesList()
		{
			$this->assertTrue(count($this->di->getInstances()) > 0);
		}

		public function testMakeSingletonClass()
		{
			$value = time();
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
			$instance = $this->di->make('ClassTestNonSingleton');
			$instance->setValue('value');
			$instance2 = $this->di->make('ClassTestNonSingleton');
			$this->assertEquals($instance2->getValue(), null);
		}

		public function testContainerGenerate()
		{
			$value = 'value';
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
			$container = $this->di->get('ClassTestNonSingleton');
			$this->assertTrue($container instanceof IContainer);
			$this->assertTrue($container->getClass() instanceof ClassTest);
			$container->getClass()->setValue($value);
			$this->assertEquals($container->getClass()->getValue(), $value);
			$container2 = $this->di->get('ClassTestNonSingleton');
			$this->assertEquals($container2->getClass()->getValue(), null);
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
}