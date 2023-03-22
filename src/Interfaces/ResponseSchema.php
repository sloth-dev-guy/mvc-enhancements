<?php

namespace SlothDevGuy\MVCEnhancements\Interfaces;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Interface ResponseSchema
 * @package SlothDevGuy\MVCEnhancements\Interfaces
 */
interface ResponseSchema extends Arrayable, Jsonable
{
    /**
     * Sets the response payload
     *
     * @param Arrayable $payload
     * @return static
     */
    public function setPayload(Arrayable $payload) : static;

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, mixed $value) : static;

    /**
     * Gets a value for the original data (dot notation supported)
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Maps the payload as the response schema specifications
     *
     * @return array
     */
    public function map() : array;
}
