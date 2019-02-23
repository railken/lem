<?php

namespace Railken\Lem\Tests\Attributes;

use Railken\Bag;
use Railken\Lem\Attributes\UuidAttribute;
use Railken\Lem\Tests\App\Managers\FooManager;
use Railken\Lem\Tests\App\Models\Foo;
use Railken\Lem\Tests\BaseTest;

class UuidAttributeTest extends BaseTest
{
    public function testValidationYamlAttribute()
    {
        $attribute = new UuidAttribute();
        $attribute->setManager(new FooManager());

        $entity = new Foo();
        $this->assertEquals(0, $attribute->update($entity, (new Bag()))->count());
    }
}
