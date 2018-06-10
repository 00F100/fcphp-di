<?php

include(__DIR__ . '/../../vendor/autoload.php');

use FcPhp\Di\Facades\DiFacade;


class TestClass
{
	private $value;
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}

	public function getValue()
	{
		return $this->value;
	}
}

// Init Di
$di = DiFacade::getInstance();

// Configure classes
$di->set('TestClass', 'TestClass', [], ['setValue' => 'content']);
$di->set('TestClass2', 'TestClass', [], ['setValue' => $di->get('TestClass')]);

// Print: content
echo $di->make('TestClass2')->getValue()->getValue();
echo "\n";
$di->make('TestClass')->setValue('content-change');

// Print: content-change
echo $di->make('TestClass')->getValue();
echo "\n";

// Print: test-change
echo $di->make('TestClass2')->getValue()->setValue('test-change')->getValue();
echo "\n";