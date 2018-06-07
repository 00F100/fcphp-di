<?php

namespace FcPhp\Di\Traits
{
	use FcPhp\Di\Interfaces\IDi;
	use FcPhp\Di\Interfaces\IContainer;
	use FcPhp\Di\Exceptions\InstanceNotFound;
	use FcPhp\Di\Exceptions\ContainerNotFound;
	use FcPhp\Di\Exceptions\ClassBusy;

	trait DiCore
	{
		/**
		 * Method to make new instance of class
		 *
		 * @param string $id Identify of class
		 * @param array $args Args to contruct class
		 */
		private function _make(string $id, array $args = [], array $setters = [])
		{
			$this->beforeMake($id, $args, $setters);
			if(!isset($this->instances[$id])) {
				throw new InstanceNotFound();
			}
			$instance = $this->instances[$id];
			if(!$instance->getIsSingleton()) {
				$container = $this->_getNonSingleton($id, $args, $setters);
			}else{
				$checksum = $this->_getChecksum($id, $args);
				if(!isset($this->containers[$checksum])) {
					$this->containers[$checksum] = $this->_get($id, $args, $setters);
				}
				$container = $this->containers[$checksum];
			}
			$class = $container->getClass();
			$this->afterMake($id, $args, $setters, $instance, $container, $class);
			return $class;
		}

		/**
		 * Method to return Container to manipulate instance
		 *
		 * @param string $id Identify instance
		 * @param array $args Args to construct class
		 * @param array $setters Setters to after construct class
		 * @return FcPhp\Di\Interfaces\IContainer
		 */
		private function _get(string $id, array $args = [], array $setters = []) :IContainer
		{
			$this->beforeGet($id, $args, $setters);
			if(!isset($this->instances[$id])) {
				throw new InstanceNotFound();
			}
			$instance = $this->instances[$id];
			if(!$instance->getIsSingleton()) {
				$container = $this->containerFactory->getInstance($instance, $args, $setters);
				$this->registerContainer($container);
				$this->_afterGet($id, $args, $setters, $instance, $container);
				return $container;
			}
			$checksum = $this->_getChecksum($id, $args);
			if(!isset($this->containers[$checksum])) {
				$this->containers[$checksum] = $this->containerFactory->getInstance($instance, $args, $setters);
				$this->registerContainer($this->containers[$checksum]);
			}else{
				if(count($args) > 0 || count($setters) > 0) {
					throw new ClassBusy("This class has already been instantiated, can not be its modified constructor", 500);
				}
			}
			$this->_afterGet($id, $args, $setters, $instance, $this->containers[$checksum]);
			return $this->containers[$checksum];
		}

		/**
		 * Method to return new instance non singleton of class
		 *
		 * @param string $id Identify of instance
		 * @param array $args Args to construct class
		 * @return FcPhp\Di\Interfaces\IContainer
		 */
		private function _getNonSingleton(string $id, array $args = [], array $setters = []) :IContainer
		{
			if(!isset($this->instances[$id])) {
				throw new InstanceNotFound();
			}
			$container = $this->containerFactory->getInstance($this->instances[$id], $args, $setters);
			$this->registerContainer($container);
			return $container;
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
		private function _set(string $id, string $namespace, array $args = [], array $setters = [], bool $singleton = true) :IDi
		{
			$this->beforeSet($id, $namespace, $args, $setters, $singleton);
			if(!isset($this->instances[$id])) {
				$this->instances[$id] = $this->instanceFactory->getInstance($namespace, $args, $setters, $singleton);
			}
			$this->afterSet($id, $namespace, $args, $setters, $singleton, $this->instances[$id]);
			return $this;
		}

		/**
		 * Method to generate new checksum
		 *
		 * @return string
		 */
		private function _getChecksum()
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
	}
}