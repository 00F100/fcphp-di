<?php

namespace FcPhp\Di\Factories
{
	use FcPhp\Di\Interfaces\IDiFactory;
	use FcPhp\Di\Interfaces\IContainerFactory;
	use FcPhp\Di\Interfaces\IInstanceFactory;
	use FcPhp\Di\Di;

	class DiFactory implements IDiFactory
	{
		public function getInstance(IContainerFactory $containerFactory, IInstanceFactory $instanceFactory, bool $register = false)
		{
			return new Di($containerFactory, $instanceFactory, $register);
		}
	}
}