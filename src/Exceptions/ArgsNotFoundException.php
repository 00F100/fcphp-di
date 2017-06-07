<?php
/**
 * Fast Create PHP - Args not found
 *
 * @author João Moraes <joaomoraesbr@gmail.com>
 */
namespace FcPhp\Di\Exceptions
{
	use Exception;

	class ArgsNotFoundException extends Exception
	{
		public function __construct()
		{
			parent::__construct('Arguments not found!', 500);
		}
	}
}