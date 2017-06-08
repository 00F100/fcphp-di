<?php
/**
 * Fast Create PHP - Dependency Injection Test
 *
 * @author João Moraes <joaomoraesbr@gmail.com>
 */
namespace FcPhp\Di\Test\Unit
{
	use FcPhp\Di\Instance;
	use PHPUnit\Framework\TestCase;
	use FcPhp\Di\Interfaces\IInstance;

	class InstanceTest extends TestCase
	{

		public function testContructSuccess()
		{
			$paramsTest = ['a', 'b', 'c'];
			$class = new Instance('FcPhp\Di\Test\Unit\Mocks\EntityTest', $paramsTest);
			$this->assertTrue($class instanceof IInstance);
			$params = $class->getParams();
			$this->assertTrue(count(array_diff($params, $paramsTest)) == 0);
		}

		public function testGetClassName()
		{
			$classNamespace = 'FcPhp\Di\Test\Unit\Mocks\EntityTest';
			$class = new Instance($classNamespace);
			$className = $class->getClassName();
			$this->assertEquals($classNamespace, $className);
		}

		public function testConfigureCallbacks()
		{
			$stringCreateInstance = 'abc';
			$stringBeforeInstantiating = '123';
			$stringAfterInstantiating = 'xyz';
			$stringBeforeCallMethod = '789';
			$stringAfterCallMethod = '456';
			$classNamespace = 'FcPhp\Di\Test\Unit\Mocks\EntityTest';
			$callbacks = [
				'onCreateInstance' => function() use (&$stringCreateInstance) {
					$stringCreateInstance = 'cba';
				},
				'beforeInstantiating' => function() use (&$stringBeforeInstantiating) {
					$stringBeforeInstantiating = '321';
				},
				'afterInstantiating' => function() use (&$stringAfterInstantiating) {
					$stringAfterInstantiating = 'zyx';
				},
				'beforeCallMethod' => function() use (&$stringBeforeCallMethod) {
					$stringBeforeCallMethod = '654';
				},
				'afterCallMethod' => function() use (&$stringAfterCallMethod) {
					$stringAfterCallMethod = '987';
				},
			];
			$class = new Instance($classNamespace, [], $callbacks);
			$this->assertEquals($stringCreateInstance, 'cba');
			$class->getParams();
			$this->assertEquals($stringBeforeInstantiating, '321');
			$this->assertEquals($stringAfterInstantiating, 'zyx');
			$this->assertEquals($stringBeforeCallMethod, '654');
			$this->assertEquals($stringAfterCallMethod, '987');
		}

		/**
	     * @expectedException     FcPhp\Di\Exceptions\ClassEmptyException
	     * @expectedExceptionCode 500
	     */
		public function testContructError()
		{
			$class = new Instance();
		}

		/**
	     * @expectedException     FcPhp\Di\Exceptions\MethodNotFoundException
	     * @expectedExceptionCode 404
	     */
		public function testMethodNotFound()
		{
			$class = new Instance('FcPhp\Di\Test\Unit\Mocks\EntityTest');
			$class->getMethodNotFound();
		}
	}
}