<?php

namespace FcPhp\Di
{
	use FcPhp\Di\Di;
	use FcPhp\Di\Interfaces\IInstance;
	use FcPhp\Di\Interfaces\IContainer;
	use ReflectionClass;

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
				$class = new ReflectionClass($this->instance->getNamespace());
				$instance = $class->newInstanceArgs(array_merge($this->instance->getArgs(), $this->args));
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