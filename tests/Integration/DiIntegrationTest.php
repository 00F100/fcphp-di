<?php

use FcPhp\Di\Di;
use FcPhp\Di\Interfaces\IDi;
use FcPhp\Di\Factories\DiFactory;
use FcPhp\Di\Factories\ContainerFactory;
use FcPhp\Di\Factories\InstanceFactory;
use FcPhp\Di\Interfaces\Icontainer;
use FcPhp\Di\Facades\DiFacade;

require_once(__DIR__ . '/../Unit/Mock.php');

class DiIntegrationTest extends Mock
{
	public function setUp()
	{
		$this->di = DiFacade::getInstance();
	}

	public function testCreate()
	{
		$this->assertTrue(Di::getInstance(new DiFactory(), new ContainerFactory(), new InstanceFactory(), false) instanceof IDi);
	}

	public function testIntance()
	{
		$this->assertTrue($this->di instanceof DiFacade);
	}

	public function testSetGet()
	{
		$this->di->set('TestClass', '\MockCallback');
		$this->assertTrue($this->di->get('TestClass') instanceof IContainer);
	}

	public function testMake()
	{
		$this->di->set('TestClass', '\MockCallback');
		$this->assertTrue($this->di->make('TestClass') instanceof \MockCallback);
	}

	public function testGetNonSingleton()
	{
		$this->di->set('TestClass', '\MockCallback');
		$this->assertTrue($this->di->getNonSingleton('TestClass')->getClass() instanceof \MockCallback);
	}

	public function testEvent()
	{
		$this->di->event('beforeSet', function() {
			$this->assertTrue(true);
		});
		$this->di->set('TestClass', '\MockCallback');
	}
}