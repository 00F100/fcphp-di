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