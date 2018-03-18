<?php

namespace Railken\Laravel\Manager\Tests\Core\Article\Attributes\AuthorId;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelAttribute;
use Railken\Laravel\Manager\Tokens;
use Railken\Laravel\Manager\Tests\User\UserManager;

class AuthorIdAttribute extends ModelAttribute
{
    /**
     * Name attribute.
     *
     * @var string
     */
    protected $name = 'author_id';

    /**
     * Is the attribute required
     * This will throw not_defined exception for non defined value and non existent model.
     *
     * @var bool
     */
    protected $required = true;

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
        Tokens::NOT_DEFINED => Exceptions\ArticleAuthorIdNotDefinedException::class,
        Tokens::NOT_VALID => Exceptions\ArticleAuthorIdNotValidException::class,
        Tokens::NOT_AUTHORIZED => Exceptions\ArticleAuthorIdNotAuthorizedException::class,
    ];

    /**
     * List of all permissions.
     */
    protected $permissions = [
        Tokens::PERMISSION_FILL => 'article.attributes.author_id.fill',
        Tokens::PERMISSION_SHOW => 'article.attributes.author_id.show',
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
        $user = (new UserManager($this->getManager()->getAgent()))->getRepository()->findOneById($value);

        return !empty($user);
    }
}
