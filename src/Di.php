<?php
/**
 * Fast Create PHP - Dependency Injection
 *
 * @author João Moraes <joaomoraesbr@gmail.com>
 */
namespace FcPhp\Di
{
	use Exception;
	use ReflectionClass;
	use FcPhp\Di\Interfaces\IDi;
	use FcPhp\Di\Exceptions\AliasEmptyException;
	use FcPhp\Di\Exceptions\ArgsNotFoundException;
	use FcPhp\Di\Exceptions\DuplicateClassException;
	use FcPhp\Di\Exceptions\ArgsIncompleteException;
	use FcPhp\Di\Exceptions\ClassHasEmptyException;
	use FcPhp\Di\Exceptions\ClassNotExistsException;
	use FcPhp\Di\Exceptions\ClassNotFoundException;
	use FcPhp\Di\Exceptions\ClassEmptyException;

	class Di implements IDi
	{
		private $defaultsTypes = [
			'class' => 'string',
			'singleton' => 'boolean',
			'params' => 'array',
			'depends' => 'array'
		];

		private $classes = [];

		private $instances = [];

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

		public function getNew($alias)
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

		public function getNewArgs($alias, array $params = [])
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

		private function createInstance($class, $params)
		{
			$class = new ReflectionClass($class);
			$instance = $class->newInstanceArgs($params);
			return $instance;
		}

		private function getSingleton($alias)
		{
			if(!isset($this->instances[$alias])){
				$this->instances[$alias] = $this->getNew($alias);
			}
			return $this->instances[$alias];
		}

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
	}
}