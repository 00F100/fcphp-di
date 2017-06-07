<?php

namespace FcPhp\Di\Exceptions
{
	use Exception;

	class ArgsIncompleteException extends Exception
	{
		public function __construct()
		{
			parent::__construct('Args incomplete!', 500);
		}
	}
}