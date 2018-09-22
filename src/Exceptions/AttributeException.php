<?php

namespace Railken\Laravel\Manager\Exceptions;

abstract class AttributeException extends \Exception
{
    /**
     * The reason (attribute) for which this exception is thrown.
     *
     * @var string
     */
    protected $label;

    /**
     * The value used in the message.
     *
     * @var mixed
     */
    protected $value;

    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = '%s_NOT_VALID';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = 'The %s is invalid';

    /**
     * Construct.
     *
     * @param mixed $value
     */
    public function __construct($code = null)
    {
        parent::__construct(null);

        $this->code = sprintf($this->code, $code);
    }

    /**
     * Set the label.
     *
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set value.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getFinalMessage()
    {
        return sprintf($this->message, $this->getValue());
    }

    /**
     * Set the code.
     *
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
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
}
