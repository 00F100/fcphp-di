<?php

namespace FcPHP\Di\Facades
{
	use FcPHP\Di\Di;

	class Di
	{
		private $di;

		public function __construct() :void
		{
			$this->di = Di::getInstance();
		}

		public function set(string $id, string $namespace, ?array $args) :void
		{
			$this->di->set($id, $namespace, $args);
		}

		public function get(string $id, ?array $args)
		{
			return $this->di->get($id, $args);
		}

		public function getNew(string $id, ?array $args)
		{
			return $this->di->getNew($id, $args);
		}

		public function getInstances() :array
		{
			return $this->di->getInstances();
		}
	}
}