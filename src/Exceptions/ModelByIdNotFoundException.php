<?php

namespace Railken\Laravel\Manager\Exceptions;

use Exception;

class ModelByIdNotFoundException extends Exception
{

	/** 
	 * Construct
	 *
	 * @param string $param
	 * @param string $value
	 */
	public function __construct($param, $value){
		$message = "No record found for attribute {$param} with value {$value}";
		parent::__construct($message);
	}
}
