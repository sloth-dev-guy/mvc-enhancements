<?php

namespace SlothDevGuy\MVCEnhancements\Interfaces;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Interface ComplexValueObject
 * @package SlothDevGuy\MVCEnhancements\Interfaces
 */
interface EntityValueObject extends ValueObject, Arrayable, Jsonable
{
    /**
     * Get the value object from the stack
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null) : mixed;

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, mixed $value) : static;

    /**
     * Maps the values object as an array of primitives
     *
     * @return array
     */
    public function map() : array;

    /**
     * Builds a new options stack class with the current attributes merged with the new attributes
     *
     * @param Arrayable|array $newAttributes
     * @return static
     */
    public function cloneWith(Arrayable|array $newAttributes = []) : static;
}
