<?php

use PHPUnit\Framework\TestCase;

Class Mock extends TestCase
{
	public function getContainerMock()
	{
		$phpunit = $this;
		$containerFactory = $this->createMock('FcPhp\Di\Interfaces\IContainerFactory');
		$containerFactory
			->expects($this->any())
			->method('getInstance')
			->will($this->returnCallback(function() use ($phpunit){
				$container = $phpunit->createMock('FcPhp\Di\Interfaces\IContainer');

				$container
					->expects($this->any())
					->method('getClass')
					->will($this->returnCallback(function() {
						return new MockCallback();
					}));

				return $container;
			}));
		return $containerFactory;
	}

	public function getInstanceMock()
	{
		$phpunit = $this;
		$instanceFactory = $this->createMock('FcPhp\Di\Interfaces\IInstanceFactory');
		$instanceFactory
			->expects($this->any())
			->method('getInstance')
			->will($this->returnCallback(function() use ($phpunit) {
				$instance = $phpunit->createMock('FcPhp\Di\Interfaces\IInstance');

				$instance
					->expects($this->any())
					->method('getIsSingleton')
					->will($this->returnValue(true));

				return $instance;
			}));
		return $instanceFactory;
	}

	public function getInstanceNonSingletonMock()
	{
		$phpunit = $this;
		$instanceFactory = $this->createMock('FcPhp\Di\Interfaces\IInstanceFactory');
		$instanceFactory
			->expects($this->any())
			->method('getInstance')
			->will($this->returnCallback(function() use ($phpunit) {
				$instance = $phpunit->createMock('FcPhp\Di\Interfaces\IInstance');

				$instance
					->expects($this->any())
					->method('getIsSingleton')
					->will($this->returnValue(false));

				return $instance;
			}));
		return $instanceFactory;
	}
}

class MockCallback
{
	public function __construct()
	{

	}
}