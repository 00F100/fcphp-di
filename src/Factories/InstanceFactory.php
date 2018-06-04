<?php

namespace FcPhp\Di\Factories
{
	use FcPhp\Di\Instance;
	use FcPhp\Di\Interfaces\IInstanceFactory;

	class InstanceFactory implements IInstanceFactory
	{
		public function getInstance($namespace, $args, $singleton)
		{
			return new Instance($namespace, $args, $singleton);
		}
	}
}