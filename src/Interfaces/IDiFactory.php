<?php

namespace FcPhp\Di\Interfaces
{
	interface IDiFactory
	{
		public function getInstance(IContainerFactory $containerFactory, IInstanceFactory $instanceFactory, bool $register = false) :IDi;
	}
}