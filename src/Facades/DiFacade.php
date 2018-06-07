<?php

namespace FcPHP\Di\Facades
{
	use FcPHP\Di\Di;

	use FcPHP\Di\Factories\DiFactory;
	use FcPHP\Di\Factories\ContainerFactory;
	use FcPHP\Di\Factories\InstanceFactory;
	
	use FcPHP\Di\Interfaces\IDi;
	use FcPhp\Di\Interfaces\IContainer;

	class DiFacade
	{
		/**
		 * @var FcPHP\Di\Facades\DiFacade
		 */
		private static $instance;

		/**
		 * Method to create and return instance of Di Facade
		 *
		 * @param bool $register Register Containers
		 * @return FcPHP\Di\Facades\DiFacade
		 */
		public static function getInstance(bool $register = false)
		{
			if(!self::$instance instanceof DiFacade) {
				self::$instance = new DiFacade(new Di(new DiFactory(), new ContainerFactory(), new InstanceFactory(), $register));
			}
			return self::$instance;
		}

		/**
		 * Method to construct instance
		 *
		 * @param FcPHP\Di\Interfaces\IDi $di Instance of Di
		 */
		public function __construct(IDi $di)
		{
			$this->di = $di;
		}

		/**
		 * Method to set new class
		 *
		 * @param string $id Identify of instance
		 * @param string $namespace Namespace of class
		 * @param array $args Args to construct class
		 * @return void
		 */
		public function set(string $id, string $namespace, array $args = [], array $setters = [], bool $singleton = true) :void
		{
			$this->di->set($id, $namespace, $args, $setters, $singleton);
		}

		/**
		 * Method to get instance of Container
		 *
		 * @param string $id Identify of instance
		 * @param array $args Args to construct instance
		 * @return FcPhp\Di\Interfaces\IContainer
		 */
		public function get(string $id, array $args = [], array $setters = []) :IContainer
		{
			return $this->di->get($id, $args, $setters);
		}

		/**
		 * Method to get new instance of Container
		 *
		 * @param string $id Identify of instance
		 * @param array $args Args to construct instance
		 * @return FcPhp\Di\Interfaces\IContainer
		 */
		public function getNonSingleton(string $id, array $args = [], array $setters = []) :IContainer
		{
			return $this->di->getNonSingleton($id, $args, $setters);
		}

		/**
		 * Method instance of class
		 *
		 * @param string $id Identify of instance
		 * @param array $args Args to construct instance
		 * @return mixed
		 */
		public function make(string $id, array $args = [], array $setters = [])
		{
			return $this->di->make($id, $args, $setters);
		}

		/**
		 * Method to execute clousure on event
		 *
		 * @param string|array $eventName Event Name, ex: beforeSet
		 * @param object $callback Function to execute
		 * @return void
		 */
		public function event($eventName, object $callback = null) :void
		{
			return $this->di->event($eventName, $callback);
		}
	}
}