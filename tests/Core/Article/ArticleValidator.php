<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\ModelValidatorContract;
use Railken\Laravel\Manager\ParameterBag;
use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Tests\Core\Article\Exceptions as Exceptions;


class ArticleValidator implements ModelValidatorContract
{

	/**
	 * @var ModelManager
	 */
	protected $manager;

	/**
	 * Construct
	 */
	public function __construct(ArticleManager $manager)
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

		## temp
		$parameters->exists('author_id') && $parameters->set('author', $this->manager->author->findOneBy(['id' => $parameters->get('author_id')]));

		if (!$entity->exists)
			$errors = $errors->merge($this->validateRequired($parameters));

		$errors = $errors->merge($this->validateValue($parameters));

		return $errors;
	}

	/**
	 * Validate "required" values
	 *
	 * @param EntityContract $entity
	 * @param ParameterBag $parameters
	 *
	 * @return Collection
	 */
	public function validateRequired(ParameterBag $parameters)
	{
		$errors = new Collection();

		!$parameters->exists('title') && $errors->push(new Exceptions\ArticleTitleNotDefinedException($parameters->get('title')));
		!$parameters->exists('author') && $errors->push(new Exceptions\ArticleAuthorNotDefinedException($parameters->get('author')));

		return $errors;
	}

	/**
	 * Validate "not valid" values
	 *
	 * @param ParameterBag $parameters
	 *
	 * @return Collection
	 */
	public function validateValue(ParameterBag $parameters)
	{
		$errors = new Collection();

		$parameters->exists('title') && !$this->validTitle($parameters->get('title')) &&
			$errors->push(new Exceptions\ArticleTitleNotValidException($parameters->get('title')));


		$parameters->exists('author') && !$parameters->get('author') &&
			$errors->push(new Exceptions\ArticleAuthorNotValidException($parameters->get('author')));

		return $errors;
	}

	/**
	 * Validate name
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function validTitle($value)
	{
		return strlen($value) >= 3 && strlen($value) < 255;
	}

}
