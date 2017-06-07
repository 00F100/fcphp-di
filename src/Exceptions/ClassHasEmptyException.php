<?php

namespace FcPhp\Di\Exceptions
{
	use Exception;

	class ClassHasEmptyException extends Exception
	{
		public function __construct()
		{
			parent::__construct('Args incomplete!', 500);
		}
	}
}