<?php

namespace Railken\Laravel\Manager\Tests\Core\Article\Attributes\Id;


use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelAttribute;
use Railken\Laravel\Manager\Traits\AttributeValidateTrait;
use Railken\Laravel\Manager\Tests\Core\Article\Attributes\Id\Exceptions as Exceptions;
use Respect\Validation\Validator as v;
use Railken\Laravel\Manager\Tokens;

class IdAttribute extends ModelAttribute
{

	/**
	 * Name attribute
	 *
	 * @var string
	 */
	protected $name = 'id';

    /**
     * List of all exceptions used in validation
     *
     * @var array
     */
    protected $exceptions = [
        Tokens::NOT_AUTHORIZED => Exceptions\ArticleIdNotAuthorizedException::class
    ];

    /**
     * List of all permissions
     */
    protected $permissions = [
        Tokens::PERMISSION_FILL => 'article.attributes.id.fill',
        Tokens::PERMISSION_SHOW => 'article.attributes.id.show'
    ];

}