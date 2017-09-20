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

    /**
     * Return a new instance of user bag
     *
     * @return Bag
     */
    public function getUserBag()
    {
        return new Bag(['email' => 'admin@admin.it', 'username' => 'admin', 'password' => microtime()]);
    }

    public function testBasics()
    {

        $um = new UserManager();

        # Testing validation
        $result = $um->create($this->getUserBag()->set('password', 'wrong'));
        $this->assertEquals("USER_PASSWORD_NOT_VALID", $result->getErrors()->first()->getCode());

        # Testing correct
        $resource = $um->create($this->getUserBag())->getResource();
        $this->assertEquals($this->getUserBag()->get('username'), $resource->username);
        $this->assertEquals($this->getUserBag()->get('email'), $resource->email);

        # Testing uniqueness
        $result = $um->create($this->getUserBag());
        $this->assertEquals("USER_EMAIL_NOT_UNIQUE", $result->getErrors()->first()->getCode());
    }

}