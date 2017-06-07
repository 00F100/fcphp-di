<?php

namespace FcPhp\Di\Exceptions
{
	use Exception;

	class ClassNotExistsException extends Exception
	{
		public function __construct()
		{
			parent::__construct('This Class not exists!', 404);
		}
	}
}