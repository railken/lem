<?php

namespace Railken\Lem\Tests\Attributes;

use Railken\Lem\Attributes\UrlAttribute;
use Railken\Lem\Tests\App\Models\Foo;
use Railken\Lem\Tests\Base;

class UrlAttributeTest extends Base
{
    public function testValidationUrlAttribute()
    {
        $attribute = new UrlAttribute();

        $entity = new Foo();
        $this->assertTrue($attribute->valid($entity, 'https://github.com'));
        $this->assertFalse($attribute->valid($entity, 'github.com'));
    }
}
