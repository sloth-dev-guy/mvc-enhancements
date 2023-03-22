<?php

namespace SlothDevGuy\MVCEnhancements\ValueObjects\Traits;

/**
 * Trait ProxyObjectMethods
 * @package SlothDevGuy\MVCEnhancements\ValueObjects\Traits
 */
trait ProxyValueObjectWithObjectMethods
{
    /**
     * Proxy all the methods from the original object as mine
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->object(), $name], $arguments);
    }
}
