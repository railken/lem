<?php

namespace Railken\Laravel\Manager\Tests;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;


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

	}

    public function testBase()
    {


    }

}