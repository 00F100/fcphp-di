<?php
/**
 * Fast Create PHP - Class Fake Test
 *
 * @author João Moraes <joaomoraesbr@gmail.com>
 */
namespace FcPhp\Di\Test\Mocks
{
	class EntityTest
	{
		private $params = [];

		public function __construct($param1 = null, $param2 = null, $param3 = null)
		{
			$this->params = [$param1, $param2, $param3];
		}

		public function getParams()
		{
			return $this->params;
		}
	}
}