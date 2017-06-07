<?php
/**
 * Fast Create PHP - Dependency Injection Test
 *
 * @author João Moraes <joaomoraesbr@gmail.com>
 */
namespace FcPhp\Di\Test
{
	use PHPUnit\Framework\TestCase;
	use FcPhp\Di\Di;
	use FcPhp\Di\Interfaces\IDi;

	class DiTest extends TestCase
	{
		const ITEM_NO_PARAM=1;
		const ITEM_PARAM=2;
		const ITEM_PARAM_DEPENDS=3;
		const ITEM_PARAM_FAKE_POPULATE=4;

		const ITEM_FAKE_ENTITY_TEST = 4;
		const ITEM_FAKE_CLASS_POPULATE = 5;

		private $instance;
		private $alias = [
			self::ITEM_NO_PARAM => 'new-mock',
			self::ITEM_PARAM => 'new-mock-param',
			self::ITEM_PARAM_DEPENDS => 'new-mock-param-depends',
			self::ITEM_PARAM_FAKE_POPULATE => 'new-mock-param-fake-populate',
		];
		private $classTests = [
			self::ITEM_FAKE_ENTITY_TEST => 'FcPhp\Di\Test\Mocks\EntityTest',
			self::ITEM_FAKE_CLASS_POPULATE => 'FcPhp\Di\Test\Mocks\ClassPopulateTest',
		];

		/**
		 * Set up tests
		 */
		public function setUp()
		{
		}

		public function testSetClassWithoutParamsAndDepends()
		{
			$alias = $this->alias[self::ITEM_NO_PARAM];
			$className = $this->classTests[self::ITEM_FAKE_ENTITY_TEST];
			$args = [
				'class' => $className,
				'singleton' => false,
				'params' => [],
				'depends' => []
			];
			$instance = new Di();
			$this->assertTrue($instance->set($alias, $args));
			$class = $instance->get($alias);
			$this->assertEquals($className, get_class($class));
		}

		public function testSetClassWithParamsNoDependsSingletonFalse()
		{
			$alias = $this->alias[self::ITEM_PARAM];
			$className = $this->classTests[self::ITEM_FAKE_ENTITY_TEST];
			$args = [
				'class' => $className,
				'singleton' => false,
				'params' => [
					'param 1',
					'param 2',
					'param 3'
				],
				'depends' => []
			];
			$instance = new Di();
			$this->assertTrue($instance->set($alias, $args));
			$class = $this->get($alias);
			$this->assertEquals($className, get_class($class));
			$params = $class->getParams();
			$this->assertEquals('param 1', $params[0]);
			$this->assertEquals('param 2', $params[1]);
			$this->assertEquals('param 3', $params[2]);
		}

		public function testSetClassWithParamsAndDepends()
		{
			$alias1 = $this->alias[self::ITEM_PARAM_DEPENDS];
			$className1 = $this->classTests[self::ITEM_FAKE_ENTITY_TEST];
			$alias2 = $this->alias[self::ITEM_PARAM_FAKE_POPULATE];
			$className2 = $this->classTests[self::ITEM_FAKE_CLASS_POPULATE];

			$argsFake1 = [
				'class' => $className1,
				'singleton' => false,
				'params' => [
					'param 1',
					'param 2',
					'param 3'
				],
				'depends' => [
					$className2
				]
			];
			$instance = new Di();
			$this->assertTrue($instance->set($alias1, $argsFake1));
			$argsFake2 = [
				'class' => $className2,
				'singleton' => false,
				'params' => [],
				'depends' => []
			];
			$this->assertTrue($instance->set($alias2, $argsFake2));
			$class1 = $this->get($alias1);
			$this->assertEquals($className1, get_class($class1));
			$class2 = $this->get($alias2);
			$this->assertEquals($className2, get_class($class2));
		}

		public function testGetNewInstance()
		{
			$alias = $this->alias[self::ITEM_NO_PARAM];
			$className = $this->classTests[self::ITEM_FAKE_ENTITY_TEST];
			$args = [
				'class' => $className,
				'singleton' => false,
				'params' => [],
				'depends' => []
			];
			$instance = new Di();
			$this->assertTrue($instance->set($alias, $args));
			$class = $instance->getNew($alias);
			$this->assertEquals($className, get_class($class));
		}

		public function testGetNewArgsInstance()
		{
			$alias = $this->alias[self::ITEM_NO_PARAM];
			$className = $this->classTests[self::ITEM_FAKE_ENTITY_TEST];
			$args = [
				'class' => $className,
				'singleton' => false,
				'params' => [],
				'depends' => []
			];
			$newArgs = [
				'param 1',
				'param 2',
				'param 3'
			];
			$instance = new Di();
			$this->assertTrue($instance->set($alias, $args));
			$class = $instance->getNewArgs($alias, $newArgs);
			$this->assertEquals($className, get_class($class));
			$params = $class->getParams();
			$this->assertEquals('param 1', $params[0]);
			$this->assertEquals('param 2', $params[1]);
			$this->assertEquals('param 3', $params[2]);
		}
	}
}