<?php

namespace Railken\Lem;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Railken\Bag;
use Railken\Lem\Contracts\AuthorizerContract;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Contracts\ManagerContract;
use Railken\Lem\Tokens;

class Authorizer implements AuthorizerContract
{
    use Concerns\HasManager;
    use Concerns\HasPermissions;
    use Concerns\HasExceptions;
    use Concerns\CallMethods;

    /**
     * @var array
     */
    protected $exceptions = [
        Tokens::NOT_AUTHORIZED => Exceptions\ModelNotAuthorizedException::class,
    ];

    /**
     * List of all permissions.
     *
     * @var array
     */
    protected $permissions = [];

    /**
     * Construct.
     *
     * @param ManagerContract $manager
     */
    public function __construct(ManagerContract $manager)
    {
        $this->manager = $manager;

        $name = $manager->getName();

        $this->permissions = [
            Tokens::PERMISSION_CREATE => $name.'.create',
            Tokens::PERMISSION_UPDATE => $name.'.update',
            Tokens::PERMISSION_SHOW   => $name.'.show',
            Tokens::PERMISSION_REMOVE => $name.'.remove'
        ];

    }

    /**
     * @param string                                $action
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param Bag                                   $parameters
     *
     * @return Collection
     */
    public function authorize(string $action, EntityContract $entity, Bag $parameters)
    {
        $errors = new Collection();

        $this->callMethods('authorize', [$action, $entity, $parameters], function ($return) use (&$errors) {
            $errors = $errors->merge($return);
        });

        return $errors;
    }

    /**
     * @param string                                $action
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param Bag                                   $parameters
     *
     * @return Collection
     */
    public function authorizeAction(string $action, EntityContract $entity, Bag $parameters)
    {
        $errors = new Collection();

        $permission = $this->getPermission($action);

        if (!$this->getManager()->getAgent()->can($permission)) {
            $errors->push($this->newException(Tokens::NOT_AUTHORIZED, $permission));
        }

        return $errors;
    }

    public function getAuthorizedAttributes(string $action)
    {
        return $this->getManager()->getAttributes()->filter(function ($attribute) use ($action, $entity) {
            $errors = $attribute->authorize($action, []);

            return $errors->count() === 0;
        });
    }

    /**
     * Create a new instance of exception.
     *
     * @param string $code
     * @param mixed  $value
     *
     * @return \Exception
     */
    public function newException(string $code, $value): Exception
    {
        $exception = $this->getException($code);

        return new $exception(
            strtoupper(Str::kebab($this->getManager()->getName())),
            $value
        );
    }
}
