<?php

namespace FcPhp\Di\Exceptions
{
	use Exception;

	class FailToLoadClassException extends Exception
	{
		public function __construct(Exception $e)
		{
			parent::__construct('This Class not found!', 500, $e);
		}
	}
}