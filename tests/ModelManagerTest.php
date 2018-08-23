<?php

namespace Railken\Laravel\Manager\Tests;

use Railken\Laravel\Manager\Tests\User\UserManager;

class ModelManagerTest extends \Orchestra\Testbench\TestCase
{
    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\ModelMissingRepositoryException
     */
    /*public function testModelMissingRepositoryExceptionNull()
    {
        (new UserManager())->setRepository(null)->initializeComponents();
    }*/

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\ModelMissingValidatorException
     */
    /*public function testModelMissingValidatorExceptionNull()
    {
        (new UserManager())->setValidator(null)->initializeComponents();
    }*/

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\ModelMissingAuthorizerException
     */
    /*public function testModelMissingAuthorizerExceptionNull()
    {
        (new UserManager())->setAuthorizer(null)->initializeComponents();
    }*/

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\ModelMissingSerializerException
     */
    /*public function testModelMissingSerializerExceptionNull()
    {
        (new UserManager())->setSerializer(null)->initializeComponents();
    }*/

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\ExceptionNotDefinedException
     */
    public function testExceptionNotDefinedException()
    {
        (new UserManager())->getException('WRONG_EXCEPTION_CODE');
    }

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\PermissionNotDefinedException
     */
    public function testPermissionNotDefinedException()
    {
        (new UserManager())->getPermission('WRONG_PERMISSION_CODE');
    }

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
}
