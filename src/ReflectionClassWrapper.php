<?php
/**
 * Fast Create PHP - Reflection Class Wrapper
 *
 * @author João Moraes <joaomoraesbr@gmail.com>
 */
namespace FcPhp\Di
{
	use ReflectionClass;
	use ReflectionException;
	use FcPhp\Di\Exceptions\FailToLoadClassException;

	class ReflectionClassWrapper
	{

		/**
		 * Method to create a instance of class
		 *
		 * @param string $alias Alias of class
		 * @param array $params New params to classe
		 * @return mixed
		 */
		protected function createInstance($class, $params)
		{
			$class = new ReflectionClass($class);
			$instance = $class->newInstanceArgs($params);
			return $instance;
		}
	}
}