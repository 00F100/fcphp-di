<?php

namespace FcPhp\Di\Traits
{
	use FcPhp\Di\Interfaces\IInstance;
	use FcPhp\Di\Interfaces\IContainer;

	trait DiEvents
	{
		

		/**
		 * Method to event before execute set()
		 *
		 * @param string $id Identify of instance
		 * @param string $namespace Namespace of class
		 * @param array $args Args to construct instance
		 * @param array $setters Setters to class
		 * @param bool $singleton If this class is singleton
		 * @return void
		 */
		public function beforeSet(string $id, string $namespace, array $args, array $setters, bool $singleton) :void
		{
			$this->_beforeSet($id, $namespace, $args, $setters, $singleton);
		}

		/**
		 * Method to event after execute set()
		 *
		 * @param string $id Identify of instance
		 * @param string $namespace Namespace of class
		 * @param array $args Args to construct instance
		 * @param array $setters Setters to class
		 * @param bool $singleton If this class is singleton
		 * @param FcPhp\Di\Interfaces\IInstance $instance Instance config of class
		 * @return void
		 */
		public function afterSet(string $id, string $namespace, array $args, array $setters, bool $singleton, IInstance $instance)
		{
			$this->_afterSet($id, $namespace, $args, $setters, $singleton, $instance);
		}

		/**
		 * Method to event before execute get()
		 *
		 * @param string $id Identify of instance
		 * @param array $args Args to construct instance
		 * @param array $setters Setters to class
		 * @return void
		 */
		public function beforeGet(string $id, array $args, array $setters) :void
		{
			$this->_beforeGet($id, $args, $setters);
		}

		/**
		 * Method to event after execute get()
		 *
		 * @param string $id Identify of instance
		 * @param array $args Args to construct instance
		 * @param array $setters Setters to class
		 * @param FcPhp\Di\Interfaces\IInstance $instance Instance config of class
		 * @param FcPhp\Di\Interfaces\IContainer $container Instance of Container
		 * @return void
		 */
		public function afterGet(string $id, array $args, array $setters, IInstance $instance, IContainer $container) :void
		{
			$this->_afterGet($id, $args, $setters, $instance, $container);
		}

		/**
		 * Method to event before execute make()
		 *
		 * @param string $id Identify of instance
		 * @param array $args Args to construct instance
		 * @param array $setters Setters to class
		 * @return void
		 */
		public function beforeMake(string $id, array $args, array $setters) :void
		{
			$this->_beforeMake($id, $args, $setters);
		}

		/**
		 * Method to event after execute make()
		 *
		 * @param string $id Identify of instance
		 * @param array $args Args to construct instance
		 * @param array $setters Setters to class
		 * @param FcPhp\Di\Interfaces\IInstance $instance Instance config of class
		 * @param FcPhp\Di\Interfaces\IContainer $container Instance of Container
		 * @param mixed $class Instance of class
		 * @return void
		 */
		public function afterMake(string $id, array $args, array $setters, IInstance $instance, IContainer $container, $class) :void
		{
			$this->_afterMake($id, $args, $setters, $instance, $container, $class);
		}

		/**
		 * Method to execute clousure on event
		 *
		 * @param string $eventName Event Name, ex: beforeSet
		 * @param object $callback Function to execute
		 * @return void
		 */
		private function _event($eventName, object $callback = null) :void
		{
			if(is_array($eventName)) {
				foreach($eventName as $eName => $clousure) {
					$this->{$eName} = $clousure;
				}
			}else{
				$this->{$eventName} = $callback;
			}
		}

		/**
		 * Method to event before execute set()
		 *
		 * @param string $id Identify of instance
		 * @param string $namespace Namespace of class
		 * @param array $args Args to construct instance
		 * @param array $setters Setters to class
		 * @param bool $singleton If this class is singleton
		 * @return void
		 */
		private function _beforeSet(string $id, string $namespace, array $args, array $setters, bool $singleton) :void
		{
			$event = $this->beforeSet;
			if(gettype($event) == 'object') {
				$event($id, $namespace, $args, $setters, $singleton);
			}
		}

		/**
		 * Method to event after execute set()
		 *
		 * @param string $id Identify of instance
		 * @param string $namespace Namespace of class
		 * @param array $args Args to construct instance
		 * @param array $setters Setters to class
		 * @param bool $singleton If this class is singleton
		 * @param FcPhp\Di\Interfaces\IInstance $instance Instance config of class
		 * @return void
		 */
		private function _afterSet(string $id, string $namespace, array $args, array $setters, bool $singleton, IInstance $instance)
		{
			$event = $this->beforeSet;
			if(gettype($event) == 'object') {
				$event($id, $namespace, $args, $setters, $singleton, $instance);
			}
		}

		/**
		 * Method to event before execute get()
		 *
		 * @param string $id Identify of instance
		 * @param array $args Args to construct instance
		 * @param array $setters Setters to class
		 * @return void
		 */
		private function _beforeGet(string $id, array $args, array $setters) :void
		{
			$event = $this->beforeGet;
			if(gettype($event) == 'object') {
				$event($id, $args, $setters);
			}
		}

		/**
		 * Method to event after execute get()
		 *
		 * @param string $id Identify of instance
		 * @param array $args Args to construct instance
		 * @param array $setters Setters to class
		 * @param FcPhp\Di\Interfaces\IInstance $instance Instance config of class
		 * @param FcPhp\Di\Interfaces\IContainer $container Instance of Container
		 * @return void
		 */
		private function _afterGet(string $id, array $args, array $setters, IInstance $instance, IContainer $container) :void
		{
			$event = $this->afterGet;
			if(gettype($event) == 'object') {
				$event($id, $args, $setters, $instance, $container);
			}
		}

		/**
		 * Method to event before execute make()
		 *
		 * @param string $id Identify of instance
		 * @param array $args Args to construct instance
		 * @param array $setters Setters to class
		 * @return void
		 */
		private function _beforeMake(string $id, array $args, array $setters) :void
		{
			$event = $this->beforeMake;
			if(gettype($event) == 'object') {
				$event($id, $args, $setters);
			}
		}

		/**
		 * Method to event after execute make()
		 *
		 * @param string $id Identify of instance
		 * @param array $args Args to construct instance
		 * @param array $setters Setters to class
		 * @param FcPhp\Di\Interfaces\IInstance $instance Instance config of class
		 * @param FcPhp\Di\Interfaces\IContainer $container Instance of Container
		 * @param mixed $class Instance of class
		 * @return void
		 */
		private function _afterMake(string $id, array $args, array $setters, IInstance $instance, IContainer $container, $class) :void
		{
			$event = $this->afterMake;
			if(gettype($event) == 'object') {
				$event($id, $args, $setters, $instance, $container, $class);
			}
		}
	}
}