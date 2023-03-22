<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace SlothDevGuy\MVCEnhancements\Interfaces;

use JsonSerializable;
use Stringable;

/**
 * Interface ValueObject
 * @package SlothDevGuy\MVCEnhancements\Interfaces
 *
 * A class that wraps a value object
 */
interface ValueObject extends Stringable, JsonSerializable
{
    /**
     * Get the real value object
     *
     * @return mixed
     */
    public function object() : mixed;

    /**
     * Get the value representation of this value object, usually is the string representation or other primitive type
     *
     * @return mixed
     */
    public function value() : mixed;

    /**
     * Cast the value object as another value or type, usually the same return as the value method
     *
     * @return mixed
     */
    public function cast() : mixed;

    /**
     * Transforms the value object into a string representation of it
     *
     * @return string
     */
    public function toString() : string;
}
