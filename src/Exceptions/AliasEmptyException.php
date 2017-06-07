<?php
/**
 * Fast Create PHP - Alias has empty
 *
 * @author João Moraes <joaomoraesbr@gmail.com>
 */
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