<?php

namespace FcPhp\Di
{
	use FcPhp\Di\Di;
	use FcPhp\Di\Interfaces\IInstance;
	use FcPhp\Di\Interfaces\IContainer;
	use ReflectionClass;
	use ReflectionMethod;

	class Container implements IContainer
	{
		private $instance;
		private $args;
		private $classInstance = false;

		public function __construct(IInstance $instance, array $args = [])
		{
			$this->instance = $instance;
			$this->args = $args;
		}

		public function getClass()
		{
			if(!$this->classInstance) {
				$args = array_merge($this->instance->getArgs(), $this->args);
				foreach($args as $index => $value) {
					if($value instanceof IContainer) {
						$args[$index] = $value->getClass();
					}
				}
				$method = new ReflectionMethod($this->instance->getNamespace(), '__construct');
				$parameters = $method->getParameters();
				$params = [];
				if(count($parameters) > 0) {
					foreach($parameters as $param) {
						$params[] = $param->getName();
					}
				}
				$argsClass = [];
				if(count($params) > 0) {
					foreach($params as $param) {
						if(isset($args[$param])) {
							$argsClass[] = $args[$param];
						}else{
							$argsClass[] = null;
						}
					}
				}
				$class = new ReflectionClass($this->instance->getNamespace());
				$instance = $class->newInstanceArgs($argsClass);
				$this->register($instance);
			}

			// process setters

			return $this->classInstance;
		}

		private function register($instance) :IContainer
		{
			$this->classInstance = $instance;
			return $this;
		}
	}
}