<?php

namespace Railken\Laravel\Manager\Tests;

use Railken\Laravel\Manager\Tests\User\UserManager;
use Railken\Laravel\Manager\Tests\User\User;
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
     * @expectedException Railken\Laravel\Manager\Exceptions\ModelMissingRepositoryException
     */
    public function testModelMissingRepositoryExceptionNull()
    {
        UserManager::repository(null);
        new UserManager(new User());
    }

    /**
     * @expectedException Railken\Laravel\Manager\Exceptions\ModelMissingRepositoryException
     */
    public function testModelMissingRepositoryExceptionNotValid()
    {
        UserManager::repository(stdClass::class);
        new UserManager(new User());
    }

    /**
     * @expectedException Railken\Laravel\Manager\Exceptions\ModelMissingValidatorException
     */
    public function testModelMissingValidatorExceptionNull()
    {
        UserManager::validator(null);
        new UserManager(new User());
    }

    /**
     * @expectedException Railken\Laravel\Manager\Exceptions\ModelMissingValidatorException
     */
    public function testModelMissingValidatorExceptionNotValid()
    {
        UserManager::validator(stdClass::class);
        new UserManager(new User());
    }

    /**
     * @expectedException Railken\Laravel\Manager\Exceptions\ModelMissingParametersException
     */
    public function testModelMissingParametersExceptionNull()
    {
        UserManager::parameters(null);
        new UserManager(new User());
    }

    /**
     * @expectedException Railken\Laravel\Manager\Exceptions\ModelMissingParametersException
     */
    public function testModelMissingParametersExceptionNotValid()
    {
        UserManager::parameters(stdClass::class);
        new UserManager(new User());
    }

    /**
     * @expectedException Railken\Laravel\Manager\Exceptions\ModelMissingAuthorizerException
     */
    public function testModelMissingAuthorizerExceptionNull()
    {
        UserManager::authorizer(null);
        new UserManager(new User());
    }

    /**
     * @expectedException Railken\Laravel\Manager\Exceptions\ModelMissingAuthorizerException
     */
    public function testModelMissingAuthorizerExceptionNotValid()
    {
        UserManager::authorizer(stdClass::class);
        new UserManager(new User());
    }
    /**
     * @expectedException Railken\Laravel\Manager\Exceptions\ModelMissingSerializerException
     */
    public function testModelMissingSerializerExceptionNull()
    {
        UserManager::serializer(null);
        new UserManager(new User());
    }

    /**
     * @expectedException Railken\Laravel\Manager\Exceptions\ModelMissingSerializerException
     */
    public function testModelMissingSerializerExceptionNotValid()
    {
        UserManager::serializer(stdClass::class);
        new UserManager(new User());
    }
}
