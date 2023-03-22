<?php

namespace SlothDevGuy\MVCEnhancements\DesignPatterns\Traits;

use SlothDevGuy\MVCEnhancements\Http\SimpleRespose;
use SlothDevGuy\MVCEnhancements\Interfaces\ResponseSchema;

/**
 * Trait DefaultResponseSchema
 * @package SlothDevGuy\MVCEnhancements\Traits
 */
trait DefaultResponseSchema
{
    /**
     * @return ResponseSchema
     */
    public static function defaultResponseSchema() : ResponseSchema
    {
        return new SimpleRespose();
    }
}
