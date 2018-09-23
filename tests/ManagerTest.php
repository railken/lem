<?php

namespace Railken\Lem\Tests;

use Railken\Lem\Tests\Core\User;

class ManagerTest extends BaseTest
{
    /**
     * @expectedException \Railken\Lem\Exceptions\ModelMissingRepositoryException
     */
    /*public function testModelMissingRepositoryExceptionNull()
    {
        (new User\Manager())->setRepository(null)->initializeComponents();
    }*/

    /**
     * @expectedException \Railken\Lem\Exceptions\ModelMissingValidatorException
     */
    /*public function testModelMissingValidatorExceptionNull()
    {
        (new User\Manager())->setValidator(null)->initializeComponents();
    }*/

    /**
     * @expectedException \Railken\Lem\Exceptions\ModelMissingAuthorizerException
     */
    /*public function testModelMissingAuthorizerExceptionNull()
    {
        (new User\Manager())->setAuthorizer(null)->initializeComponents();
    }*/

    /**
     * @expectedException \Railken\Lem\Exceptions\ModelMissingSerializerException
     */
    /*public function testModelMissingSerializerExceptionNull()
    {
        (new User\Manager())->setSerializer(null)->initializeComponents();
    }*/

    /**
     * @expectedException \Railken\Lem\Exceptions\ExceptionNotDefinedException
     */
    public function testExceptionNotDefinedException()
    {
        (new User\Manager())->getException('WRONG_EXCEPTION_CODE');
    }

    /**
     * @expectedException \Railken\Lem\Exceptions\PermissionNotDefinedException
     */
    public function testPermissionNotDefinedException()
    {
        (new User\Manager())->getAuthorizer()->getPermission('WRONG_PERMISSION_CODE');
    }
}
