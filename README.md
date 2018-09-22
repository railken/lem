# Entity Manager Laravel

[![Build Status](https://travis-ci.org/railken/entity-manager-laravel.svg?branch=master)](https://travis-ci.org/railken/entity-manager-laravel)

A precise way to structure your logic to improve readability and maintainability of your code when building API.

## Requirements

PHP 7.0.0 and later.

## Composer

You can install it via [Composer](https://getcomposer.org/) by typing the
following command:

```bash
composer require railken/laravel-manager
```

## Installation
- Add the service provider to the `providers` array in `config/app.php`

```php
Railken\Laravel\Manager\ManagerServiceProvider::class,
```

## Usage

First you need to generate a new structure folder, use:

`php artisan railken:make:manager App App\Foo`.

Than, add `App\Foo\FooServiceProvider::class` in config/app.php.

Now you can use it.
```php
use App\Foo\FooManager;

$manager = new FooManager();
$result = $manager->create(['name' => 'foo']);

if ($result->ok()) {
    $foo = $result->getResource();
} else {
    $result->getErrors(); // All errors go here.
}

```

How can you get an Error during an operation? An error occurs when a validation or authorization fails. The cool thing about it is that you have the total control during each process: using [ModelValidator](#modelvalidator) and [ModelAuthorizer](#modelauthorizer). When you're retrieving errors you're receiving a Collection, it goes pretty well when you're developing an api. Here's an example
```php
$manager = new FooManager();
$result = $manager->create(['name' => 'f'));

print_r($result->getErrors()->toArray());
/*
Array
    (
        [0] => Array
            (
                [code] => FOO_TITLE_NOT_DEFINED
                [attribute] => title
                [message] => The title is required
                [value] =>
            )

        [1] => Array
            (
                [code] => FOO_NAME_NOT_DEFINED
                [attribute] => name
                [message] => The name isn't valid
                [value] => f
            )
    )
*/
```



So, what about the authorization part? First we have to edit the class User.

```php
use Railken\Laravel\Manager\Contracts\AgentContract;

class User implements AgentContract
{
    public function can($permission, $arguments = [])
    {
        return true;
    }
}

```


You can set the method can as you wish, it's better if a permission library is used such as https://github.com/Zizaco/entrust or https://github.com/spatie/laravel-permission.

If no system permission is needed simply leave return true.

```php
use Railken\Laravel\Manager\Agents\SystemAgent;

$manager = new FooManager(Auth::user());
$result = $manager->create(['title' => 'f']);

print_r($result->getErrors()->toArray());
/*
Array
    (
        [0] => Array
            (
                [code] => FOO_TITLE_NOT AUTHORIZED
                [attribute] => title
                [message] => You're not authorized to interact with title, missing foo.attributes.title.fill permission
                [value] =>
            )
    )
*
```

"foo.attributes.title.fill" is passed to User::can() and if the return is false the result will contains errors.

Note: if you don't set any agent, the SystemAgent will be used (all granted).



See [ModelAuthorizer](#modelauthorizer) for more explanations.
### Commands

- Generate a new set of files `php artisan railken:make:manager [path] [namespace]`. E.g. php artisan railken:make:manager App "App\Foo"
- Generate a new attribute  `php artisan railken:make:manager-attribute [path] [namespace] [attribute]`.  E.g. php artisan railken:make:manager-attribute App "App\Foo" Title


### ModelManager
This is the main class, all the operations are performed using this: creating, updating, deleting, retrieving. This class is composed of components which are: validator, repository, authorizer, parameters, serializer

See [ModelManager](https://github.com/railken/laravel-manager/blob/master/src/ModelManager.php).
```php
namespace App\Foo;

use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Contracts\AgentContract;

class FooManager extends ModelManager
{

    /**
     * Construct
     *
     * @param AgentContract|null $agent
     */
    public function __construct(AgentContract $agent = null)
    {
        parent::__construct($agent);
    }
}

```

### Model
This is the Eloquent Model, nothing changes, excepts for an interface

```php
namespace App\Foo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Railken\Laravel\Manager\Contracts\EntityContract;

class Foo extends Model implements EntityContract
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'foo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
```

### ModelRepository
This is a Repository, the concept is very similar to the Repository of Symfony, code all your queries here.

See [ModelRepository](https://github.com/railken/laravel-manager/blob/master/src/ModelRepository.php) for more information.

```php
namespace App\Foo;

use Railken\Laravel\Manager\ModelRepository;

class FooRepository extends ModelRepository
{

    /**
     * Class name entity
     *
     * @var string
     */
    public $entity = Foo::class;

    /**
     * Custom method
     *
     * @param string $name
     *
     * @return Foo
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(['name' => $name]);
    }

}

```

### ModelParameterBag
This is a [Bag](https://github.com/railken/bag). This will contain all methods to filter attributes of a Model.
Use filterWrite to filter the bag before crearting/updating.
Use filterRead to filter the bag before retrieving.
See an [example](https://github.com/railken/laravel-manager/blob/master/tests/Core/Comment/CommentParameterBag.php)

```php
namespace App\Foo;

use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\Contracts\SystemAgentContract;
use Railken\Laravel\Manager\Contracts\GuestAgentContract;
use Railken\Laravel\Manager\Contracts\UserAgentContract;
use Railken\Laravel\Manager\ParameterBag;

class FooParameterBag extends ParameterBag
{

	/**
	 * Filter current bag using agent
	 *
	 * @param AgentContract $agent
	 *
	 * @return $this
	 */
	public function filterWrite(AgentContract $agent)
	{

		$this->filter(['name']);

		return $this;
	}

	/**
	 * Filter current bag using agent for a search
	 *
	 * @param AgentContract $agent
	 *
	 * @return $this
	 */
	public function filterRead(AgentContract $agent)
	{
		$this->filter(['id', 'name', 'created_at', 'updated_at']);

		return $this;
	}

}

```

### ModelValidator
Here comes the validator, and again it's very simple. validate() is called whenever a create/update operation is called.
Remember: always return the collection of errors. You can of course add a specific library for validation and use it here.


```php
namespace App\Foo;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\ModelValidatorContract;
use Railken\Laravel\Manager\ParameterBag;
use Illuminate\Support\Collection;
use App\Foo\Exceptions as Exceptions;


class FooValidator implements ModelValidatorContract
{

    /**
     * Validate
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validate(EntityContract $entity, ParameterBag $parameters)
    {

        $errors = new Collection();

        if (!$entity->exists)
            $errors = $errors->merge($this->validateRequired($parameters));

        $errors = $errors->merge($this->validateValue($entity, $parameters));

        return $errors;
    }

}


```

### ModelSerializer
This class will serialize your model

```php
namespace App\Foo;

use Railken\Laravel\Manager\Contracts\ModelSerializerContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Illuminate\Support\Collection;
use Railken\Bag;

class FooSerializer implements ModelSerializerContract
{

	/**
	 * Serialize entity
	 *
	 * @param EntityContract $entity
	 *
	 * @return Bag
	 */
    public function serialize(EntityContract $entity, Collection $select)
    {
        $bag = (new Bag($entity->toArray()))->only($select->toArray());

		return $bag;
	}

}
```


### ModelServiceProvider
This class is very important, it will load all the components,
Load this provider with all others in your config/app.php

```php
namespace App\Foo;

use Gate;
use Illuminate\Support\ServiceProvider;

class FooServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        Foo::observe(FooObserver::class);
        Gate::policy(Foo::class, FooPolicy::class);

        FooManager::repository(FooRepository::class);
        FooManager::serializer(FooSerializer::class);
        FooManager::parameters(FooParameterBag::class);
        FooManager::validator(FooValidator::class);
        FooManager::authorizer(FooAuthorizer::class);
    }
}

```
