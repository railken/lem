<?php

namespace Railken\Lem\Tests;

use Railken\Lem\Tests\App\Managers\UserManager;

class ManagerTest extends Base
{
    public function testExceptionNotDefinedException()
    {
        $this->expectException(\Railken\Lem\Exceptions\ExceptionNotDefinedException::class);

        (new UserManager())->getException('WRONG_EXCEPTION_CODE');
    }

    /**
     * @expectedException
     */
    public function testPermissionNotDefinedException()
    {
        $this->expectException(\Railken\Lem\Exceptions\PermissionNotDefinedException::class);

        (new UserManager())->getAuthorizer()->getPermission('WRONG_PERMISSION_CODE');
    }
}
