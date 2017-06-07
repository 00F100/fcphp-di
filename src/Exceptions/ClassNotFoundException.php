<?php
/**
 * Fast Create PHP - Class not found
 *
 * @author João Moraes <joaomoraesbr@gmail.com>
 */
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