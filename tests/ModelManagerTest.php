<?php

namespace Railken\Laravel\Manager\Tests;

use Railken\Laravel\Manager\Tests\User\UserManager;
use stdClass;

class ModelManagerTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [

            \Railken\Laravel\Manager\ManagerServiceProvider::class,
            \Railken\Laravel\Manager\Tests\User\UserServiceProvider::class,
            \Railken\Laravel\Manager\Tests\AppServiceProvider::class,
        ];
    }

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\ModelMissingRepositoryException
     */
    public function testModelMissingRepositoryExceptionNull()
    {
        UserManager::repository(null);
        new UserManager();
    }

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\ModelMissingRepositoryException
     */
    public function testModelMissingRepositoryExceptionNotValid()
    {
        UserManager::repository(stdClass::class);
        new UserManager();
    }

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\ModelMissingValidatorException
     */
    public function testModelMissingValidatorExceptionNull()
    {
        UserManager::validator(null);
        new UserManager();
    }

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\ModelMissingValidatorException
     */
    public function testModelMissingValidatorExceptionNotValid()
    {
        UserManager::validator(stdClass::class);
        new UserManager();
    }

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\ModelMissingParametersException
     */
    public function testModelMissingParametersExceptionNull()
    {
        UserManager::parameters(null);
        new UserManager();
    }

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\ModelMissingParametersException
     */
    public function testModelMissingParametersExceptionNotValid()
    {
        UserManager::parameters(stdClass::class);
        new UserManager();
    }

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\ModelMissingAuthorizerException
     */
    public function testModelMissingAuthorizerExceptionNull()
    {
        UserManager::authorizer(null);
        new UserManager();
    }

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\ModelMissingAuthorizerException
     */
    public function testModelMissingAuthorizerExceptionNotValid()
    {
        UserManager::authorizer(stdClass::class);
        new UserManager();
    }

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\ModelMissingSerializerException
     */
    public function testModelMissingSerializerExceptionNull()
    {
        UserManager::serializer(null);
        new UserManager();
    }

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\ModelMissingSerializerException
     */
    public function testModelMissingSerializerExceptionNotValid()
    {
        UserManager::serializer(stdClass::class);
        new UserManager();
    }


    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\ExceptionNotDefinedException
     */
    public function testExceptionNotDefinedException()
    {
        (new UserManager())->getException("WRONG_EXCEPTION_CODE");
    }

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\PermissionNotDefinedException
     */
    public function testPermissionNotDefinedException()
    {
        (new UserManager())->getPermission("WRONG_PERMISSION_CODE");
    }
}
