<?php

namespace SlothDevGuy\MVCEnhancements\ValueObjects;

use Carbon\Carbon;
use SlothDevGuy\MVCEnhancements\Interfaces\ValueObject;

/**
 * Class CarbonValueObject
 * @package SlothDevGuy\MVCEnhancements\ValueObjects
 */
class CarbonValueObject implements ValueObject
{
    /**
     * Carbon value object
     *
     * @var Carbon
     */
    protected Carbon $value;

    /**
     * @param mixed $value
     */
    public function __construct(mixed $value)
    {
        $this->value = static::fromValueToCarbon($value);
    }

    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @return mixed|Carbon
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function object(): mixed
    {
        return $this->value;
    }

    /**
     * @return mixed
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function value(): mixed
    {
        return $this->toString();
    }

    /**
     * @return mixed
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function cast(): mixed
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->value->toISOString();
    }

    /**
     * @return mixed
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function jsonSerialize(): mixed
    {
        return $this->value->jsonSerialize();
    }

    /**
     * @param mixed $value
     * @return Carbon
     */
    public static function fromValueToCarbon(mixed $value) : Carbon
    {
        if($value instanceof Carbon){
            return $value;
        }

        if(is_int($value)){
            return Carbon::createFromTimestamp($value);
        }

        if(is_float($value)){
            list($time, $microTime) = explode('.', (string) $value);

            return Carbon::createFromTimestamp($time)->addMicroseconds($microTime);
        }

        return Carbon::parse($value);
    }
}
