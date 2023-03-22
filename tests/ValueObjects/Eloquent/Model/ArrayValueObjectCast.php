<?php

namespace Tests\ValueObjects\Eloquent\Model;

use SlothDevGuy\MVCEnhancements\ValueObjects\Casts\ValueObjectCast;

/**
 * Class AsArrayValueObject
 * @package Tests\Unit\Eloquent
 */
class ArrayValueObjectCast extends ValueObjectCast
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
