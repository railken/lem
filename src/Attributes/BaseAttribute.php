<?php

namespace Railken\Lem\Attributes;

use Closure;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Railken\Bag;
use Railken\Lem\Concerns;
use Railken\Lem\Contracts\AttributeContract;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Exceptions as Exceptions;
use Railken\Lem\Tokens;
use Respect\Validation\Validator as v;

abstract class BaseAttribute implements AttributeContract
{
    use Concerns\HasManager;
    use Concerns\HasPermissions;
    use Concerns\HasExceptions;

    /**
     * @var string
     */
    protected $name;

    /**
     * Is the attribute required
     * This will throw not_defined exception for non defined value and non existent model.
     *
     * @var bool
     */
    protected $required = false;

    /**
     * Is the attribute unique.
     *
     * @var bool
     */
    protected $unique = false;

    /**
     * Is the attribute fillable.
     *
     * @var bool
     */
    protected $fillable = false;

    /**
     * Is the attribute hidden.
     *
     * @var bool
     */
    protected $hidden = false;

    /**
     * A comment.
     *
     * @var string
     */
    protected $comment;

    /**
     * Default closure.
     *
     * @var Closure
     */
    protected $default;

    /**
     * Default value.
     *
     * @var mixed
     */
    protected $defaultValue = null;

    /**
     * Default value.
     *
     * @var Closure
     */
    protected $validator;

    /**
     * Schema of the attribute
     *
     * @var string
     */
    protected $schema = 'basic';

    /**
     * Is the value mutable
     *
     * @var bool
     */
    protected $mutable = true;


    /**
     * List of all exceptions used in validation.
     *
     * @var array
     */
    protected $exceptions = [
        Tokens::NOT_DEFINED    => Exceptions\AttributeNotDefinedException::class,
        Tokens::NOT_VALID      => Exceptions\AttributeNotValidException::class,
        Tokens::NOT_MUTABLE    => Exceptions\AttributeNotMutableException::class,
        Tokens::NOT_AUTHORIZED => Exceptions\AttributeNotAuthorizedException::class,
        Tokens::NOT_UNIQUE     => Exceptions\AttributeNotUniqueException::class,
    ];

    /**
     * List of all permissions.
     */
    protected $permissions = [
        Tokens::PERMISSION_WRITE => '%s.attributes.%s.write',
        Tokens::PERMISSION_READ => '%s.attributes.%s.read',
    ];

    /**
     * Create a new instance.
     *
     * @param string $name
     */
    public function __construct(string $name = null)
    {
        if ($name !== null) {
            $this->name = $name;
        }
    }

    /**
     * Create a new instance.
     */
    public static function make()
    {
        return new static(...func_get_args());
    }

    /**
     * Boot permissions.
     */
    public function bootPermissions()
    {
        $name = Str::kebab($this->getManager()->getName());
        $pName = Str::kebab($this->getName());

        foreach ($this->permissions as $token => $permission) {
            $this->permissions[$token] = sprintf($permission, $name, $pName);
        }
    }

    /**
     * Create a new instance of exception.
     *
     * @param string $code
     * @param mixed  $value
     * @param mixed $error
     *
     * @return \Exception
     */
    public function newException(string $code, $value, string $error = null): Exception
    {
        $exception = $this->getException($code);

        return new $exception(
            strtoupper(Str::kebab($this->getManager()->getName())),
            strtoupper(Str::kebab($this->getName())),
            $value,
            $error
        );
    }

    /**
     * Boot attribute.
     */
    public function boot()
    {
        $this->bootExceptions();
        $this->bootPermissions();
    }

    /**
     * Is a value valid ?
     *
     * @param string                                $action
     * @param mixed                                 $value
     *
     * @return Collection
     */
    public function authorize(string $action, $value)
    {
        $permission = $this->getPermission($action);

        $result = $this->getManager()->getAgent()->can($permission);

        if (!$result) {
            return Collection::make([$this->newException(Tokens::NOT_AUTHORIZED, $permission)]);
        }

        return Collection::make();
    }

    /**
     * Is a value valid ?
     *
     * @param string                                $action
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param mixed                                 $value
     *
     * @return Collection
     */
    public function authorizeByEntity(string $action, EntityContract $entity, $value)
    {
        $permission = $this->getPermission($action);

        $result = $this->getManager()->getAgent()->can($permission);

        if (!$result) {
            return Collection::make([$this->newException(Tokens::NOT_AUTHORIZED, $permission)]);
        }

        return Collection::make();
    }

    /**
     * Validate.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param \Railken\Bag                          $parameters
     *
     * @return Collection
     */
    public function validate(EntityContract $entity, Bag $parameters)
    {
        $errors = new Collection();

        $value = $parameters->get($this->name);

        if ($this->required && !$entity->exists && !$parameters->exists($this->name)) {
            $errors->push($this->newException(Tokens::NOT_DEFINED, $value));
        }

        if ($this->unique && $value !== null && $this->isUnique($entity, $value)) {
            $errors->push($this->newException(Tokens::NOT_UNIQUE, $value));
        }

        if ($parameters->exists($this->name) && ($value !== null || $this->required) && !$this->valid($entity, $value)) {
            $errors->push($this->newException(Tokens::NOT_VALID, $value));
        }

        return $errors;
    }

