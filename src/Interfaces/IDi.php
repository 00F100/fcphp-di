<?php
/**
 * Fast Create PHP - Dependency Injection Interface
 *
 * @author João Moraes <joaomoraesbr@gmail.com>
 */
namespace FcPhp\Di\Interfaces
{
	interface IDi {
		public function set($alias, array $args);
		public function get($alias);
		public function getNew($alias);
		public function getNewArgs($alias, array $args);
	}
}