<?php

namespace SlothDevGuy\MVCEnhancements\ValueObjects;

use Carbon\Carbon;
use MongoDB\BSON\UTCDateTime;

/**
 * Class BSONUTCDateTimeValueObject
 * @package SlothDevGuy\MVCEnhancements\ValueObjects
 */
class BSONCarbonValueObject extends CarbonValueObject
{
    /**
     * @return mixed|UTCDateTime
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function cast(): mixed
    {
        return new UTCDateTime($this->value->format('Uv'));
    }

    /**
     * @param mixed $value
     * @return Carbon
     */
    public static function fromValueToCarbon(mixed $value): Carbon
    {
        if($value instanceof UTCDateTime){
            return new Carbon($value->toDateTime());
        }

        return parent::fromValueToCarbon($value);
    }
}
