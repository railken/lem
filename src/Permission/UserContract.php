<?php

namespace Railken\Laravel\Manager\Permission;

interface UserContract
{

	public function getRole();
	public function getIdentifier();

}