<?php

namespace FcPhp\Di\Exceptions
{
	use Exception;

	class ArgsNotFoundException extends Exception
	{
		public function __construct()
		{
			parent::__construct('Arguments has empty!', 500);
		}
	}
}