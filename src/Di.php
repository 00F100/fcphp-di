<?php

namespace FcPhp\Di
{
	use ReflectionClass;
	use FcPhp\Di\Interfaces\IDi;
	use FcPhp\Di\Interfaces\IContainer;
	// use FcPhp\Di\Container;
	use FcPhp\Di\Instance;
	use FcPhp\Di\Exceptions\InstanceNotFound;
	use FcPhp\Di\Exceptions\ContainerNotFound;
	use FcPhp\Di\Factories\ContainerFactory;
	use FcPhp\Di\Factories\DiFactory;
	use FcPhp\Di\Interfaces\IContainerFactory;
	use FcPhp\Di\Interfaces\IDiFactory;
	use FcPhp\Di\Interfaces\IInstanceFactory;
	use FcPhp\Di\Interfaces\IInstance;

	class Di implements IDi
	{
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

		private $beforeSet;
		private $afterSet;
		private $beforeGet;
		private $afterGet;
		private $beforeMake;
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
		public function set(string $id, string $namespace, array $args = [], bool $singleton = true) :IDi
		{
			$this->beforeSet($id, $namespace, $args, $singleton);
			if(!isset($this->instances[$id])) {
				$this->instances[$id] = $this->instanceFactory->getInstance($namespace, $args, $singleton);
			}
			$this->afterSet($id, $namespace, $args, $singleton, $this->instances[$id]);
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
		public function setNonSingleton(string $id, string $namespace, array $args = []) :IDi
		{
			return $this->set($id, $namespace, $args, false);
		}

		/**
		 * Method to return Container to manipulate instance
		 *
		 * @param string $id Identify instance
		 * @param array $args Args to construct class
		 * @return FcPhp\Di\Interfaces\IContainer
		 */
		public function get(string $id, array $args = []) :IContainer
		{
			$this->beforeGet($id, $args);
			if(!isset($this->instances[$id])) {
				throw new InstanceNotFound();
			}
			$instance = $this->instances[$id];
			if(!$instance->getIsSingleton()) {
				$container = $this->containerFactory->getInstance($instance, $args);
				$this->registerContainer($container);
				$this->afterGet($id, $args, $instance, $container);
				return $container;
			}
			$checksum = $this->getChecksum($id, $args);
			if(!isset($this->containers[$checksum])) {
				$this->containers[$checksum] = $this->containerFactory->getInstance($instance, $args);
				$this->registerContainer($this->containers[$checksum]);
			}
			$this->afterGet($id, $args, $instance, $this->containers[$checksum]);
			return $this->containers[$checksum];
		}

		/**
		 * Method to return new instance non singleton of class
		 *
		 * @param string $id Identify of instance
		 * @param array $args Args to construct class
		 * @return FcPhp\Di\Interfaces\IContainer
		 */
		public function getNonSingleton(string $id, array $args = []) :IContainer
		{
			if(!isset($this->instances[$id])) {
				throw new InstanceNotFound();
			}
			$container = $this->containerFactory->getInstance($this->instances[$id], $args);
			$this->registerContainer($container);
			return $container;
		}

		/**
		 * Method to make new instance of class
		 *
		 * @param string $id Identify of class
		 * @param array $args Args to contruct class
		 */
		public function make(string $id, array $args = [])
		{
			$this->beforeMake($id, $args);
			if(!isset($this->instances[$id])) {
				throw new InstanceNotFound();
			}
			$instance = $this->instances[$id];
			if(!$instance->getIsSingleton()) {
				$container = $this->getNonSingleton($id, $args);
			}else{
				$checksum = $this->getChecksum($id, $args);
				if(!isset($this->containers[$checksum])) {
					$this->containers[$checksum] = $this->get($id, $args);
				}
				$container = $this->containers[$checksum];
			}
			$class = $container->getClass();
			$this->afterMake($id, $args, $instance, $container, $class);
			return $class;
		}

		/**
		 * Method to generate new checksum
		 *
		 * @return string
		 */
		private function getChecksum()
		{
			return md5(serialize(func_get_args()));
		}

		/**
		 * Method to register container for log
		 *
		 * @param FcPhp\Di\Interfaces\IContainer $container
		 * @return void
		 */
		private function registerContainer(IContainer $container) :void
		{
			if($this->register) {
				$this->register_containers[] = $container;
			}
		}

		public function register()
		{
			return $this->register;
		}

		public function event($eventName, Clousure $callback = null)
		{
			if(is_array($eventName)) {
				foreach($eventName as $eName => $clousure) {
					$this->{$eName} = $clousure;
				}
			}else{
				$this->{$eventName} = $clousure;
			}
		}

		public function beforeSet(string $id, string $namespace, array $args, bool $singleton)
		{
			$event = $this->beforeSet;
			$event($id, $namespace, $args, $singleton);
		}

		public function afterSet(string $id, string $namespace, array $args, bool $singleton, IInstance $instance)
		{
			$event = $this->beforeSet;
			$event($id, $namespace, $args, $singleton, $instance);
		}

		public function beforeGet(string $id, array $args)
		{
			$event = $this->beforeGet;
			$event($id, $args);
		}

		public function afterGet(string $id, array $args, IInstance $instance, IContainer $container)
		{
			$event = $this->afterGet;
			$event($id, $args, $instance, $container);
		}

		public function beforeMake(string $id, array $args)
		{
			$event = $this->beforeMake;
			$event($id, $args);
		}

		public function afterMake(string $id, array $args, IInstance $instance, IContainer $container, $class)
		{
			$event = $this->afterMake;
			$event($id, $args, $instance, $container, $class);
		}
	}
}