<?php

namespace SlothDevGuy\MVCEnhancements\ValueObjects\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use SlothDevGuy\MVCEnhancements\Interfaces\ValueObject;
use SlothDevGuy\MVCEnhancements\ValueObjects\AttributesConverter;

/**
 * Class AsValueObject
 * @package SlothDevGuy\MVCEnhancements\ValueObjects
 */
abstract class ValueObjectCast implements CastsAttributes
{
    /**
     * The value object class in all cast implementation
     *
     * @var string
     */
    protected string $valueObjectClass;

    /**
     * Arguments used to build the new value object instance
     *
     * @var array
     */
    protected array $arguments = [];

    public function __construct()
    {
        $this->valueObjectClass = static::valueObjectClass();
    }

    /**
     * @inheritDoc
     * @param $model
     * @param string $key
     * @param $value
     * @param array $attributes
     * @return ValueObject
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return $this->makeValueObject($value);
    }

    /**
     * @inheritDoc
     * @param $model
     * @param string $key
     * @param mixed|ValueObject $value
     * @param array $attributes
     * @return array
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return [$key => $this->makeValueObject($value)->cast()];
    }

    /**
     * Check if the value object is already a valid one if not a new one will be built
     *
     * @param mixed $value
     * @return ValueObject
     */
    protected function makeValueObject(mixed $value) : ValueObject
    {
        $valueObjectClass = $this->valueObjectClass;

        if($value instanceof $valueObjectClass){
            return $value;
        }

        return AttributesConverter::makeValueObject($valueObjectClass, $value, $this->arguments);
    }

    /**
     * Value object class name used by this cast
     *
     * @return string
     */
    abstract protected static function valueObjectClass() : string;
}
