<?php

namespace FcPhp\Di\Interfaces
{
	use FcPhp\Di\Interfaces\IContainer;
	
	interface IContainerFactory
	{
		public function getInstance($instance, array $args, array $setters) :IContainer;
	}
}