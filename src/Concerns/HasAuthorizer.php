<?php

namespace Railken\Lem\Concerns;

use Railken\Lem\Contracts\AuthorizerContract;

trait HasAuthorizer
{
    /**
     * @var \Railken\Lem\Contracts\AuthorizerContract
     */
    public $authorizer;

    /**
     * Set a authorizer.
     *
     * @param \Railken\Lem\Contracts\AuthorizerContract $authorizer
     *
     * @return $this
     */
    public function setAuthorizer(AuthorizerContract $authorizer)
    {
        $this->authorizer = $authorizer;

        return $this;
    }

    /**
     * Retrieve the authorizer.
     *
     * @return \Railken\Lem\Contracts\AuthorizerContract
     */
    public function getAuthorizer(): AuthorizerContract
    {
        return $this->authorizer;
    }

    /**
     * Boot the component.
     *
     * @param array $classes
     */
    public function bootAuthorizer(array $classes)
    {
        $this->setAuthorizer(new $classes['authorizer']($this));
    }
}
