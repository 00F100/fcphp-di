<?php

namespace FcPhp\Di\Factories
{
	use FcPhp\Di\Container;
	use FcPhp\Di\Interfaces\IContainerFactory;

	class ContainerFactory implements IContainerFactory
	{
		public function getInstance($instance, $args)
		{
			return new Container($instance, $args);
		}
	}
}