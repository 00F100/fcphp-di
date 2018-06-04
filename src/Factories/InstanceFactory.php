<?php

namespace FcPhp\Di\Factories
{
	use FcPhp\Di\Instance;
	use FcPhp\Di\Interfaces\IInstanceFactory;

	class InstanceFactory implements IInstanceFactory
	{
		public function getInstance(string $namespace, array $args, array $setters, bool $singleton)
		{
			return new Instance($namespace, $args, $setters, $singleton);
		}
	}
}