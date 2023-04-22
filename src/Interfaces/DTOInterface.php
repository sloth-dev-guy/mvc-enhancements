<?php

namespace SlothDevGuy\MVCEnhancements\Interfaces;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

/**
 * Interface DTOInterface
 * @package SlothDevGuy\MVCEnhancements\Interfaces
 */
interface DTOInterface extends Arrayable, Jsonable, JsonSerializable
{

}
