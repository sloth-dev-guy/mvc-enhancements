<?php

namespace SlothDevGuy\MVCEnhancements\Interfaces;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Validation\ValidationException;

/**
 * Interface Request
 * @package SlothDevGuy\MVCEnhancements\Interfaces
 */
interface Request extends Arrayable, Jsonable
{
    /**
     * Current rules fot this request
     *
     * @return array
     */
    public function rules() : array;

    /**
     * Validates the current request and fails is some rule isn't meet
     *
     * @return void
     */
    public function validate(): void;

    /**
     * Get the validated data from the request.
     *
     * @return array
     * @throws ValidationException
     */
    public function validated(): array;

    /**
     * Get a request value from the stack
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    //public function toArrayOfValueObject() : array
}
