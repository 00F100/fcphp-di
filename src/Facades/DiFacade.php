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
		public function set(string $id, string $namespace, array $args = []) :void
		{
			$this->di->set($id, $namespace, $args);
		}

		/**
		 * Method to get instance of Container
		 *
		 * @param string $id Identify of instance
		 * @param array $args Args to construct instance
		 * @return FcPhp\Di\Interfaces\IContainer
		 */
		public function get(string $id, array $args = []) :IContainer
		{
			return $this->di->get($id, $args);
		}

		/**
		 * Method to get new instance of Container
		 *
		 * @param string $id Identify of instance
		 * @param array $args Args to construct instance
		 * @return FcPhp\Di\Interfaces\IContainer
		 */
		public function getNonSingleton(string $id, array $args = []) :IContainer
		{
			return $this->di->getNonSingleton($id, $args);
		}

		/**
		 * Method instance of class
		 *
		 * @param string $id Identify of instance
		 * @param array $args Args to construct instance
		 * @return mixed
		 */
		public function make(string $id, array $args = [])
		{
			return $this->di->make($id, $args);
		}

		public function beforeSet()
		{

		}

		public function afterSet()
		{

		}

		public function beforeGet()
		{
			
		}

		public function afterGet()
		{

		}

		public function beforeMake()
		{

		}

		public function afterMake()
		{
			
		}
	}
}