<?php

namespace Railken\Lem\Tests\Attributes;

use Railken\Lem\Attributes\NumberAttribute;
use Railken\Lem\Tests\App\Models\Foo;
use Railken\Lem\Tests\Base;

class NumberAttributeTest extends Base
{
    public function testValidationNumberAttribute()
    {
        $attribute = new NumberAttribute();

        $entity = new Foo();
        $this->assertTrue($attribute->valid($entity, 7));
        $this->assertFalse($attribute->valid($entity, 'A'));
    }
}
