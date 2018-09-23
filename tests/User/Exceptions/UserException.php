<?php

namespace Railken\Lem\Tests\User\Exceptions;

use Exception;
use Railken\Lem\Contracts\ExceptionContract;

class UserException extends Exception implements ExceptionContract
{
    /**
     * The reason (label) for which this exception is thrown.
     *
     * @var string
     */
    protected $label;

    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = 'USER_ERROR';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = 'An error occurred with %s';

    /**
     * Value of attribute.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Construct.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;

        if (!$this->label) {
            $this->label = get_class($this);
        }

        $this->message = sprintf($this->message, $this->label, $value);

        parent::__construct();
    }

    /**
     * Rapresents the exception in the array format.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'code'    => $this->getCode(),
            'label'   => $this->getLabel(),
            'message' => $this->getMessage(),
            'value'   => $this->getValue(),
        ];
    }

    /**
     * Get value of attribute.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get attribute.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}
