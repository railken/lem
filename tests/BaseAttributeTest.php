<?php

namespace Railken\Lem\Tests;

use Railken\Lem\Tests\App\Managers\UserManager;

class BaseAttributeTest extends BaseTest
{
    /**
     * Get attribute.
     *
     * @return \Railken\Lem\Contracts\AttributeContract
     */
    public function getAttribute()
    {
        return \Railken\Lem\Attributes\EmailAttribute::make()->setManager(new UserManager());
    }

    public function testExceptionNotDefinedException()
    {
        $this->expectException(\Railken\Lem\Exceptions\ExceptionNotDefinedException::class);

        $this->getAttribute()->getException('WRONG_EXCEPTION_CODE');
    }

    public function testPermissionNotDefinedException()
    {
        $this->expectException(\Railken\Lem\Exceptions\PermissionNotDefinedException::class);
        
        $this->getAttribute()->getPermission('WRONG_PERMISSION_CODE');
    }
}
