<?php

namespace SlothDevGuy\MVCEnhancements\ValueObjects\Casts;

use SlothDevGuy\MVCEnhancements\ValueObjects\EntityValueObject;

/**
 * Class AsEntityValueObject
 * @package SlothDevGuy\MVCEnhancements\ValueObjects\Casts
 */
class AsEntityValueObject extends ValueObjectCast
{
    /**
     * @inheritDoc
     * @return string
     */
    protected static function valueObjectClass(): string
    {
        return EntityValueObject::class;
    }
}
