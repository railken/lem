<?php

namespace Railken\Laravel\Manager\Tests\Generated\Foo;

use Railken\Laravel\Manager\Contracts\EntityContract;

class FooObserver
{
    /**
     * Listen to the entity retrieved event.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     */
    public function retrieved(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity creating event.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     */
    public function creating(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity created event.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     */
    public function created(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity updating event.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     */
    public function updating(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity updated event.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     */
    public function updated(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity saving event.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     */
    public function saving(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity saved event.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     */
    public function saved(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity deleting event.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     */
    public function deleting(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity deleted event.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     */
    public function deleted(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity restoring event.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     */
    public function restoring(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity restored event.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     */
    public function restored(EntityContract $entity)
    {
        // ..
    }
}
