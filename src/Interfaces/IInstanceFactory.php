<?php

namespace FcPhp\Di\Interfaces
{
	interface IInstanceFactory
	{
		public function getInstance(string $namespace, array $args, array $setters, bool $singleton);
	}
}