<?php

namespace Railken\Laravel\Manager;

interface ModelAuthorizerContract
{

	/**
	 * Authorize update
	 *
	 * @param EntityContract $entity
	 * @param ParameterBag $parameters
	 *
	 * @return Collection
	 */
	public function update(EntityContract $entity, ParameterBag $parameters);
}
