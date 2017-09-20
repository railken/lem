<?php

namespace Railken\Laravel\Manager\Tests;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

use Railken\Laravel\Manager\Tests\User\UserManager;
use Railken\Bag;

class BasicTest extends \Orchestra\Testbench\TestCase
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

    /**
     * Setup the test environment.
     */
    public function setUp()
    {

        $dotenv = new \Dotenv\Dotenv(__DIR__."/..", '.env');
        $dotenv->load();

        parent::setUp();

        Schema::dropIfExists('users');
        
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function testInstance()
    {

        $um = new UserManager();
        $bag = new Bag(['username' => 'admin', 'password' => 'admin', 'email' => 'admin@admin.it']);
        $result = $um->create($bag);
        $this->assertEquals(false, $result->ok());


        $bag->password = 'adminadmin';
        $resource = $um->create($bag)->getResource();

        $this->assertEquals('admin', $resource->username);
        $this->assertEquals('admin@admin.it', $resource->email);
    }

}