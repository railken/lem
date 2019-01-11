<?php

namespace Railken\Lem\Tests\Attributes;

use Railken\Lem\Attributes\YamlAttribute;
use Railken\Lem\Tests\App\Models\Foo;
use Railken\Lem\Tests\App\Managers\FooManager;
use Railken\Lem\Tests\BaseTest;
use Railken\Bag;

class YamlAttributeTest extends BaseTest
{
    public function testValidationYamlAttribute()
    {
        $attribute = new YamlAttribute('name');
        $attribute->setManager(new FooManager);

        $entity = new Foo();
        $this->assertTrue($attribute->valid($entity, 'foo:foo'));
        $this->assertFalse($attribute->valid($entity, '{foo:1}'));
        $this->assertEquals(0, $attribute->update($entity, (new Bag())->set('name', 'foo:foo'))->count());
        $this->assertEquals(1, $attribute->update($entity, (new Bag())->set('name', '{foo:1}'))->count());
        $this->assertEquals(0, $attribute->update($entity, (new Bag())->set('name', ['foo' => 'foo']))->count());
        $this->assertEquals(0, $attribute->update($entity, (new Bag())->set('name', (object)['foo' => 'foo']))->count());
    }
}