    /**
     * Is a value valid ?
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param mixed                                 $value
     *
     * @return bool
     */
    public function valid(EntityContract $entity, $value)
    {
        $validator = $this->validator;

        return $validator ? $validator($entity, $value) : true;
    }

    /**
     * Update entity value.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param \Railken\Bag                          $parameters
     * @param string                                $permission
     *
     * @return Collection
     */
    public function update(EntityContract $entity, Bag $parameters, $permission = Tokens::PERMISSION_UPDATE)
    {
        $errors = new Collection();

        $default = null;

        if (!$parameters->has($this->name) && !$entity->exists) {
            $default = $this->getDefault($entity);

            if ($default !== null) {
                $parameters->set($this->name, $default);
            }
        }


        // Skip check fillable if has a default value
        if (!$this->getFillable() && $default === null) {
            return $errors;
        }

        $errors = $errors->merge($this->authorize(Tokens::PERMISSION_WRITE, $entity, $parameters));
        $errors = $errors->merge($this->validate($entity, $parameters));
        $errors = $errors->merge($this->fill($entity, $parameters, $permission));

        return $errors;
    }


    /**
     * Update entity value.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param \Railken\Bag                          $parameters
     * @param string                                $permission
     *
     * @return Collection
     */
    public function fill(EntityContract $entity, Bag $parameters, $permission = Tokens::PERMISSION_UPDATE)
    {
        $errors = new Collection();

        if ($parameters->exists($this->name)) {

            $value = $this->parse($parameters->get($this->name));

            if ($permission === Tokens::PERMISSION_UPDATE && !$this->isMutable()) {
                $errors->push($this->newException(Tokens::NOT_MUTABLE, $value));
            } else {
                $entity->setAttribute($this->name, $value);
            }
        }

        return $errors;
    }
    /**
     * Is a value valid ?
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param mixed                                 $value
     *
     * @return bool
     */
    public function isUnique(EntityContract $entity, $value)
    {
        $q = $this->getManager()->getRepository()->getQuery()->where($this->name, $value);

        if ($entity->exists) {
            $q->where('id', '!=', $entity->id);
        }

        return $q->count() > 0;
    }

    /**
     * Retrieve name attribute.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return [$this->getName()];
    }

    /**
     * Set default value.
     *
     * @param Closure $default
     *
     * @return $this
     */
    public function setDefault(Closure $default): self
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Set default value.
     *
     * @param Closure $validator
     *
     * @return $this
     */
    public function setValidator(Closure $validator): self
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Retrieve default value.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     *
     * @return mixed
     */
    public function getDefault(EntityContract $entity)
    {
        $method = $this->default;

        return $method !== null ? $method($entity, $this) : $this->defaultValue;
    }

    /**
     * Set unique.
     *
     * @param bool $unique
     *
     * @return $this
     */
    public function setUnique(bool $unique): self
    {
        $this->unique = $unique;

        return $this;
    }

    /**
     * Is the attribute unique?
     *
     * @return bool
     */
    public function getUnique()
    {
        return $this->unique;
    }

    /**
     * Set Required.
     *
     * @param bool $required
     *
     * @return $this
     */
    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Is the attribute required?
     *
     * @return bool
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Set comment.
     *
     * @param string $comment
     *
     * @return $this
     */
    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Retrieve the comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set fillable.
     *
     * @param bool $fillable
     *
     * @return $this
     */
    public function setFillable(bool $fillable): self
    {
        $this->fillable = $fillable;

        return $this;
    }

    /**
     * Is the attribute fillable?
     *
     * @return bool
     */
    public function getFillable()
    {
        return $this->fillable;
    }

    /**
     * Set hidden.
     *
     * @param bool $hidden
     *
     * @return $this
     */
    public function setHidden(bool $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Is the attribute hidden?
     *
     * @return bool
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Parse value.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function parse($value)
    {
        return $value;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return preg_replace('/Attribute$/', '', (new \ReflectionClass($this))->getShortName());
    }

    /**
     * Get descriptor.
     *
     * @return array
     */
    public function getDescriptor()
    {
        return [];
    }

    /**
     * Save attribute.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     */
    public function save(EntityContract $entity)
    {
        return Collection::make();
    }

    /**
     * Push readable.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param \Railken\Bag                          $parameters
     *
     * @return $parameters
     */
    public function pushReadable(EntityContract $entity, Bag $parameters)
    {
        $name = $this->getName();

        $parameters->set($name, $entity->$name);
        
        return $parameters;
    }

    /**
     * Is readable
     *
     * @return bool
     */
    public function isReadable(): bool
    {
        return true;
    }

    /**
     * Get schema of attribute
     *
     * @return string
     */
    public function getSchema(): string
    {
        return $this->schema;
    }

    /**
     * Is mutable
     *
     * @return bool
     */
    public function isMutable(): bool
    {
        return $this->mutable;
    }

    /**
     * Set mutable
     *
     * @param bool $mutable
     *
     * @return $this
     */
    public function setMutable(bool $mutable): self
    {
        $this->mutable = $mutable;

        return $this;
    }
}
