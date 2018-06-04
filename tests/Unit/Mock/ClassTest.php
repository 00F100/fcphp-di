<?php

namespace FcPHP\Di\Test\Unit\Mock
{
	class ClassTest
	{
		private $param1 = false;
		private $value;

		public function __construct($param1 = null)
		{
			$this->param1 = $param1;
		}

		public function getParam1()
		{
			return $this->param1;
		}

		public function setValue($value)
		{
			$this->value = $value;
		}

		public function getValue()
		{
			return $this->value;
		}
	}
}