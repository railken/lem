<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\ParameterBag;

class ArticleParameterBag extends ParameterBag
{

	/**
	 * Filter current bag
	 *
	 * @return $this
	 */
	public function filterWrite()
	{
		return $this;
	}

}
