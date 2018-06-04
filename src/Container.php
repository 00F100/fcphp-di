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
		private $setters;
		private $classInstance = false;
		private $lock = false;

		public function __construct(IInstance $instance, array $args = [], array $setters = [])
		{
			$this->instance = $instance;
			$this->args = $args;
			$this->setters = $setters;
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
				$setters = array_merge($this->instance->getSetters(), $this->setters);
				if(count($setters) > 0) {
					foreach($setters as $method => $value) {
						if(method_exists($instance, $method)) {
							if(!is_array($value))
								$value = [$value];

							call_user_func_array([$instance, $method], $value);
						}
					}
				}
				$this->register($instance);
				$this->lock = true;
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