<?php

namespace SlothDevGuy\MVCEnhancements\Interfaces;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

/**
 * Interface ArrayOfValueObjects
 * @package SlothDevGuy\MVCEnhancements\Interfaces
 */
interface ArrayOfValueObjects extends ValueObject, ArrayAccess, Arrayable, Jsonable
{
    /**
     * @param ValueObject[] ...$valueObjects
     * @return static
     */
    public function push(...$valueObjects) : static;

    /**
     * @param mixed $valueObject
     * @param string|null $key
     * @return static
     */
    public function add(mixed $valueObject, string $key = null) : static;

    /**
     * @return int
     */
    public function count() : int;

    /**
     * @return $this
     */
    public function clear() : static;

    /**
     * @param callable $map
     * @return array
     */
    public function map(callable $map) : array;

    /**
     * @param callable $iterator
     * @return $this
     */
    public function each(callable $iterator) : static;

    /**
     * @return Collection
     */
    public function collect() : Collection;

    /**
     * Gets the class-type for this array of value objects
     *
     * @return string
     */
    public static function valueObjectClass() : string;
}
