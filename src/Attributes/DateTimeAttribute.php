<?php

namespace Railken\Lem\Attributes;

use Railken\Lem\Contracts\EntityContract;

class DateTimeAttribute extends BaseAttribute
{
    /**
     * Schema of the attribute
     *
     * @var string
     */
    protected $schema = 'datetime';

    /**
     * Is the attribute fillable.
     *
     * @var bool
     */
    protected $fillable = true;

    /**
     * Format of date
     *
     * @var string
     */
    protected $format = 'Y-m-d H:i:s';

    /**
     * Is a value valid ?
     *
     * @param EntityContract $entity
     * @param mixed          $value
     *
     * @return bool
     */
    public function valid(EntityContract $entity, $value)
    {
        $d = \DateTime::createFromFormat($this->getFormat(), $value);

        return $d && $d->format($this->getFormat()) === $value;
    }

    /**
     * Retrieve datetime format
     *
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }
}
