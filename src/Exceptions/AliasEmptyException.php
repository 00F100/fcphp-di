<?php

namespace FcPhp\Di\Exceptions
{
	use Exception;

	class AliasEmptyException extends Exception
	{
		public function __construct()
		{
			parent::__construct('Alias has empty!', 404);
		}
	}
}