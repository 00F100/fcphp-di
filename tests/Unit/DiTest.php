<?php

use FcPhp\Di\Di;
use FcPhp\Di\Factories\ContainerFactory;
use FcPhp\Di\Factories\InstanceFactory;
use PHPUnit\Framework\TestCase;
use FcPhp\Di\Interfaces\IDi;
use FcPhp\Di\Interfaces\IContainer;
use FcPhp\Di\Interfaces\IInstance;

require_once('Mock.php');

class DiTest extends Mock
{
	public $di;

	public function setUp()
	{
		$this->di = new Di($this->getContainerMock(), $this->getInstanceMock(), true);
	}

	public function testEvents()
	{
		$this->di->event([
			'beforeSet' => function(string $id, string $namespace, array $args, array $setters, bool $singleton) {
				$this->assertEquals($id, 'MockCallback');
				$this->assertEquals($namespace, 'MockCallback');
				$this->assertEquals($args, []);
				$this->assertEquals($setters, []);
				$this->assertEquals($singleton, true);
			},
			'afterSet' => function(string $id, string $namespace, array $args, array $setters, bool $singleton, ?IInstance $instance) {
				$this->assertEquals($id, 'MockCallback');
				$this->assertEquals($namespace, 'MockCallback');
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
				$this->assertTrue($class instanceof MockCallback);
			},
            'beforeOverwrite' => function(string $id, string $namespace, array $args, array $setters, bool $singleton) {
                $this->assertEquals($id, 'MockCallback');
                $this->assertEquals($namespace, 'MockCallback');
                $this->assertEquals($args, []);
                $this->assertEquals($setters, []);
                $this->assertEquals($singleton, true);
            },
            'afterOverwrite' => function(string $id, string $namespace, array $args, array $setters, bool $singleton, ?IInstance $instance) {
                $this->assertEquals($id, 'MockCallback');
                $this->assertEquals($namespace, 'MockCallback');
                $this->assertEquals($args, []);
                $this->assertEquals($setters, []);
                $this->assertEquals($singleton, true);
                $this->assertTrue($instance instanceof IInstance);
            }
		]);
		$this->di->event('afterGet', function(string $id, array $args, array $setters, ?IInstance $instance, ?IContainer $container) {
			$this->assertEquals($id, 'MockCallback');
			$this->assertEquals($args, []);
			$this->assertEquals($setters, []);
			$this->assertTrue($instance instanceof IInstance);
			$this->assertTrue($container instanceof IContainer);
		});
        $this->di->set('MockCallback', 'MockCallback');
		$this->di->overwrite('MockCallback', 'MockCallback');
		$this->assertTrue($this->di->get('MockCallback') instanceof IContainer);
		$this->assertTrue($this->di->make('MockCallback') instanceof MockCallback);
	}

	public function testInstanceDi()
	{
		$this->assertTrue($this->di instanceof IDi);
	}

	public function testSetInstance()
	{
		$this->assertTrue($this->di->set('ClassTest', 'MockCallback') instanceof IDi);
	}

	public function testSetNonSingletonInstance()
	{
		$this->assertTrue($this->di->setNonSingleton('ClassTest', 'MockCallback') instanceof IDi);
	}

	public function testGetInstance()
	{
		$this->di->set('ClassTeste', 'MockCallback');
		$this->assertTrue($this->di->get('ClassTeste') instanceof IContainer);
	}

	public function testGetNonSingletonInstance()
	{
		$this->di->set('ClassTeste', 'MockCallback');
		$this->assertTrue($this->di->getNonSingleton('ClassTeste') instanceof IContainer);
	}

	public function testMake()
	{
		$this->di->set('ClassTeste', 'MockCallback');
		$this->assertTrue($this->di->make('ClassTeste') instanceof MockCallback);
	}

	public function testGetInstanceOfDi()
	{
		$this->assertTrue(Di::getInstance($this->createMock('FcPhp\Di\Interfaces\IDiFactory'), $this->createMock('FcPhp\Di\Interfaces\IContainerFactory'), $this->createMock('FcPhp\Di\Interfaces\IInstanceFactory')) instanceof IDi);
	}

	public function testMakeGetNonSingleton()
	{
		$di = new Di($this->getContainerMock(), $this->getInstanceNonSingletonMock());
		$di->set('MockCallback', 'MockCallback', [], [], false);
		$this->assertTrue($di->make('MockCallback') instanceof MockCallback);
		$this->assertTrue($di->get('MockCallback') instanceof IContainer);
	}

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

	/**
     * @expectedException FcPhp\Di\Exceptions\ClassBusy
     */
	public function testMakeInstanceErrorClassBosy()
	{
		$this->di->set('ClassTeste', 'MockCallback');
		$class = $this->di->make('ClassTeste');
		$this->assertTrue($class instanceof MockCallback);
		$class->param = 'value1';
		$this->di->get('ClassTeste', [], ['setFoo' => 'bar'])->getClass();
	}

	public function testSetSetters()
	{
		$this->di->set('ClassMock', 'MockCallbackParams');
		$this->assertTrue($this->di->setter('ClassMock', ['setTest' => 'value']) instanceof IDi);
	}

    public function testHas()
    {
        $this->di->set('ClassMock', 'MockCallbackParams');
        $this->assertTrue($this->di->has('ClassMock'));
    }

    public function testOverwrite()
    {
        $this->assertInstanceOf(IDi::class, $this->di->overwrite('ClassMock', 'MockCallbackParams'));
    }
}
