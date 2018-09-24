<?php

namespace Railken\Lem\Tests;

use Railken\Lem\Tests\App\Managers\UserManager;

class ManagerTest extends BaseTest
{
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
