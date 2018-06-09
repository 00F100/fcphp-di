<?php

namespace FcPhp\Di\Interfaces
{
	use FcPhp\Di\Interfaces\IDiFactory;
	use FcPhp\Di\Interfaces\IContainer;
	use FcPhp\Di\Interfaces\IContainerFactory;
	use FcPhp\Di\Interfaces\IInstanceFactory;

	interface IDi
	{
		public static function getInstance(IDiFactory $diFactory, IContainerFactory $containerFactory, IInstanceFactory $instanceFactory, bool $register = false);

		public function __construct(IContainerFactory $containerFactory, IInstanceFactory $instanceFactory, $register = false);

		public function set(string $id, string $namespace, array $args = [], array $setters = [], bool $singleton = true) :IDi;

		public function setNonSingleton(string $id, string $namespace, array $args = [], array $setters = []) :IDi;

		public function get(string $id, array $args = [], array $setters = []) :IContainer;

		public function getNonSingleton(string $id, array $args = [], array $setters = []) :IContainer;

		public function make(string $id, array $args = [], array $setters = []);

		public function event($eventName, object $callback = null) :void;

		public function beforeSet(string $id, string $namespace, array $args, array $setters, bool $singleton) :void;

		public function afterSet(string $id, string $namespace, array $args, array $setters, bool $singleton, ?IInstance $instance);

		public function beforeGet(string $id, array $args, array $setters) :void;

		public function afterGet(string $id, array $args, array $setters, ?IInstance $instance, ?IContainer $container) :void;

		public function beforeMake(string $id, array $args, array $setters) :void;

		public function afterMake(string $id, array $args, array $setters, ?IInstance $instance, ?IContainer $container, $class) :void;

		
	}
}