# Laravel Manager

Organize your project with a defined structure to handle Models in laravel: manipulate, validate, authorize, serialize, etc...

## Requirements

PHP 7.0.0 or later.

## Composer

You can install it via [Composer](https://getcomposer.org/) by typing the following command:

```bash
composer require railken/laravel-manager
```

## Installation
- Add the service provider to the `providers` array in `config/app.php`

```php
Railken\Laravel\Manager\ManagerServiceProvider::class,
```

## Usage

A simple usage looks like this:
```php
$manager = new FooManager();
$result = $manager->create($manager->parameters(['name' => 'foo']));

if ($result->ok()) {
    $foo = $result->getResource(); 
} else {
    $result->getErrors(); // All errors goes here.
} 

```

How can you get an Error during an operation? An error occurs when a validation or authorization fails. The cool thing about it is that you have the total control during each process: using with [ModelValidator](###ModelValidator) and [ModalAuthorizer](###ModelAuthorizer). When you're retrieving errors you're receiving a Collection, it goes pretty well when you're developing an api. Here's an example
```php
$manager = new FooManager();
$result = $manager->create($manager->parameters(['name' => 'f']));

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
                [code] => USER_NAME_NOT_DEFINED
                [attribute] => name
                [message] => The name isn't valid
                [value] => f
            )
    )
*/
```
### Commands

- Generate a new set of files `php artisan railken:make:manager [path] [namespace]`. An example would be `php artisan railken:make:manager src/Core Core/User`. 

### ModelManager
This is the main class, all the operations are performed using this: create, update, retrieve, remove

See [ModelRepository](https://github.com/railken/laravel-manager/blob/master/src/ModelManager.php) for more information.
```php
namespace Core\Foo;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\ParameterBag;

use Railken\Laravel\Manager\Tests\Generated\Foo\Foo;

class FooManager extends ModelManager
{

    /**
     * Construct
     */
    public function __construct(AgentContract $agent = null)
    {
        $this->repository = new FooRepository($this);
        $this->authorizer = new FooAuthorizer($this);
        $this->validator = new FooValidator($this);
        $this->serializer = new FooSerializer($this);

        parent::__construct($agent);
    }

    /**
     * Fill the entity
     *
     * @param EntityContract $entity
     * @param FooParameterBag $parameters
     *
     * @return EntityContract
     */
    public function fill(EntityContract $entity, ParameterBag $parameters)
    {
        $parameters = $parameters->only(['name']);

        return parent::fill($entity, $parameters);
    }
}
```

### Model
This is the Eloquent Model, nothing changes, excepts for an interface

```php
namespace Core\Foo;

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
namespace Core\Foo;

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
This is a [Bag](https://github.com/railken/bag).

```php
namespace Core\Foo;

use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\ParameterBag;

class FooParameterBag extends ParameterBag
{
        
    /**
     * Filter current bag using agent
     *
     * @param AgentContract $agent
     *
     * @return this
     */
    public function filterByAgent(AgentContract $agent)
    {  
        return $this;
    }
        
    /**
     * Filter current bag using agent for a search
     *
     * @param AgentContract $agent
     *
     * @return this
     */
    public function filterSearchableByAgent(AgentContract $agent)
    {  
        return $this;
    }
}

```

###ModelAuthorizer
Has you can see this class has only this method and what it does is a simple bridge between the ModelManager and the ModelPolicy. So all the "rules" for authorization are defined in the ModelPolicy.

You can leave this as is it, or change and used another method for authorization.

```php
namespace Core\Foo;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ParameterBag;
use Railken\Laravel\Manager\Contracts\ModelAuthorizerContract;
use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Tests\Generated\Foo\Exceptions as Exceptions;

class FooAuthorizer implements ModelAuthorizerContract
{

    /**
     * Authorize
     *
     * @param string $operation
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function can(string $operation, EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        !$this->manager->agent->can($operation, $entity) && $errors->push(new Exceptions\FooNotAuthorizedException($entity));

        return $errors;
    }

}


```

###ModelValidator
Here comes the validator, and again it's very simple. validate() is called whenever an create/update are called.
Remember: always return the collection of errors.

```php
namespace Core\Foo;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\ModelValidatorContract;
use Railken\Laravel\Manager\ParameterBag;
use Illuminate\Support\Collection;
use Core\Foo\Exceptions as Exceptions;


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

    /**
     * Validate "required" values
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateRequired(ParameterBag $parameters)
    {
        $errors = new Collection();

        !$parameters->exists('name') && $errors->push(new Exceptions\FooNameNotDefinedException($parameters->get('name')));

        return $errors;
    }

    /**
     * Validate "not valid" values
     *
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateValue(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        $parameters->exists('name') && !$this->validName($parameters->get('name')) && 
            $errors->push(new Exceptions\FooNameNotValidException($parameters->get('name')));


        return $errors;
    }

    /**
     * Validate name
     *
     * @param string $name
     *
     * @return boolean
     */
    public function validName($name)
    {
        return $name === null || (strlen($name) >= 3 && strlen($name) < 255);
    }

}

```