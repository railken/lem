<?php

namespace Railken\Laravel\Manager\Tests\Core\Comment;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\ModelValidatorContract;
use Railken\Laravel\Manager\ParameterBag;
use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Tests\Core\Comment\Exceptions as Exceptions;

class CommentValidator implements ModelValidatorContract
{

    /**
     * @var ModelManager
     */
    protected $manager;

    /**
     * Construct
     */
    public function __construct(CommentManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Validate
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     * @param bool $required
     *
     * @return Collection
     */
    public function validate(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        if (!$entity->exists) {
            $errors = $errors->merge($this->validateRequired($parameters));
        }

        $errors = $errors->merge($this->validateValue($entity, $parameters));

        return $errors;
    }

    /**
     * Validate "required" values
     *
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateRequired(ParameterBag $parameters)
    {
        $errors = new Collection();

        !$parameters->exists('content') && $errors->push(new Exceptions\CommentContentNotDefinedException($parameters->get('content')));
        !$parameters->exists('author') && $errors->push(new Exceptions\CommentAuthorNotDefinedException($parameters->get('author')));
        !$parameters->exists('article') && $errors->push(new Exceptions\CommentArticleNotDefinedException($parameters->get('article')));


        return $errors;
    }

    /**
     * Validate "not valid" values
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateValue(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        $parameters->exists('content') && !$this->validName($parameters->get('content')) &&
            $errors->push(new Exceptions\CommentContentNotValidException($parameters->get('content')));

		$parameters->exists('author') && !$parameters->get('author') &&
			$errors->push(new Exceptions\CommentAuthorNotValidException($parameters->get('author')));

		$parameters->exists('article') && !$parameters->get('article') &&
			$errors->push(new Exceptions\CommentArticleNotValidException($parameters->get('article')));

        return $errors;
    }

    /**
     * Validate name
     *
     * @param string $name
     *
     * @return bool
     */
    public function validName($name)
    {
        return $name === null || (strlen($name) >= 3 && strlen($name) < 255);
    }
}
