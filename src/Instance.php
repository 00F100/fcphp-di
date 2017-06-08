<?php
/**
 * Fast Create PHP - Dependency Injection
 *
 * @author João Moraes <joaomoraesbr@gmail.com>
 */
namespace FcPhp\Di
{
	use Exception;
	use ReflectionClass;
	use FcPhp\Di\Interfaces\IInstance;
	use FcPhp\Di\Exceptions\ClassEmptyException;
	use FcPhp\Di\Exceptions\MethodNotFoundException;

	class Instance implements IInstance
	{
		private $class;
		private $params;
		private $instance = null;

		public function __construct($class = null, array $params = [])
		{
			if(empty($class)){
				throw new ClassEmptyException();
			}
			$this->class = $class;
			$this->params = $params;
		}

		public function __call($method, array $args = [])
		{
			$this->createInstance();
			if(method_exists($this->instance, $method)){
				return call_user_func_array([$this->instance, $method], $args);
			}
			throw new MethodNotFoundException();
		}

		public function getClass()
		{
			$this->createInstance();
			return $this->instance;
		}

		public function getClassName()
		{
			$this->createInstance();
			return get_class($this->instance);
		}

		private function createInstance()
		{
			if(is_null($this->instance)){
				$class = new ReflectionClass($this->class);
				$this->instance = $class->newInstanceArgs($this->params);
			}
		}
	}
}