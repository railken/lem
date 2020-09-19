# Lem

[![Actions Status](https://github.com/railken/lem/workflows/test/badge.svg)](https://github.com/railken/lem/actions)

A precise way to structure your logic to improve readability and maintainability of your code when building API.

# Requirements

PHP 7.1 and later.

## Installation

You can install it via [Composer](https://getcomposer.org/) by typing the following command:

```bash
composer require railken/lem
```

The package will automatically register itself.

## Usage

First you need to generate a new structure folder, use:

`php artisan railken:make:manager app App\Foo`.

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

How can you get an Error during an operation? An error occurs when a validation or authorization fails. The cool thing about it is that you have the total control during each process: using [Validator](#modelvalidator) and [Authorizer](#modelauthorizer). When you're retrieving errors you're receiving a Collection, it goes pretty well when you're developing an api. Here's an example
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
use Railken\Lem\Contracts\AgentContract;

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
use Railken\Lem\Agents\SystemAgent;

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



See [Authorizer](#modelauthorizer) for more explanations.
### Commands

- Generate a new set of files `php artisan railken:make:manager [path] [namespace]`. E.g. php artisan railken:make:manager App "App\Foo"


### Manager
This is the main class, all the operations are performed using this: creating, updating, deleting, retrieving. This class is composed of components which are: validator, repository, authorizer, parameters, serializer

See [Manager](https://github.com/railken/lem/blob/master/src/Manager.php).
```php
namespace App\Foo;

use Railken\Lem\Manager;
use Railken\Lem\Contracts\AgentContract;

class FooManager extends Manager
{
    /**
     * Class name entity
     *
     * @var string
     */
    public $entity = Foo::class;

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
use Railken\Lem\Contracts\EntityContract;

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

### Repository
This is a Repository, the concept is very similar to the Repository of Symfony, code all your queries here.

See [Repository](https://github.com/railken/lem/blob/master/src/Repository.php) for more information.

```php
namespace App\Foo;

use Railken\Lem\Repository;

class FooRepository extends Repository
{

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

### Validator
Here comes the validator, and again it's very simple. validate() is called whenever a create/update operation is called.
Remember: always return the collection of errors. You can of course add a specific library for validation and use it here.


```php
namespace App\Foo;

use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Contracts\ValidatorContract;
use Railken\Lem\ParameterBag;
use Illuminate\Support\Collection;
use App\Foo\Exceptions as Exceptions;


class FooValidator implements ValidatorContract
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

### Serializer
This class will serialize your model

```php
namespace App\Foo;

use Railken\Lem\Contracts\SerializerContract;
use Railken\Lem\Contracts\EntityContract;
use Illuminate\Support\Collection;
use Railken\Bag;

class FooSerializer implements SerializerContract
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
