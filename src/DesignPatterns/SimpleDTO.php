<?php

namespace SlothDevGuy\MVCEnhancements\DesignPatterns;

use Illuminate\Support\Collection;
use SlothDevGuy\MVCEnhancements\Interfaces\DTOInterface;

/**
 * Class SimpleDTO
 * @package SlothDevGuy\MVCEnhancements\DesignPatterns
 */
class SimpleDTO implements DTOInterface
{
    /**
     * @var Collection
     */
    protected Collection $values;

    /**
     * @param $values
     */
    public function __construct($values)
    {
        $this->values = collect($values);
    }

    /**
     * @return array
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function toArray()
    {
        return $this->values->toArray();
    }

    /**
     * @param $options
     * @return string
     * @noinspection PhpMissingParamTypeInspection
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function toJson($options = 0)
    {
        return $this->values->toJson();
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->values->jsonSerialize();
    }
}
