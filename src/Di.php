<?php

namespace FcPhp\Di
{
	use ReflectionClass;
	use FcPhp\Di\Interfaces\IDi;
	use FcPhp\Di\Interfaces\IContainer;
	use FcPhp\Di\Container;
	use FcPhp\Di\Instance;
	use FcPhp\Di\Exceptions\InstanceNotFound;
	use FcPhp\Di\Exceptions\ContainerNotFound;

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
		 * Return new instance of Di
		 *
		 * @return FcPHP\Di\Interfaces\IDi
		 */
		public static function getInstance()
		{
			if(!self::$instance instanceof IDi) {
				self::$instance = new Di();
			}
			return self::$instance;
		}

		/**
		 * Method to contruct instance of Di
		 *
		 * @param bool $register Define if register containers to log
		 */
		public function __construct($register = false)
		{
			$this->register = $register;
		}

		/**
		 * Return all instances
		 *
		 * @return array
		 */
		public function getInstances() :array
		{
			return $this->instances;
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
			if(!isset($this->instances[$id])) {
				$this->instances[$id]=  new Instance($namespace, $args, $singleton);
			}
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
			if(!isset($this->instances[$id])) {
				throw new InstanceNotFound();
			}
			$instance = $this->instances[$id];
			if(!$instance->getIsSingleton()) {
				$container = new Container($instance, $args);
				$this->registerContainer($container);
				return $container;
			}
			$checksum = $this->getChecksum($id, $args);
			if(!isset($this->containers[$checksum])) {
				$this->containers[$checksum] = new Container($instance, $args);
				$this->registerContainer($this->containers[$checksum]);
			}
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
			$container = new Container($this->instances[$id], $args);
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

			return $container->getClass();
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
	}
}