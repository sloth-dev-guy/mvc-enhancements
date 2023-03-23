<?php

namespace Tests\ValueObjects\Eloquent\Model;

use SlothDevGuy\MVCEnhancements\ValueObjects\Casts\AsEntityValueObject;

/**
 * Class AsArrayValueObject
 * @package Tests\Unit\Eloquent
 */
class AsTestValueObject extends AsEntityValueObject
{
    /**
     * @inheritDoc
     * @return string
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    protected static function valueObjectClass(): string
    {
        $class = parent::valueObjectClass();

        logger()->channel('null')->info("original value object class[$class]");

        return EntityValueObject::class;
    }
}
