<?php

use FcPhp\Di\Interfaces\Icontainer;
use FcPhp\Di\Facades\DiFacade;

require_once(__DIR__ . '/../Unit/Mock.php');

class DiIntegrationTest extends Mock
{
	public function setUp()
	{
		$this->di = DiFacade::getInstance();
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
}