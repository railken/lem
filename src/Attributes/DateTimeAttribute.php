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
    protected $format = 'c';

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
        if ($value instanceof \DateTime) {
            return true;
        };

        try {
            $dt = new \DateTime($value);
        } catch (\Exception $e) {
            return false;
        }

        return $dt && $dt->format($this->format) == $value;
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
