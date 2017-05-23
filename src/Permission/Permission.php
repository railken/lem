<?php

namespace Railken\Laravel\Manager\Permission;

class Permission
{

	protected $name;
	protected $operation;

	/**
	 * Construct
	 *
	 * @param string $name
	 * @param string $operation
	 */
	public function __construct($name, $operation)
	{
		$this->name = $name;
		$this->operation = $operation;
	}
}