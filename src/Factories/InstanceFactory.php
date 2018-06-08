<?php

namespace FcPhp\Di\Factories
{
	use FcPhp\Di\Instance;
	use FcPhp\Di\Interfaces\IInstanceFactory;

	class InstanceFactory implements IInstanceFactory
	{
		/**
		 * Method to return new isntance of Instance (??!?@##?!)
		 *
		 * @param string $namespace Namespace of new class
		 * @param array $args Args to construct new class
		 * @param array $setters Setters to class
		 * @param bool $singleton If is singleton
		 */
		public function getInstance(string $namespace, array $args, array $setters, bool $singleton)
		{
			return new Instance($namespace, $args, $setters, $singleton);
		}
	}
}