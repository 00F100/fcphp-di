<?php
/**
 * Fast Create PHP - Args incomplete
 *
 * @author João Moraes <joaomoraesbr@gmail.com>
 */
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