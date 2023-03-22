<?php

namespace SlothDevGuy\MVCEnhancements\ValueObjects\Casts;

use SlothDevGuy\MVCEnhancements\ValueObjects\BSONCarbonValueObject;

/**
 * Class AsBSONCarbonValue
 * @package SlothDevGuy\MVCEnhancements\ValueObjects\Casts
 */
class AsBSONCarbonValue extends ValueObjectCast
{
    /**
     * @return string
     */
    protected static function valueObjectClass(): string
    {
        return BSONCarbonValueObject::class;
    }
}
