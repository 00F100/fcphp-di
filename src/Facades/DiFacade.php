<?php

namespace FcPhp\Di\Facades
{
	use FcPhp\Di\Di;
	use FcPhp\Di\Factories\ContainerFactory;
	use FcPhp\Di\Factories\InstanceFactory;
	use FcPhp\Di\Interfaces\IDi;
	use FcPhp\Di\Interfaces\IContainer;

	class DiFacade
	{
		/**
		 * @var FcPhp\Di\Facades\DiFacade
		 */
		private static $instance;

		/**
		 * Method to create and return instance of Di Facade
		 *
		 * @param bool $register Register Containers
		 * @return FcPhp\Di\Facades\DiFacade
		 */
		public static function getInstance(bool $register = false) :IDi
		{
			if(!self::$instance instanceof IDi) {
				self::$instance = new Di(new ContainerFactory(), new InstanceFactory(), $register);
			}
			return self::$instance;
		}
	}
}