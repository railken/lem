<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelValidator;
use Railken\Laravel\Manager\ParameterBag;
use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Tests\Core\Article\Exceptions as Exceptions;
use Respect\Validation\Validator as v;

class ArticleValidator extends ModelValidator
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
            $errors->push(new Exceptions\ArticleAuthorNotDefinedException($parameters->get('author')));

        $parameters->exists('author') &&
            !$parameters->get('author') &&
            $errors->push(new Exceptions\ArticleAuthorNotValidException($parameters->get('author')));

        return $errors;
    }

    /**
     * Validate "title"
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateTitle(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        !$entity->exists && !$parameters->exists('title') &&
            $errors->push(new Exceptions\ArticleTitleNotDefinedException($parameters->get('title')));

        $parameters->exists('title') &&
            !v::length(2, 255)->validate($parameters->get('title')) &&
            $errors->push(new Exceptions\ArticleTitleNotValidException($parameters->get('title')));

        return $errors;
    }
}
