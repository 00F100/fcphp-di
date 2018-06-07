<?php

namespace FcPhp\Di
{
	// use ReflectionClass;
	use FcPhp\Di\Interfaces\IDi;
	// use FcPhp\Di\Interfaces\IInstance;
	// use FcPhp\Di\Container;
	// use FcPhp\Di\Instance;
	// use FcPhp\Di\Exceptions\InstanceNotFound;
	// use FcPhp\Di\Exceptions\ContainerNotFound;
	// use FcPhp\Di\Exceptions\ClassBusy;
	// use FcPhp\Di\Factories\ContainerFactory;
	// use FcPhp\Di\Factories\DiFactory;
	use FcPhp\Di\Interfaces\IContainerFactory;
	use FcPhp\Di\Interfaces\IDiFactory;
	use FcPhp\Di\Interfaces\IInstanceFactory;
	use FcPhp\Di\Traits\DiEvents;
	use FcPhp\Di\Traits\DiCore;
	use FcPhp\Di\Interfaces\IInstance;
	use FcPhp\Di\Interfaces\IContainer;

	class Di implements IDi
	{
		use DiEvents;
		use DiCore;

		/**
		 * @var FcPHP\Di\Interfaces\IDi Instance
		 */
		private static $instance;

		/**
		 * @var array $instances List of instances
		 */
		private $instances = [];

		/**
		 * @var array List of containers register
		 */
		private $register_containers = [];

		/**
		 * @var bool Enable/Disable register container
		 */
		private $register = false;

		/**
		 * @var FcPhp\Di\Interfaces\IContainerFactory Container Factory
		 */
		private $containerFactory;

		/**
		 * @var FcPhp\Di\Interfaces\IInfanceFactory Instance Factory
		 */
		private $instanceFactory;

		/**
		 * @var object 
		 */
		private $beforeSet;

		/**
		 * @var object 
		 */
		private $afterSet;

		/**
		 * @var object 
		 */
		private $beforeGet;

		/**
		 * @var object 
		 */
		private $afterGet;

		/**
		 * @var object 
		 */
		private $beforeMake;

		/**
		 * @var object 
		 */
		private $afterMake;

		/**
		 * Return new instance of Di
		 *
		 * @return FcPHP\Di\Interfaces\IDi
		 */
		public static function getInstance(IDiFactory $diFactory, IContainerFactory $containerFactory, IInstanceFactory $instanceFactory, bool $register = false)
		{
			if(!self::$instance instanceof IDi) {
				self::$instance = $diFactory->getInstance($containerFactory, $instanceFactory, $register);
			}
			return self::$instance;
		}

		/**
		 * Method to contruct instance of Di
		 *
		 * @param bool $register Define if register containers to log
		 */
		public function __construct(IContainerFactory $containerFactory, IInstanceFactory $instanceFactory, $register = false)
		{
			$this->register = $register;
			$this->instanceFactory = $instanceFactory;
			$this->containerFactory = $containerFactory;
		}

		/**
		 * Method to configure new instance
		 *
		 * @param string $id Identify instance
		 * @param string $namespace Namespace of class
		 * @param array $args Args to construct class
		 * @param bool $singleton Define this class has singleton (or not
		 * @return FcPhp\Di\Interfaces\IDi
		 */
		public function set(string $id, string $namespace, array $args = [], array $setters = [], bool $singleton = true) :IDi
		{
			$this->_set($id, $namespace, $args, $setters, $singleton);
			return $this;
		}

		/**
		 * Method to configure new instance non singleton
		 *
		 * @param string $id Identify instance
		 * @param string $namespace Namespace of class
		 * @param array $args Args to construct class
		 * @return FcPhp\Di\Interfaces\IDi
		 */
		public function setNonSingleton(string $id, string $namespace, array $args = [], array $setters = []) :IDi
		{
			return $this->set($id, $namespace, $args, $setters, false);
		}

		/**
		 * Method to return Container to manipulate instance
		 *
		 * @param string $id Identify instance
		 * @param array $args Args to construct class
		 * @param array $setters Setters to after construct class
		 * @return FcPhp\Di\Interfaces\IContainer
		 */
		public function get(string $id, array $args = [], array $setters = []) :IContainer
		{
			return $this->_get($id, $args, $setters);
		}

		/**
		 * Method to return new instance non singleton of class
		 *
		 * @param string $id Identify of instance
		 * @param array $args Args to construct class
		 * @return FcPhp\Di\Interfaces\IContainer
		 */
		public function getNonSingleton(string $id, array $args = [], array $setters = []) :IContainer
		{
			return $this->_getNonSingleton($id, $args, $setters);
		}

		/**
		 * Method to make new instance of class
		 *
		 * @param string $id Identify of class
		 * @param array $args Args to contruct class
		 * @return mixed
		 */
		public function make(string $id, array $args = [], array $setters = [])
		{
			return $this->_make($id, $args, $setters);
		}

		/**
		 * Method to execute clousure on event
		 *
		 * @param string $eventName Event Name, ex: beforeSet
		 * @param object $callback Function to execute
		 * @return void
		 */
		public function event($eventName, object $callback = null) :void
		{
			$this->_event($eventName, $callback);
		}
	}
}