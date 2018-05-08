<?php

namespace Railken\Laravel\Manager\Contracts;

interface PolicyContract
{
	public function newQuery($query, AgentContract $agent);
}
