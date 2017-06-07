<?php

namespace FcPhp\Di\Exceptions
{
	use Exception;

	class ClassNotFoundException extends Exception
	{
		public function __construct()
		{
			parent::__construct('This Class not found!', 404);
		}
	}
}