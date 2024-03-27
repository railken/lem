<?php

namespace Railken\Lem\Tests\Managers;

use Railken\Lem\Support\Testing\TestableBaseTrait;
use Railken\Lem\Tests\App\Fakers\FooFaker;
use Railken\Lem\Tests\App\Managers\FooManager;
use Railken\Lem\Tests\Base;

class FooTest extends Base
{
    use TestableBaseTrait;

    /**
     * Manager class.
     *
     * @var string
     */
    protected $manager = FooManager::class;

    /**
     * Faker class.
     *
     * @var string
     */
    protected $faker = FooFaker::class;
}
