<?php

namespace Railken\Laravel\Manager\Tests\Core\Comment;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelValidator;
use Railken\Laravel\Manager\ParameterBag;
use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Tests\Core\Comment\Exceptions as Exceptions;
use Respect\Validation\Validator as v;

class CommentValidator extends ModelValidator
{
    /**
     * Validate "author"
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateAuthor(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        !$entity->exists && !$parameters->exists('author') &&
                $errors->push(new Exceptions\CommentAuthorNotDefinedException($parameters->get('author')));

        $parameters->exists('author') &&
                !$parameters->get('author') &&
                $errors->push(new Exceptions\CommentAuthorNotValidException($parameters->get('author')));

        return $errors;
    }

    /**
     * Validate "article"
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateArticle(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        !$entity->exists && !$parameters->exists('article') &&
                $errors->push(new Exceptions\CommentArticleNotDefinedException($parameters->get('article')));

        $parameters->exists('article') &&
                !$parameters->get('article') &&
                $errors->push(new Exceptions\CommentArticleNotValidException($parameters->get('article')));

        return $errors;
    }

    /**
     * Validate "content"
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateContent(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        return $errors;
    }
}
