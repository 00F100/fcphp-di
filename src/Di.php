<?php
/**
 * Fast Create PHP - Dependency Injection
 *
 * @author João Moraes <joaomoraesbr@gmail.com>
 */
namespace FcPhp\Di
{
	use Exception;
	use FcPhp\Di\Instance;
	use FcPhp\Di\Interfaces\IDi;
	use FcPhp\Di\Exceptions\AliasEmptyException;
	use FcPhp\Di\Exceptions\ArgsNotFoundException;
	use FcPhp\Di\Exceptions\DuplicateClassException;
	use FcPhp\Di\Exceptions\ArgsIncompleteException;
	use FcPhp\Di\Exceptions\ClassHasEmptyException;
	use FcPhp\Di\Exceptions\ClassNotExistsException;
	use FcPhp\Di\Exceptions\ClassNotFoundException;
	use FcPhp\Di\Exceptions\ClassEmptyException;
	use FcPhp\Di\Exceptions\FailToLoadClassException;

	class Di implements IDi
	{
		/**
		 * List of params and types acceptable
		 * @var array
		 */
		private $defaultsTypes = [
			'class' => 'string',
			'singleton' => 'boolean',
			'params' => 'array',
			'depends' => 'array'
		];

		/**
		 * List of classes in cache
		 * @var array
		 */
		private $classes = [];

		/**
		 * List of instances in cache
		 * @var array
		 */
		private $instances = [];

		/**
		 * Function to execute before instantiating
		 * @var mixed
		 */
		private $beforeInstantiating;

		/**
		 * Function to execute after instantiating
		 * @var mixed
		 */
		private $afterInstantiating;

		/**
		 * Function to execute before execute method
		 * @var mixed
		 */
		private $beforeCallMethod;

		/**
		 * Function to execute after execute method
		 * @var mixed
		 */
		private $afterCallMethod;

		/**
		 * Method to configure class
		 *
		 * @param string $alias Alias of class
		 * @param array $args List of params to class
		 * @return boolean
		 *
		 * @throws FcPhp\Di\Interfaces\AliasEmptyException
		 * @throws FcPhp\Di\Interfaces\ArgsNotFoundException
		 * @throws FcPhp\Di\Interfaces\DuplicateClassException
		 * @throws FcPhp\Di\Interfaces\ArgsIncompleteException
		 * @throws FcPhp\Di\Interfaces\ClassEmptyException
		 * @throws FcPhp\Di\Interfaces\ClassNotFoundException
		 */
		public function set($alias = null, array $args = [])
		{
			if(empty($alias)){
				throw new AliasEmptyException();
			}
			if(count($args) == 0){
				throw new ArgsNotFoundException();
			}
			if(isset($this->classes[$alias])){
				throw new DuplicateClassException();
			}
			if(!$this->validateArgs($args)){
				throw new ArgsIncompleteException();
			}
			if(empty($args['class'])){
				throw new ClassEmptyException();
			}
			if(!class_exists($args['class'])){
				throw new ClassNotFoundException();
			}

			$this->classes[$alias] = $args;
			return true;
		}

		/**
		 * Method to get class with singleton (if configured)
		 *
		 * @param string $alias Alias of class
		 * @return mixed
		 *
		 * @throws FcPhp\Di\Interfaces\ClassHasEmptyException
		 * @throws FcPhp\Di\Interfaces\ClassNotExistsException
		 */
		public function get($alias = null)
		{
			if(empty($alias)){
				throw new ClassHasEmptyException();
			}
			if(!isset($this->classes[$alias])){
				throw new ClassNotExistsException();
			}
			$args = $this->classes[$alias];
			if($args['singleton']){
				return $this->getSingleton($alias);
			}else{
				return $this->getNew($alias);
			}
		}

		/**
		 * Method to get new instance of class (no new params)
		 *
		 * @param string $alias Alias of class
		 * @return mixed
		 *
		 * @throws FcPhp\Di\Interfaces\ClassHasEmptyException
		 * @throws FcPhp\Di\Interfaces\ClassNotExistsException
		 */
		public function getNew($alias = null)
		{
			if(empty($alias)){
				throw new ClassHasEmptyException();
			}
			if(!isset($this->classes[$alias])){
				throw new ClassNotExistsException();
			}
			$args = $this->classes[$alias];
			return $this->createInstance($args['class'], $args['params']);
			
		}

		/**
		 * Method to get new instance of class (no new params)
		 *
		 * @param string $alias Alias of class
		 * @param array $params New params to classe
		 * @return mixed
		 *
		 * @throws FcPhp\Di\Interfaces\ClassHasEmptyException
		 * @throws FcPhp\Di\Interfaces\ClassNotExistsException
		 * @throws FcPhp\Di\Interfaces\ArgsIncompleteException
		 */
		public function getNewArgs($alias = null, array $params = [])
		{
			if(empty($alias)){
				throw new ClassHasEmptyException();
			}
			if(!isset($this->classes[$alias])){
				throw new ClassNotExistsException();
			}
			if(count($params) == 0){
				throw new ArgsIncompleteException();
			}
			$args = $this->classes[$alias];
			$args['params'] = $params;
			return $this->createInstance($args['class'], $args['params']);
		}

		/**
		 * Method to return singleton instance of class
		 *
		 * @param string $alias Alias of class
		 * @return mixed
		 */
		private function getSingleton($alias)
		{
			if(!isset($this->instances[$alias])){
				$this->instances[$alias] = $this->getNew($alias);
			}
			return $this->instances[$alias];
		}

		/**
		 * Method to validate args of class on set
		 *
		 * @param array $args List of args to configure class
		 * @return boolean
		 */
		private function validateArgs(array $args)
		{
			foreach($this->defaultsTypes as $param => $value) {
				if(!isset($args[$param])) {
					return false;
				}else{
					if(gettype($args[$param]) != $value) {
						return false;
					}
				}
			}
			return true;
		}

		/**
		 * Method to create a instance of class
		 *
		 * @param string $alias Alias of class
		 * @param array $params New params to classe
		 * @return FcPhp\Di\Interfaces\IInstance
		 *
		 * @throws FcPhp\Di\Interfaces\ClassEmptyException
		 */
		private function createInstance($class, $params)
		{
			$beforeInstantiating = function(){

			};
			$afterInstantiating = function(){

			};
			return new Instance($class, $params, $beforeInstantiating, $afterInstantiating);
		}
	}
}