<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\ModelContract;
use Railken\Laravel\Manager\Exceptions\InvalidParamValueException;
use Railken\Laravel\Manager\Exceptions\MissingParamException;
use Railken\Laravel\Manager\Exceptions\ModelByIdNotFoundException;
use DB;
use Exception;


abstract class ModelManager
{

	/**
	 * @var array
	 */
	protected $vars = [];

	/**
	 * @var queue
	 */
	public $queue = [];

	/**
	 * Construct
	 *
	 */
	public function __construct()
	{
		$this->vars = collect();
	}

	/**
	 * Retrieve repository
	 *
	 * @return Railken\Laravel\Manager\RepositoryModel
	 */
	public function getRepository()
	{
		return $this->repository;
	}

	/**
	 * Find
	 *
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function find($params)
	{
		return $this->getRepository()->find($params);
	}

	/**
	 * Find where in
	 *
	 * @param array $params
	 *
	 * @return Collection ?
	 */
	public function findWhereIn(array $params)
	{
		return $this->getRepository()->findWhereIn($params);
	}

	/**
	 * Create a new ModelContract given array
	 *
	 * @param array $params
	 *
	 * @return Railken\Laravel\Manager\ModelContract
	 */
	public function create(array $params)
	{

		DB::beginTransaction();

		try {

			$entity = $this->getRepository()->newEntity();
			$this->update($entity, $params);
			$this->save($entity);
			DB::commit();

		} catch (Exception $e) {

			DB::rollBack();
			throw $e;
		}

		return $entity;
	}

	/**
	 * Update a ModelContract given array
	 *
	 * @param array $params
	 *
	 * @return Railken\Laravel\Manager\ModelContract
	 */
	public function update(ModelContract $entity, array $params)
	{

		DB::beginTransaction();

		try {

			$this->fill($entity, $params);
			$this->save($entity);
			DB::commit();

		} catch (Exception $e) {

			DB::rollBack();
			throw $e;
		}

		return $entity;
	}



	/**
	 * Remove a ModelContract
	 *
	 * @param Railken\Laravel\Manager\ModelContract $entity
	 *
	 * @return void
	 */
	public function delete(ModelContract $entity)
	{
		return $entity->delete();
	}

	/**
	 * Save the entity
	 *
	 * @param  Railken\Laravel\Manager\ModelContract $entity
	 *
	 * @return ModelContract
	 */
	public function save(ModelContract $entity)
	{
		return $entity->save();
	}


	/**
	 * Fill entity ModelContract with array
	 *
	 * @param Railken\Laravel\Manager\ModelContract $entity
	 * @param array $params
	 *
	 * @return void
	 */
	public function fill(ModelContract $entity, array $params)
	{
		$entity->fill($params);
		return $entity;
	}

	/**
	 * Fill an attribute of relation Many to One given id or entity
	 *
	 * @param ModelContract $entity
	 * @param ModelManager $manager
	 * @param array $params
	 * @param string $attribute
	 * @param string $attribute_id
	 *
	 * @return $entity
	 */
	public function fillManyToOneById(ModelContract $entity, ModelManager $manager, $params, $attribute, $attribute_id = null)
	{

		if ($attribute_id == null)
			$attribute_id = $attribute."_id";

		if (isset($params[$attribute_id])) {

			$value = $manager->getRepository()->findById($params[$attribute_id]);

			if (!$value)
				throw new ModelByIdNotFoundException($attribute_id, $params[$attribute_id]);

			$params[$attribute] = $value;

		}

		if (isset($params[$attribute])) {
			$value = $params[$attribute];
			$entity->$attribute_id = $params[$attribute]->id;
			$this->vars[$attribute] = $value;
		}

		return $value;
	}

	/**
	 * Convert entity to array
	 *
	 * @param  Railken\Laravel\Manager\ModelContract $entity
	 *
	 * @return array
	 */
	abstract public function toArray(ModelContract $entity);


	/**
	 * Remove multiple ModelContract
	 *
	 * @param array $entities
	 *
	 * @return void
	 */
	public function deleteMultiple($entities)
	{
		foreach ($entities as $entity) {
			$this->delete($entity);
		}
	}

	/**
	 * Throw an exception if a value is invalid
	 *
	 * @param string $name
	 * @param string $value
	 * @param mixed $accepted
	 *
	 * @return void
	 */
	public function throwExceptionInvalidParamValue($name, $value, $accepted)
	{
		if (is_array($accepted)) {
			if (!in_array($value, $accepted))
		        throw new InvalidParamValueException("Invalid value {$value} for param {$name}. Accepted: ".implode($accepted, ","));
		}
	}

	/**
	 * Throw an exception if a parameter is null
	 *
	 * @param array $params
	 *
	 * @return void
	 */
	public function throwExceptionParamsNull($params)
	{
	    foreach($params as $name => $value) {
	        if($value == null) {
	            throw new MissingParamException("Missing parameter: {$name}");
	        }
	    }
	}

	/**
	 * Get only specific params
	 *
	 * @param array $params
	 * @param array $requested
	 *
	 * @return array
	*/
	public function getOnlyParams(array $params, array $requested)
	{
		return (array_intersect_key($params, array_flip($requested)));
	}


	/**
	 * Execute queue
	 *
	 * @return null
	 */
	public function executeQueue()
	{
		foreach ($this->getQueue() as $queue) {
			$queue();
		}

		$this->setQueue([]);
	}
	
	/**
	 * Add an operation to queue
	 *
	 * @param Closure $closure
	 *
	 * @return this
	 */
	public function addQueue(\Closure $closure)
	{
		$this->queue[] = $closure;
	}

	/**
	 * Retrieve all queue
	 *
	 * @return array
	 */
	public function getQueue()
	{
		return $this->queue;
	}
	
	/**
	 * Add an operation to queue
	 *
	 * @param array $queue
	 *
	 * @return array
	 */
	public function setQueue($queue)
	{
		$this->queue = $queue;
	}
}
