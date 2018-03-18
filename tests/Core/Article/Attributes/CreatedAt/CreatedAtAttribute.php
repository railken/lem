<?php

namespace Railken\Laravel\Manager\Tests\Core\Article\Attributes\CreatedAt;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelAttribute;
use Respect\Validation\Validator as v;
use Railken\Laravel\Manager\Tokens;

class CreatedAtAttribute extends ModelAttribute
{
    /**
     * Name attribute.
     *
     * @var string
     */
    protected $name = 'created_at';

    /**
     * Is the attribute required
     * This will throw not_defined exception for non defined value and non existent model.
     *
     * @var bool
     */
    protected $required = false;

    /**
     * Is the attribute unique.
     *
     * @var bool
     */
    protected $unique = false;

    /**
     * List of all exceptions used in validation.
     *
     * @var array
     */
    protected $exceptions = [
        Tokens::NOT_DEFINED => Exceptions\ArticleCreatedAtNotDefinedException::class,
        Tokens::NOT_VALID => Exceptions\ArticleCreatedAtNotValidException::class,
        Tokens::NOT_AUTHORIZED => Exceptions\ArticleCreatedAtNotAuthorizedException::class,
    ];

    /**
     * List of all permissions.
     */
    protected $permissions = [
        Tokens::PERMISSION_FILL => 'article.attributes.created_at.fill',
        Tokens::PERMISSION_SHOW => 'article.attributes.created_at.show',
    ];

    /**
     * Is a value valid ?
     *
     * @param EntityContract $entity
     * @param mixed          $value
     *
     * @return bool
     */
    public function valid(EntityContract $entity, $value)
    {
        return v::length(1, 255)->validate($value);
    }
}
