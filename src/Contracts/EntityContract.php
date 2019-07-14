<?php

namespace Railken\Lem\Contracts;

/**
 * All entities that are used in manager must be under this contract.
 *
 * @property int  $id
 * @property bool $exists
 */
interface EntityContract
{
    /**
     * Fill the model with an array of attributes.
     *
     * @param array $attributes
     *
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function fill(array $attributes);

    /**
     * Save the model to the database.
     *
     * @param array $options
     *
     * @return bool
     */
    public function save(array $options = []);

    /**
     * Delete the model from the database.
     *
     * @return bool|null|int
     *
     * @throws \Exception
     */
    public function delete();

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray();

    /**
     * Get a new query builder for the model's table.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery();

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable();
}
