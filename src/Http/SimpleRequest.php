<?php

namespace SlothDevGuy\MVCEnhancements\Http;

use Illuminate\Http\Request;
use SlothDevGuy\MVCEnhancements\Http\Traits\RequestValidate;
use SlothDevGuy\MVCEnhancements\Interfaces\Request as RequestInterface;

/**
 * Class BaseRequest
 * @package SlothDevGuy\MVCEnhancements
 */
class SimpleRequest extends Request implements RequestInterface
{
    use RequestValidate;

    /**
     * @inheritDoc
     * @param int $options
     * @return false|string
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
