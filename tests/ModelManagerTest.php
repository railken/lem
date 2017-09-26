<?php

namespace Railken\Laravel\Manager\Tests;

use Railken\Laravel\Manager\Tests\User\UserManager;
use stdClass;

class ModelManagerTest extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \Railken\Laravel\Manager\ManagerServiceProvider::class,
            \Railken\Laravel\Manager\Tests\User\UserServiceProvider::class,
        ];
    }

    /**
     * @expectedException Railken\Laravel\Manager\Exceptions\ModelMissingAuthorizerException
     */
    public function testModelMissingAuthorizerExceptionNull()
    {
        UserManager::authorizer(null);
        new UserManager();
    }

    /**
     * @expectedException Railken\Laravel\Manager\Exceptions\ModelMissingAuthorizerException
     */
    public function testModelMissingAuthorizerExceptionNotValid()
    {
        UserManager::authorizer(stdClass::class);
        new UserManager();
    }
}
