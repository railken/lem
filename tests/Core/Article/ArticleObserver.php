<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Railken\Laravel\Manager\Contracts\EntityContract;

class ArticleObserver
{
    /**
     * Listen to the entity retrieved event.
     *
     * @param EntityContract $entity
     */
    public function retrieved(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity creating event.
     *
     * @param EntityContract $entity
     */
    public function creating(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity created event.
     *
     * @param EntityContract $entity
     */
    public function created(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity updating event.
     *
     * @param EntityContract $entity
     */
    public function updating(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity updated event.
     *
     * @param EntityContract $entity
     */
    public function updated(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity saving event.
     *
     * @param EntityContract $entity
     */
    public function saving(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity saved event.
     *
     * @param EntityContract $entity
     */
    public function saved(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity deleting event.
     *
     * @param EntityContract $entity
     */
    public function deleting(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity deleted event.
     *
     * @param EntityContract $entity
     */
    public function deleted(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity restoring event.
     *
     * @param EntityContract $entity
     */
    public function restoring(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the entity restored event.
     *
     * @param EntityContract $entity
     */
    public function restored(EntityContract $entity)
    {
        // ..
    }
}
