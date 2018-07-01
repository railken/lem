<?php

namespace Railken\Laravel\Manager\Contracts;

use Illuminate\Support\Collection;

interface ResultContract
{
    /**
     * Set resources.
     *
     * @param Collection $resources
     */
    public function setResources($resources);

    /**
     * Get resources.
     *
     * @return Collection
     */
    public function getResources();

    /**
     * Get first resource.
     *
     * @return mixed
     */
    public function getResource();

    /**
     * Set errors.
     *
     * @param Collection $errors
     *
     * @return $this
     */
    public function setErrors($errors);

    /**
     * Get errors.
     *
     * @return Collection
     */
    public function getErrors();

    /**
     * Get error.
     *
     * @return mixed
     */
    public function getError($index = 0);

    /**
     * Add errors.
     *
     * @param Collection $errors
     *
     * @return $this
     */
    public function addErrors(Collection $errors);

    /**
     * Return if result has been executed without errors.
     *
     * @return bool
     */
    public function success();
    /**
     * Return if result has been executed without errors.
     *
     * @return bool
     */
    public function ok();

    /**
     * Retrieve a "short" version of errors.
     *
     * @return Collection
     */
    public function getSimpleErrors();
}
