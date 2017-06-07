<?php

namespace FcPhp\Di\Exceptions
{
	use Exception;

	class ClassEmptyException extends Exception
	{
		public function __construct()
		{
			parent::__construct('Class empty!', 500);
		}
	}
}