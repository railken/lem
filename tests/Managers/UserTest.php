<?php

namespace Railken\Lem\Tests\Managers;

use Railken\Lem\Support\Testing\TestableBaseTrait;
use Railken\Lem\Tests\App\Fakers\UserFaker;
use Railken\Lem\Tests\App\Managers\UserManager;
use Railken\Lem\Tests\Base;

class UserTest extends Base
{
    use TestableBaseTrait;

    /**
     * Manager class.
     *
     * @var string
     */
    protected $manager = UserManager::class;

    /**
     * Faker class.
     *
     * @var string
     */
    protected $faker = UserFaker::class;
}
