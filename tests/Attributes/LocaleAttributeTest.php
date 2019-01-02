<?php

namespace Railken\Lem\Tests\Attributes;

use Railken\Lem\Attributes\LocaleAttribute;
use Railken\Lem\Tests\App\Models\Foo;
use Railken\Lem\Tests\BaseTest;

class LocaleAttributeTest extends BaseTest
{
    public function testValidationUrlAttribute()
    {
        $attribute = new LocaleAttribute();

        $entity = new Foo();
        $this->assertTrue($attribute->valid($entity, 'en_US'));
        $this->assertFalse($attribute->valid($entity, 'Wrong'));
    }
}
