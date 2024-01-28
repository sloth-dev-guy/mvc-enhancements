<?php

namespace SlothDevGuy\MVCEnhancements\DesignPatterns\Traits;

use SlothDevGuy\MVCEnhancements\Http\SimpleResponse;
use SlothDevGuy\MVCEnhancements\Interfaces\ResponseSchema;
use Symfony\Component\HttpFoundation\Response;

/**
 * Trait DefaultResponseSchema
 * @package SlothDevGuy\MVCEnhancements\Traits
 */
trait DefaultResponseSchema
{
    /**
     * @return ResponseSchema
     */
    public static function defaultResponseSchema(): ResponseSchema|Response
    {
        return new SimpleResponse();
    }
}
