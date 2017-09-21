<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\Exceptions as Exceptions;
use Illuminate\Support\Collection;

class ResultExecute
{

    
    /**
     * A collection of resources altered during operation
     *
     * @var Collection
     */
    protected $resources;

    /**
     * List of errors happened during execution
     *
     * @var Collection
     */
    protected $errors;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->setResources(new Collection());
        $this->setErrors(new Collection());
    }

    /**
     * Set resources
     *
     * @param Collection $resources
     */
    public function setResources($resources)
    {
        $this->resources = $resources;
    }

    /**
     * Get resources
     *
     * @return Collection
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * Get first resource
     *
     * @return mixed
     */
    public function getResource()
    {
        return $this->getResources()->first();
    }

    /**
     * Set errors
     *
     * @param Collection
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * Get errors
     *
     * @return Collection
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get error
     *
     * @return Exception
     */
    public function getError()
    {
        return $this->getErrors()->first();
    }

    /**
     * Add errors
     *
     * @param Collection $errors
     *
     * @return this
     */
    public function addErrors(Collection $errors = null)
    {
        $this->errors = $this->getErrors()->merge($errors);

        return $this;
    }


    /**
     * Return if result has been executed without errors
     *
     * @return boolean
     */
    public function success()
    {
        return $this->getErrors()->count() == 0;
    }

    /**
     * Return if result has been executed without errors
     *
     * @return boolean
     */
    public function ok()
    {
        return $this->success();
    }   

    /**
     * Retrieve a "short" version of errors
     *
     * @return Collection
     */
    public function getSimpleErrors()
    {
        return $this->getErrors()->map(function ($error) {
            return $error->toArray();
        });
    }

    /**
     * Is authorized
     *
     * @return boolean
     */
    public function isAuthorized()
    {
        return $this->getErrors()->search(function ($error, $key) {
            return $error instanceof Exceptions\ModelNotAuthorizedExceptionContract;
        }) === false;
    }
}