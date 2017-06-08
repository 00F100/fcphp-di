<?php
/**
 * Fast Create PHP - Dependency Injection Instance Interface
 *
 * @author João Moraes <joaomoraesbr@gmail.com>
 */
namespace FcPhp\Di\Interfaces
{
	interface IInstance {
		public function __construct($class = null, array $params = []);
		public function __call($method, array $args = []);
		public function getClass();
		public function getClassName();
	}
}