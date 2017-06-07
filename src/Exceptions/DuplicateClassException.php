<?php
/**
 * Fast Create PHP - This class has duplicate
 *
 * @author João Moraes <joaomoraesbr@gmail.com>
 */
namespace FcPhp\Di\Exceptions
{
	use Exception;

	class DuplicateClassException extends Exception
	{
		public function __construct()
		{
			parent::__construct('Duplicate Class!', 500);
		}
	}
}