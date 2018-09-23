<?php

namespace Railken\Lem\Tests;

use Railken\Lem\Tests\Core\User;

class BaseAttributeTest extends BaseTest
{
    /**
     * Get attribute.
     *
     * @return \Railken\Lem\Contracts\AttributeContract
     */
    public function getAttribute()
    {
        return \Railken\Lem\Attributes\EmailAttribute::make()->setManager(new User\Manager());
    }

    /**
     * @expectedException \Railken\Lem\Exceptions\ExceptionNotDefinedException
     */
    public function testExceptionNotDefinedException()
    {
        $this->getAttribute()->getException('WRONG_EXCEPTION_CODE');
    }

    /**
     * @expectedException \Railken\Lem\Exceptions\PermissionNotDefinedException
     */
    public function testPermissionNotDefinedException()
    {
        $this->getAttribute()->getPermission('WRONG_PERMISSION_CODE');
    }
}
