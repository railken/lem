<?php

namespace Railken\Lem\Tests;

use Railken\Lem\Tests\Core\User;

class ManagerTest extends BaseTest
{
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
