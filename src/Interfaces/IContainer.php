<?php

namespace FcPhp\Di\Interfaces
{
	use FcPhp\Di\Interfaces\IInstance;
	
	interface IContainer
	{
		public function __construct(IInstance $instance, array $args = [], array $setters = []);

		public function getClass();
	}
}