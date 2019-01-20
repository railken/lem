<?php

namespace Railken\Lem\Support\Testing;

use Railken\Bag;
use Railken\Lem\Contracts\ManagerContract;
use Railken\Lem\Contracts\ResultContract;

trait TestableBaseTrait
{
    /**
     * Retrieve a new instance of the manager.
     *
     * @return \Railken\Lem\Contracts\ManagerContract
     */
    public function getManager()
    {
        $class = $this->manager;

        return new $class();
    }

    /**
     * Retrieve a bag that contains all parameters used in the test.
     *
     * @return \Railken\Bag
     */
    public function getParameters()
    {
        return $this->faker::make()->parameters();
    }

    /**
     * Perform a test.
     */
    public function testBasics()
    {
        $this->requiredParametersTest($this->getManager(), $this->getParameters());
        $this->commonTest($this->getManager(), $this->getParameters());
    }

    /**
     * @param \Railken\Lem\Contracts\ManagerContract $manager
     * @param \Railken\Bag                           $parameters
     */
    public function requiredParametersTest(ManagerContract $manager, Bag $parameters)
    {
        $attributes = [];

        foreach ($manager->getAttributes() as $attribute) {
            if ($attribute->getRequired()) {
                $attributes = array_merge($attributes, $attribute->getAliases());
            }
        }

        $result = $manager->create($parameters->only($attributes));
        $this->assertResultOrPrint($result);
    }

    /**
     * Common tests.
     *
     * @param \Railken\Lem\Contracts\ManagerContract $manager
     * @param \Railken\Bag                           $parameters
     */
    public function commonTest(ManagerContract $manager, Bag $parameters)
    {

        $result = $manager->create($parameters);
        $this->assertResultOrPrint($result);

        $resource = $result->getResource();
        $result = $manager->update($resource, $parameters);
        $this->assertResultOrPrint($result);

        $resource = $result->getResource();
        $this->assertEquals($resource->id, $manager->getRepository()->findOneById($resource->id)->id);

        $result = $manager->remove($resource);
        $this->assertResultOrPrint($result);
    }

    /**
     * Assert result and print if fails.
     *
     * @param bool \Railken\Lem\Contracts\ResultContract
     * @param bool $flag
     */
    public function assertResultOrPrint(ResultContract $result, $flag = true)
    {
        if ($result->ok() !== $flag) {
            print_r($result->getSimpleErrors());
        }

        $this->assertEquals($flag, $result->ok());
    }
}
