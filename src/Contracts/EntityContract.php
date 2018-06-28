<?php

namespace Railken\Laravel\Manager\Contracts;

/**
 * All entities that are used in manager must be under this contract.
 *
 * @property public $id
 * @property public $exists
 * @property public $exists
 */
interface EntityContract
{

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function fill(array $attributes);


    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = []);

    /**
     * Delete the model from the database.
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete();
}
