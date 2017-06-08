<?php
/**
 * Fast Create PHP - Dependency Injection Instance
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

		/**
		 * Function to execute on create instance
		 * @var mixed
		 */
		private $onCreateInstance;

		/**
		 * Function to execute before instantiating
		 * @var mixed
		 */
		private $beforeInstantiating;

		/**
		 * Function to execute after instantiating
		 * @var mixed
		 */
		private $afterInstantiating;

		/**
		 * Function to execute before execute method
		 * @var mixed
		 */
		private $beforeCallMethod;

		/**
		 * Function to execute after execute method
		 * @var mixed
		 */
		private $afterCallMethod;

		/**
		 * Method to create a instance of class
		 *
		 * @param string $class Class with namespace
		 * @param array $params Params to classe
		 * @return void
		 *
		 * @throws FcPhp\Di\Interfaces\ClassEmptyException
		 */
		public function __construct($class = null, array $params = [], $callbacks = null)
		{
			if(empty($class)){
				throw new ClassEmptyException();
			}
			$this->class = $class;
			$this->params = $params;
			if(count($callbacks) > 0){
				foreach($callbacks as $name => $callback){
					$this->$name = $callback;
				}
			}
			if(is_callable($this->onCreateInstance)){
				call_user_func($this->onCreateInstance, [$this->class, $this->params]);
			}
		}

		/**
		 * Method to create a instance of class
		 *
		 * @param string $class Class with namespace
		 * @param array $params Params to classe
		 * @return void
		 *
		 * @throws FcPhp\Di\Interfaces\MethodNotFoundException
		 */
		public function __call($method, array $args = [])
		{
			$this->createInstance();
			if(method_exists($this->instance, $method)){
				if(is_callable($this->beforeCallMethod)){
					call_user_func($this->beforeCallMethod, compact('method', 'args'));
				}
				$return = call_user_func_array([$this->instance, $method], $args);
				if(is_callable($this->afterCallMethod)){
					call_user_func($this->afterCallMethod, compact('method', 'args', 'return'));
				}
				return $return;
			}
			throw new MethodNotFoundException();
		}

		/**
		 * Method to return the instance of class
		 *
		 * @return mixed
		 */
		public function getClass()
		{
			$this->createInstance();
			return $this->instance;
		}

		/**
		 * Method to return the name of class
		 *
		 * @return mixed
		 */
		public function getClassName()
		{
			$this->createInstance();
			return get_class($this->instance);
		}

		/**
		 * Method to create new instance of class
		 *
		 * @return void
		 */
		private function createInstance()
		{
			if(is_null($this->instance)){
				if(is_callable($this->beforeInstantiating)){
					call_user_func($this->beforeInstantiating, [$this->class, $this->params]);
				}
				$class = new ReflectionClass($this->class);
				$this->instance = $class->newInstanceArgs($this->params);
				if(is_callable($this->afterInstantiating)){
					call_user_func($this->afterInstantiating, [$this->class, $this->params, $this->instance]);
				}
			}
		}
	}
}