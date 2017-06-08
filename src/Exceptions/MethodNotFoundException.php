<?php
/**
 * Fast Create PHP - Method not found
 *
 * @author João Moraes <joaomoraesbr@gmail.com>
 */
namespace FcPhp\Di\Exceptions
{
	use Exception;

	class MethodNotFoundException extends Exception
	{
		public function __construct()
		{
			parent::__construct('This Method not found!', 404);
		}
	}
}