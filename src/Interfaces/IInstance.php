<?php

namespace FcPhp\Di\Interfaces
{
	use FcPhp\Di\Interfaces\IInstance;
	
	interface IInstance
	{
		public function __construct(string $namespace, array $args, array $setters, bool $singleton);

		public function getNamespace() :string;

		public function getArgs() :array;

		public function isNonSingleton() :IInstance;

		public function getIsSingleton() :bool;

		public function getSetters() :array;
	}
}