<?php

namespace Railken\Lem\Tests;

use Railken\Lem\Tests\User\UserManager;

class ManagerTest extends BaseTest
{
    /**
     * @expectedException \Railken\Lem\Exceptions\ModelMissingRepositoryException
     */
    /*public function testModelMissingRepositoryExceptionNull()
    {
        (new UserManager())->setRepository(null)->initializeComponents();
    }*/

    /**
     * @expectedException \Railken\Lem\Exceptions\ModelMissingValidatorException
     */
    /*public function testModelMissingValidatorExceptionNull()
    {
        (new UserManager())->setValidator(null)->initializeComponents();
    }*/

    /**
     * @expectedException \Railken\Lem\Exceptions\ModelMissingAuthorizerException
     */
    /*public function testModelMissingAuthorizerExceptionNull()
    {
        (new UserManager())->setAuthorizer(null)->initializeComponents();
    }*/

    /**
     * @expectedException \Railken\Lem\Exceptions\ModelMissingSerializerException
     */
    /*public function testModelMissingSerializerExceptionNull()
    {
        (new UserManager())->setSerializer(null)->initializeComponents();
    }*/

    /**
     * @expectedException \Railken\Lem\Exceptions\ExceptionNotDefinedException
     */
    public function testExceptionNotDefinedException()
    {
        (new UserManager())->getException('WRONG_EXCEPTION_CODE');
    }

    /**
     * @expectedException \Railken\Lem\Exceptions\PermissionNotDefinedException
     */
    public function testPermissionNotDefinedException()
    {
        (new UserManager())->getAuthorizer()->getPermission('WRONG_PERMISSION_CODE');
    }
}
