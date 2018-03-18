<?php

namespace Railken\Laravel\Manager\Tests;

use Railken\Laravel\Manager\Tests\User\UserManager;

class ModelAttributeTest extends \Orchestra\Testbench\TestCase
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
     * Get attribute.
     *
     * @return Attribute
     */
    public function getAttribute()
    {
        return new \Railken\Laravel\Manager\Tests\User\Attributes\Email\EmailAttribute(new UserManager());
    }

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\ExceptionNotDefinedException
     */
    public function testExceptionNotDefinedException()
    {
        $this->getAttribute()->getException('WRONG_EXCEPTION_CODE');
    }

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\PermissionNotDefinedException
     */
    public function testPermissionNotDefinedException()
    {
        $this->getAttribute()->getPermission('WRONG_PERMISSION_CODE');
    }
}
