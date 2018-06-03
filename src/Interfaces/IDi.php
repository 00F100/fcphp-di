<?php

namespace FcPhp\Di\Interfaces
{
	interface IDi
	{
		public static function getInstance();
		public function set(string $id, string $namespace, array $args = [], bool $singleton = true);
		public function setNonSingleton(string $id, string $namespace, array $args = []);
		public function getNonSingleton(string $id, array $args = []);
		public function get(string $id, array $args = []);
		public function getInstances();
	}
}