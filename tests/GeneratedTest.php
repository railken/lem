<?php

namespace Railken\Laravel\Manager\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Railken\Bag;
use Railken\Laravel\Manager\Generator;
use Railken\Laravel\Manager\Tests\Generated\Foo\FooManager;
use Railken\Laravel\Manager\Tests\Generated\Foo\FooParameterBag;
use Railken\Laravel\Manager\Tests\Generated\Foo\FooServiceProvider;

class GeneratedTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
    }

    protected function getPackageProviders($app)
    {
        return [
            \Railken\Laravel\Manager\ManagerServiceProvider::class,
        ];
    }

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $dotenv = new \Dotenv\Dotenv(__DIR__."/..", '.env');
        $dotenv->load();

        parent::setUp();

        Schema::dropIfExists('foo');

        Schema::create('foo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Return a new instance of user bag
     *
     * @return Bag
     */
    public function getUserBag()
    {
        return new Bag(['email' => 'test@test.net', 'username' => 'test123', 'password' => microtime()]);
    }

    /**
     * Test generate Command
     */
    public function testGenerate()
    {
        if (!File::exists(__DIR__."/Generated/Foo")) {
            $generator = new Generator();
            $generator->generate(__DIR__."/Generated", "Railken\Laravel\Manager\Tests\Generated\Foo");
        }

        $this->assertEquals(true, File::exists(__DIR__."/Generated/Foo"));
        (new FooServiceProvider($this->app))->register();

        $m = new FooManager();

        $bag = new FooParameterBag(['name' => 'a']);
        $this->assertEquals("FOO_NAME_NOT_VALID", $m->create($bag->set('name', ''))->getError()->getCode());
        $this->assertEquals(false, $m->create($bag->set('name', null))->ok());

        $foo = $m->create($bag->set('name', 'baar'))->getResource();
        $m->update($foo, $bag->set('name', 'fee'))->getResource();

        $foo_s = $m->findOneBy(['name' => 'fee']);

        $this->assertEquals($foo->id, $foo_s->id);
    }
}
