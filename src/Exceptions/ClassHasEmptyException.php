<?php
/**
 * Fast Create PHP - Class has empty
 *
 * @author João Moraes <joaomoraesbr@gmail.com>
 */
namespace FcPhp\Di\Exceptions
{
	use Exception;

	class ClassHasEmptyException extends Exception
	{
		public function __construct()
		{
			parent::__construct('Class has empty!', 500);
		}
	}
}