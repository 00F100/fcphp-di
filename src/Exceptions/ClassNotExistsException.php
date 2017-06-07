<?php
/**
 * Fast Create PHP - Class not exists
 *
 * @author João Moraes <joaomoraesbr@gmail.com>
 */
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