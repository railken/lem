<?php

namespace Railken\Lem\Exceptions;

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
    protected $code;

    /**
     * The message.
     *
     * @var string
     */
    protected $message;

    /**
     * Construct.
     *
     * @param string $model
     * @param string $attribute
     * @param mixed  $value
     */
    public function __construct($model = null, $attribute = null, $value = null)
    {
        $code = sprintf($this->code, $model, $attribute);
        $this->label = $attribute;
        $this->value = $value;
        $this->code = $code;
        $message = sprintf($this->message, $label);

        parent::__construct($message);
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
