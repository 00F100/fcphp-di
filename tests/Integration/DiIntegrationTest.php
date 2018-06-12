<?php

use FcPhp\Di\Di;
use FcPhp\Di\Interfaces\IDi;
use FcPhp\Di\Factories\DiFactory;
use FcPhp\Di\Factories\ContainerFactory;
use FcPhp\Di\Factories\InstanceFactory;
use FcPhp\Di\Interfaces\Icontainer;
use FcPhp\Di\Interfaces\IInstance;
use FcPhp\Di\Facades\DiFacade;

require_once(__DIR__ . '/../Unit/Mock.php');

class DiIntegrationTest extends Mock
{
	public function setUp()
	{
		$this->di = DiFacade::getInstance(true);
	}

	public function testCreate()
	{
		$this->assertTrue(Di::getInstance(new DiFactory(), new ContainerFactory(), new InstanceFactory(), false) instanceof IDi);
	}

	public function testIntance()
	{
		$this->assertTrue($this->di instanceof DiFacade);
	}

	public function testSetGet()
	{
		$this->di->set('TestClass', '\MockCallback');
		$this->assertTrue($this->di->get('TestClass') instanceof IContainer);
	}

	public function testMake()
	{
		$this->di->set('TestClass', '\MockCallback');
		$this->assertTrue($this->di->make('TestClass') instanceof \MockCallback);
	}

	public function testGetNonSingleton()
	{
		$this->di->set('TestClass', '\MockCallback');
		$this->assertTrue($this->di->getNonSingleton('TestClass')->getClass() instanceof \MockCallback);
	}

	public function testEvent()
	{
		$this->di->event('beforeSet', function() {
			$this->assertTrue(true);
		});
		$this->di->set('TestClass', '\MockCallback');
	}

	public function testSetNonSinglet()
	{	
		$this->di->setNonSingleton('TestClass', '\MockCallback');
	}

	public function testInjectDependency()
	{
		$this->di->set('TestClass1', '\MockCallbackParams', ['value' => 'content'], ['setTest' => 'test'], false);
		$this->di->set('TestClass2', '\MockCallbackParams', ['value' => $this->di->get('TestClass1')], ['setTest' => $this->di->get('TestClass1')]);
		$this->assertTrue($this->di->make('TestClass2') instanceof \MockCallbackParams);
		$this->assertTrue($this->di->make('TestClass2')->args instanceof \MockCallbackParams);
	}

	/**
     * @expectedException FcPhp\Di\Exceptions\InstanceNotFound
     */
	public function testGetInstanceNotFound()
	{
		$this->di->get('test');
	}

	/**
     * @expectedException FcPhp\Di\Exceptions\InstanceNotFound
     */
	public function testGetNonSingletonInstanceNotFound()
	{
		$this->di->getNonSingleton('test');
	}

	/**
     * @expectedException FcPhp\Di\Exceptions\InstanceNotFound
     */
	public function testMakeInstanceNotFound()
	{
		$this->di->make('test');
	}

	public function testGetMakeNonSingleton()
	{
		$this->di->setNonSingleton('ClassLocal', '\MockCallback');
		$this->assertTrue($this->di->make('ClassLocal') instanceof \MockCallback);
	}

	/**
     * @expectedException FcPhp\Di\Exceptions\ClassBusy
     */
	public function testGetChangeValuesAfterInstance()
	{
		$this->di->set('ClassLocalChange', '\MockCallbackParams', ['value' => 'content']);
		$instance = $this->di->make('ClassLocalChange');
		$this->di->get('ClassLocalChange', [], ['setTest' => 'value']);
	}

	public function testEvents()
	{
		$this->di->event([
			'beforeSet' => function(string $id, string $namespace, array $args, array $setters, bool $singleton) {
				$this->assertEquals($id, 'MockCallback');
				$this->assertEquals($namespace, '\MockCallback');
				$this->assertEquals($args, []);
				$this->assertEquals($setters, []);
				$this->assertEquals($singleton, true);
			},
			'afterSet' => function(string $id, string $namespace, array $args, array $setters, bool $singleton, ?IInstance $instance) {
				$this->assertEquals($id, 'MockCallback');
				$this->assertEquals($namespace, '\MockCallback');
				$this->assertEquals($args, []);
				$this->assertEquals($setters, []);
				$this->assertEquals($singleton, true);
				$this->assertTrue($instance instanceof IInstance);
			},
			'beforeGet' => function(string $id, array $args, array $setters) {
				$this->assertEquals($id, 'MockCallback');
				$this->assertEquals($args, []);
				$this->assertEquals($setters, []);
			},
			'afterGet' => function(string $id, array $args, array $setters, ?IInstance $instance, ?IContainer $container) {
				$this->assertEquals($id, 'MockCallback');
				$this->assertEquals($args, []);
				$this->assertEquals($setters, []);
				$this->assertTrue($instance instanceof IInstance);
				$this->assertTrue($container instanceof IContainer);
			},
			'beforeMake' => function(string $id, array $args, array $setters) {
				$this->assertEquals($id, 'MockCallback');
				$this->assertEquals($args, []);
				$this->assertEquals($setters, []);
			},
			'afterMake' => function(string $id, array $args, array $setters, ?IInstance $instance, ?IContainer $container, $class) {
				$this->assertEquals($id, 'MockCallback');
				$this->assertEquals($args, []);
				$this->assertEquals($setters, []);
				$this->assertTrue($instance instanceof IInstance);
				$this->assertTrue($container instanceof IContainer);
				$this->assertTrue($class instanceof \MockCallback);
			},
		]);
		$this->di->event('afterGet', function(string $id, array $args, array $setters, ?IInstance $instance, ?IContainer $container) {
			$this->assertEquals($id, 'MockCallback');
			$this->assertEquals($args, []);
			$this->assertEquals($setters, []);
			$this->assertTrue($instance instanceof IInstance);
			$this->assertTrue($container instanceof IContainer);
		});
		$this->di->set('MockCallback', '\MockCallback');
		$this->assertTrue($this->di->get('MockCallback') instanceof IContainer);
		$this->assertTrue($this->di->make('MockCallback') instanceof \MockCallback);
	}

	public function testSettersMethod()
	{
		$this->di->set('MockCallback', '\MockCallback');
		$this->di->setter('MockCallback', ['setTest' => 'value']);

	}
}